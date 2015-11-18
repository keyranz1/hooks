<?php


namespace Framework;

require_once BASE_DIR.'/config/DBConstants.php';

class DB{

    public static $pdo = null;

    public static function getInstance(){
        if(self::$pdo == null){
            self::PDOConnect();
        }
        return self::$pdo;
    }

    private static function PDOConnect(){
        try{
            self::$pdo = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\Exception $e){
            die("Database Connection Failed");
        }
    }

    public static function lastInsertedId(){
        return DB::$pdo->lastInsertId();
    }

    public static function processParams($params){


        if(count($params) > 0){

            $sql = " WHERE";
            $DBParams = [];

            $accepted_params = array('=','<','>','LIKE','BETWEEN','!=');

            //Case of 1D Naked array with operator
            //Eg : ["name","=","tika"]
            if(count($params) === 3 && isset($params[1]) &&in_array($params[1], $accepted_params)){
                $key = $params[0];
                $operator = $params[1];
                $value = $params[2];

                $sql .= " " . $key. " " . $operator . " :p" . $key ." && "; // name = :name
                $DBParams[ ":p" . $key ] = $value;               // $DBParams[":name"] = "Tika"

            } else {
                foreach($params as $key => $value){

                    if(is_array($value)){
                        if(count($value) === 3 && isset($value[1]) &&in_array($value[1], $accepted_params)){
                            $key = $value[0];
                            $operator = $value[1];
                            $value = $value[2];
                            $paramId = $key . rand(0,9999);

                            $sql .= " " . $key. " " . $operator . " :" . $paramId ." && "; // name = :name_987
                            $DBParams[ ":" . $paramId ] = $value;               // $DBParams[":name"_987] = "Tika"
                        }

                    } else {
                        $operator = "=";
                        $paramId = $key . rand(0,9999);

                        $sql .= " " . $key. " " . $operator . " :" . $paramId ." && "; // name = :name_987
                        $DBParams[ ":" . $paramId ] = $value;               // $DBParams[":name"_987] = "Tika"
                    }


                }
            }

            $sql = rtrim($sql," && ");

            return [
                "sql" => $sql,
                "DBParams" => $DBParams
            ];

        }
        return null;

    }

    public static function get($tbl, $params = array(), $limit = null, $sort = null, $class = null){
        $sql = "SELECT * FROM ". $tbl;

        $processedParams = self::processParams($params);
        if($processedParams === null){
            $DBParams = [];
        } else {
            $DBParams = $processedParams['DBParams'];
            $sql .= $processedParams['sql'];
        }


        if($sort != null){
            $orderKey = array_keys($sort)[0];
            $order = $sort[$orderKey];
            $sql .= " ORDER BY ". $orderKey . " " . $order;
        }


        if($limit !== null){
            $sql .= " LIMIT ".$limit;
        }




        $stm = self::getInstance()->prepare($sql);
        $stm->execute($DBParams);
        if($class === null){
            return $stm->fetchAll(\PDO::FETCH_OBJ);
        } else {
            return $stm->fetchAll(\PDO::FETCH_CLASS, $class);
        }
    }

    public static function insert($tbl, $fields){
        $sql = "INSERT INTO " . $tbl ;
        $keys = ""; $vals = "";

        $DBparams = array();

        foreach ($fields as $key => $val){
            $keys .= $key .", ";
            $vals .= ":" . $key .", ";
            $DBparams[ ":" . $key ] = $val;
        }

        $keys = rtrim($keys,", ");
        $vals = rtrim($vals,", ");

        $sql .= " (" . $keys . ") VALUES (" . $vals .")";


        $stm = self::getInstance()->prepare($sql);
        try{
            $stm->execute($DBparams);
            return true;
        } catch (\Exception $e){
            return $e->getMessage();
        }

    }

    public static function update($tbl, $fields, $params){
        $sql = "UPDATE " . $tbl ." SET" ;

        $DBParams = array();

        foreach ($fields as $key => $val){
            $sql .= " " . $key ." = " . ":".$key . ", ";
            $DBParams[ ":" . $key ] = $val;
        }

        $sql = rtrim($sql,", ");

        $processedParams = self::processParams($params);
        if($processedParams !== null){
            $DBParams = array_merge($DBParams,$processedParams['DBParams']);
            $sql .= $processedParams['sql'];
        }

        $stm = self::getInstance()->prepare($sql);
        try{
            $stm->execute($DBParams);
            self::$pdo->errorInfo();
            return true;
        } catch (\Exception $e){
            return $e->getMessage();
        }


    }

    public static function delete($tbl, $params = array(), $limit = null){
        $sql = "DELETE FROM ". $tbl;

        $processedParams = self::processParams($params);
        if($processedParams === null){
            $DBParams = [];
        } else {
            $DBParams = $processedParams['DBParams'];
            $sql .= $processedParams['sql'];
        }


        if($limit !== null){
            $sql .= " LIMIT ".$limit;
        }


        $stm = self::getInstance()->prepare($sql);
        return $stm->execute($DBParams);
    }

    public static function exists($tbl,$params){
        $items = self::get($tbl,$params);
        if(count($items) >= 1){
            return true;
        } else {
            return false;
        }
    }

    public static function filter($array, $key){

        foreach($array as $index => $item){

            foreach($item as $subItem){

                if(strpos($subItem, $key) !== false){
                    continue 2;
                }

            }

            unset($array[$index]);


        }

        return $array;

    }

}

