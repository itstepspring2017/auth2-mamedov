<?php

namespace Entity;

abstract class Entity{

    public function fromAssoc($data){
        if (is_array($data)){
            foreach ($data as $name=>$value){
                $this->$name = $value;
            }
        }
    }


    protected static function _fromAssocies(array $elems,$className):array {
        $array = [];
        foreach ($elems as $elem){
            $entity = new $className();
            $entity->fromAssoc($elem);
            $array[] = $entity;
        }
        return $array;
    }

    abstract public static function fromAssocies(array $elems):array ;
}