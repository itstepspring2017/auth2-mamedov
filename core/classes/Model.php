<?php

abstract class Model
{
    protected $db;
    protected function __construct()
    {
        $this->db = ModuleDatabaseConnection::instance();
    }
}