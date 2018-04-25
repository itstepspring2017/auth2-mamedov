<?php
include MODULES_PATH."ModuleDatabase/Executor.php";

use ModuleDatabase\Executor;

class ModuleDatabase{
    private static $inst = NULL;


    public static function instance(){
        if(self::$inst==NULL) self::$inst=new self();
        return self::$inst;
    }

    private $dbh;
    private $tables;

    private function __construct(){
        $config = Config::load("database");
        $connecting = "mysql:dbname={$config->dbname};host={$config->host};charset={$config->charset}";
        $this->dbh = new PDO($connecting,$config->login,$config->pass,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC

        ]);
        $this->tables = $this->dbh->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function __get($name){
        if( !in_array($name,$this->tables) ){
            throw new Exception("Table not found");
        }
        return new Executor($this->dbh,$name);
    }
}