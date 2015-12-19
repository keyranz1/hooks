<?php

namespace hooks\MVC;



abstract class DBContext extends DBContextRelational
{
    protected $context;
    protected $contextPrimaryKey;

    public function __construct($key = null)
    {
        if($key != null && is_scalar($key) && $this->contextPrimaryKey != null){
            $this->find($this->contextPrimaryKey, $key);
        }
        parent::__construct();
    }

    protected function find($prop, $val){
        $data = db()->from($this->context)->select()->where([$prop => $val])->limit(1)->execute();
        if(count($data) === 1){
            $inf =  $data->first();
            foreach($inf as $k => $v){
                if(property_exists($this, $k)){
                    $this->$k = $v;
                }
            }
        }
    }

    public function __toString() : string{
        return json_encode($this, JSON_PRETTY_PRINT);
    }

    public static function __callStatic($method, $arguments = [])
    {
        if(method_exists(__CLASS__, $method)){
            return call_user_func_array(array(__CLASS__, $method), $arguments);
        }

        $names = explode("where", $method);
        if(count($names) == 2 && $names[0] == ""){

            $prop = $names[1];
            $firstLetterLowerCase =  strtolower($prop[0]) . substr($prop,1);

            if(property_exists(get_called_class(), $firstLetterLowerCase)){

                return self::where([$firstLetterLowerCase => $arguments[0]]);
            }
            //As Passed Test for those who like Property Names beginning with UpperCase
            if(property_exists(get_called_class(), $prop)){

                return self::where([$prop => $arguments[0]]);
            }

        }


        die("Method " . $method . "() does not exist in " . __CLASS__. " at line <strong>" .
            __LINE__ . "</strong> in file <strong>" . __FILE__ . "</strong>");

    }

    public static function all() : array{
        $class = get_called_class();
        $contexts = explode("\\",$class);
        $context = end($contexts);
        return db()->select("*")->from($context)->cast($class)->all();
    }

    public static function where($array){
        $class = get_called_class();
        $contexts = explode("\\",$class);
        $context = end($contexts);
        return db()->select("*")->from($context)->where($array)->cast($class);
    }

}