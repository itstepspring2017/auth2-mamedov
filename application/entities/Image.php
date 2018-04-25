<?php

namespace Entity;

class Image extends Entity {
    public $id;
    public $name;
    public $url;

    public function __construct(string $name="", string $url=""){
        $this->name = $name;
        $this->url = $url;
    }

    public static function fromAssocies(array $elems): array{
        return Entity::_fromAssocies($elems,self::class);
    }
}