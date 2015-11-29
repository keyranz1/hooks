<?php


namespace Framework\Services;

use Framework\Storage\DB;

class ServerTracker
{

    public static function track()
    {
        $time = microtime(true) - SERVER_INIT;
        $url = self::getCompleteURL();
        $ip = self::getClientIP();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $data = compact("url","ip","ua","time");

        try{
            if($time < MAX_CPU_TIME){
                DB::insertTo(TRACKER_TABLE,$data);
            } else {
                self::blacklist();
            }
        } catch (\Exception $e){

        }

    }

    public static function getClientIP(){
        if ( isset ($_SERVER['HTTP_CLIENT_IP'] ) )
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if ( isset ($_SERVER['HTTP_X_FORWARDED_FOR'] ) )
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ( isset ($_SERVER['HTTP_X_FORWARDED'] ) )
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if ( isset ($_SERVER['HTTP_FORWARDED_FOR'] ) )
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if ( isset ($_SERVER['HTTP_FORWARDED'] ) )
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if ( isset ($_SERVER['REMOTE_ADDR'] ) )
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;

    }

    public static function getCompleteURL(){
        $base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
        return $base_url . $_SERVER["REQUEST_URI"];
    }

    public static function blacklist($reason = "Request Time Overload"){

        $url = self::getCompleteURL();
        $ip = self::getClientIP();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $time = microtime(true)- SERVER_INIT;
        $data = compact("url","ip","ua","time", "reason");

        try{
            DB::insertTo(BLACKLIST_TABLE,$data);
        } catch (\Exception $e){

        }
    }

    public static function isBlacklisted(){
        return DB::exists(BLACKLIST_TABLE,["ip" => self::getClientIP(), "cleared" => 0]);
    }

}