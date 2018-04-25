<?php

namespace Entity;

class Post extends Entity {
    public $name;
    public $text;
    public $time;
    public $user_id;
    public $image_id;
    public $category_id;


    public function __construct(string $name="", string $text="",int $user_id=0,int $image_id=0,int $category_id=0){
        $this->name = $name;
        $this->text = $text;
        $this->time = time();
        $this->user_id = $user_id;
        $this->image_id = $image_id;
        $this->category_id = $category_id;
    }

    public function getImage():Image{
        return \ModelImages::instance()->getById($this->image_id);
    }

    public function getCategory():Category{
        return \ModelCategory::instance()->getById($this->category_id);
    }

    public static function fromAssocies(array $elems): array{
        return Entity::_fromAssocies($elems,self::class);
    }
}