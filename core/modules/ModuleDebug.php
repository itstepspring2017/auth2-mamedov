<?php

class ModuleDebug{
    private static function _dump($array){
        echo "<ul style='background: black;padding: 20px'>";
        foreach ($array as $key=>$value){
            echo "<li style='color: green'><span style='color: red'>{$key} => </span>";
            if(!is_array($value)){
                echo "{$value}(".gettype($value).")";
            } else {
                self::_dump($value);
            }
            echo "</li>";
        }
        echo "</ul>";
    }

    private static function baseDump($value){
        if(is_array($value)){
            self::_dump($value);
        } else{
            echo "<pre>";
            var_dump($value);
        }
    }

    public static function dump($value){
        echo "<style>body{padding-top: 50vh !important;}</style>";
        echo "<div style='position: fixed;z-index: 100500; top: 0;left: 0;right: 0;height: 50vh;overflow-y: scroll;'>";
        self::baseDump($value);
        echo "</div>";
    }

    public static function dumpDie($value){
        self::baseDump($value);
        die();
    }
    public static function var_dump($value){
        echo "<pre>";
        var_dump($value);
    }
}