<?php

namespace ModuleValidator;

abstract class Rule{
    abstract function test(string $str):bool;
    private $name;
    const PATTERN = "/\{.+}\/i";
    protected $params = [];
    
    public function __construct($pattern){
        $this->pattern=$pattern;
    }
}