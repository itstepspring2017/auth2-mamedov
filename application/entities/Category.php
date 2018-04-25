<?php

namespace Entity;

class Category extends Entity {
    public $id;
    public $name;
    public $image_id;

    public function __construct(string $name="",int $image_id = 0){
        $this->name = $name;
        $this->image_id= $image_id;
    }

    public static function fromAssocies(array $elems): array{
        return Entity::_fromAssocies($elems,self::class);
    }

    public function getImage():Image{
        return \ModelImages::instance()->getById($this->image_id);
    }
}