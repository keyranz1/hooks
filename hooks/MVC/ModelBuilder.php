<?php


namespace hooks\MVC;

use hooks\Storage\FileSystem;

class ModelBuilder{

    public static $_db, $namespace;

    public static function build($_db = DB_NAME, $namespace = "Models"){
        self::$_db = $_db;
        self::$namespace = $namespace;

        $db = db();
        $tables = $db->getTables($_db);

        $tablesInDB = [];

        foreach ($tables as $table){
            $index = "Tables_in_".$_db;
            $tableName = $table->$index;
            $tablesInDB[] = $tableName;
        }

        foreach ($tablesInDB as $tableName){
            $columns = $db->getColumns($tableName);
            $data = self::GenerateModel($tableName, $columns);
            self::SaveModel(ucfirst($tableName), $data);
            self::DeriveRelations($tablesInDB, $tableName, $columns);

        }
    }

    public static function SaveModel($tableName, $data) {
        try{
            $file = BASE_DIR . "/".
                str_replace("\\",DIRECTORY_SEPARATOR,self::$namespace) ."/" .
                $tableName . ".php";

            FileSystem::upload($file, $data);

            echo  "<h3>Model ". $tableName ." Created:</h3>";
            echo "<hr />";
            echo "<code class='prettyprint'>";
            echo str_replace("\t","&nbsp;&nbsp;&nbsp;",nl2br(htmlentities($data)));
            echo "</code><br />";
            echo "<br />";
        } catch (\Exception $e){
            echo  "Building ". $tableName ." Model Failed";
        }
    }

    public static function GenerateModel($tableName, $columns) : string {
        $model = "<?php\n\n";

        $model .= "namespace ".self::$namespace.";\n\n";
        $model .= "use hooks\\MVC\\DBContext;\n\n";

        $model .= "class " . ucfirst($tableName) . " extends DBContext {\n\n";

        //Public Vars
        $model .= "\tpublic ";

        foreach ($columns as $col){
            $model  .=  "$" . $col->Field . ", ";
            if($col->Key === "PRI"){
                $primaryKey = $col->Field;
            }
        }
        $model = rtrim($model, ", ") . ";\n\n";


        //DB Table
        $model .= "\tprotected $". "context = \"" . $tableName . "\";\n\n";

        //DB Key
        if(isset($primaryKey)){

            $model .= "\tprotected $". "contextPrimaryKey = \"" . $primaryKey . "\";\n\n";
        }


        $model .= "\tpublic function __construct( $" ."key = null)\n";
        $model .= "\t{\n";
        $model .= "\t\tparent::__construct($"."key);\n";
        $model .= "\t}\n";




        $model .= "\n}";

        return $model;
    }

    public static function DeriveRelations($tables, $tableName, $columns){

        $data  = [];
        foreach($columns as $col){

            if($col->Type == "timestamp"){
                $data[$col->Field] = "DateTime";
            }

            else if($col->Type == "date"){
                $data[$col->Field] = "DateTime";
            }

            else if($col->Type == "time"){
                $data[$col->Field] = "DateTime";
            }

            else if($col->Field == "image"){
                $data[$col->Field] = "hooks\\Media\\Image";
            }

            else if(in_array($col->Field, $tables)){
                $data[$col->Field] = self::$namespace ."\\" . ucfirst($col->Field);
            }

        }

        echo count($data) . " relations defined.";

        FileSystem::put("Models/.model-cache/". $tableName . ".rln", json_encode($data, JSON_PRETTY_PRINT));
    }

}