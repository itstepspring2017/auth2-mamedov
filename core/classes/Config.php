<?php
class Config{
    public static function load($name){
        if(file_exists(APP_CONFIG_PATH.$name.".php"))
            return (object)include APP_CONFIG_PATH.$name.".php";
        if(file_exists(CONFIG_PATH.$name.".php"))
            return (object)include CONFIG_PATH.$name.".php";
        return NULL;
    }
}