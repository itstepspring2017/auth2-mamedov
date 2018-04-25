<?php

include MODULES_PATH."ModuleHash/PassHasher.php";

use \ModuleHash\PassHasher;

class ModuleHash{
    private static $PassHasher = NULL;

    public static function getPassHasher():PassHasher{
        $pass_hasher_config = Config::load("hash")->passHasher;
        $salt_pos = $pass_hasher_config["salt_pos"];
        $salt_len = $pass_hasher_config["salt_len"];
        $alg = $pass_hasher_config["alg"];
        return self::$PassHasher === NULL
             ? self::$PassHasher = new PassHasher($alg,$salt_pos,$salt_len)
             : self::$PassHasher;
    }


}