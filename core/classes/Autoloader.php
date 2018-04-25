<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 19.03.2018
 * Time: 20:43
 */

class Autoloader
{
    private static $paths = [
        [
            "pattern"=>"/^Model([A-Z0-9][a-z0-9]*)+$/",
            "path"=>MODELS_PATH
        ], [
            "pattern"=>"/^Module([A-Z0-9][a-z0-9]*)+$/",
            "path"=>MODULES_PATH
        ], [
            "pattern"=>"/^Controller([A-Z0-9][a-z0-9]*)+$/",
            "path"=>CONTROLLERS_PATH
        ],[
            "pattern"=>"/^Entity\\\([A-Z0-9][a-z0-9]*)+$/",
            "path"=>APP_PATH."entities/"
        ]
    ];

    public static function load($name){

        foreach (self::$paths as $path){
            if(preg_match($path["pattern"],$name)){
                $className = explode("\\",$name);
                $name = end($className);
                include $path["path"].$name.".php";
            }
        }
    }
}