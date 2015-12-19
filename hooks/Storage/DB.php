<?php


namespace hooks\Storage;

require_once BASE_DIR.'/config/DBConstants.php';

class DB extends DBWrapper{

    protected $pdo = null;
    public $useTransactions = true;
    protected $errors = [];
    protected $results = [];

    public function __construct($host = DB_HOST, $db = DB_NAME, $user = DB_USER, $pass = DB_PASS){
        try{
            $this->pdo = new \PDO($host.';dbname='.$db, $user, $pass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\Exception $e){
            die("Database Connection Failed");
        }
    }

    public function __destruct(){
        $this->pdo = null;
    }

    public function getInstance() : \PDO{
        return $this->pdo;
    }

    public function lastInsertedId(){
        return $this->getInstance()->lastInsertId();
    }

    public function processParams($params){

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
                            $paramId = $key . rand(0,99999);

                            $sql .= " " . $key. " " . $operator . " :" . $paramId ." && "; // name = :name_987
                            $DBParams[ ":" . $paramId ] = $value;               // $DBParams[":name"_987] = "Tika"
                        }

                    } else {
                        $operator = "=";
                        $paramId = $key . rand(0,99999);

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

    public function getFrom($tbl, $params = array(), $limit = null, $sort = null, $class = null, $select = "*"){
        $sql = "SELECT " . $select . " FROM ". $tbl;

        $processedParams = $this->processParams($params);
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

        $stm = $this->getInstance()->prepare($sql);
        $stm->execute($DBParams);
        if($class === null){
            return $stm->fetchAll(\PDO::FETCH_OBJ);
        } else {
            return $stm->fetchAll(\PDO::FETCH_CLASS, $class);
        }
    }

    public function save($tbl, $fields, $params){

        if($this->exists($tbl, $params)){
            return $this->updateTo($tbl, $fields, $params);
        } else {
            return $this->insertTo($tbl, $fields);
        }
    }

    public function insertTo($tbl, $fields){
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

        $this->query($sql,$DBparams);
    }

    public function updateTo($tbl, $fields, $params, $limit = null){
        $sql = "UPDATE " . $tbl ." SET" ;

        $DBParams = array();

        foreach ($fields as $key => $val){
            $sql .= " " . $key ." = " . ":".$key . ", ";
            $DBParams[ ":" . $key ] = $val;
        }

        $sql = rtrim($sql,", ");

        $processedParams = $this->processParams($params);
        if($processedParams !== null){
            $DBParams = array_merge($DBParams,$processedParams['DBParams']);
            $sql .= $processedParams['sql'];
        }


        if($limit !== null){
            $sql .= " LIMIT ".$limit;
        }

        return $this->query($sql,$DBParams);
    }

    public function deleteFrom($tbl, $params = array(), $limit = null){
        $sql = "DELETE FROM ". $tbl;

        $processedParams = $this->processParams($params);
        if($processedParams === null){
            $DBParams = [];
        } else {
            $DBParams = $processedParams['DBParams'];
            $sql .= $processedParams['sql'];
        }


        if($limit !== null){
            $sql .= " LIMIT ".$limit;
        }

        return $this->query($sql,$DBParams);
    }

    public function exists($tbl,$params){
        $items = $this->getFrom($tbl,$params);
        if(count($items) >= 1){
            return true;
        } else {
            return false;
        }
    }

    public function filter($array, $key){

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

    public function getColumns($tbl){
        $sql = "SHOW COLUMNS FROM ". $tbl;
        try{
            $stm = $this->getInstance()->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(\PDO::FETCH_OBJ);
        } catch (\Exception $e){
            return [];
        }
    }

    public function getTables($db){
        $sql = "SHOW TABLES FROM ". $db;
        try{
            $stm = $this->getInstance()->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(\PDO::FETCH_OBJ);
        } catch (\Exception $e){
            return [];
        }
    }

    public function query(string $sql, array $params = []){

        if($this->useTransactions){
            $this->getInstance()->beginTransaction();
        }

        $stm = $this->getInstance()->prepare($sql);

        try{
            $stm->execute($params);
            return $stm->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e){
            $this->errors[] = $e->getMessage();
            return false;
        }

    }

    public function commit(){
        $this->getInstance()->commit();
    }

}
