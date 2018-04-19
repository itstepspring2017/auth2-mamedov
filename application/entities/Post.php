<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 18.04.2018
 * Time: 19:50
 */

namespace Entity;


class Post extends Entity
{
    public $id,$name, $text,$likes, $views,$time,$users_id,$images_id, $categories_id;

    public function __construct(string $name="",string $text="",int $user_id=0,int $images_id=0,int $categories_id=0)
    {
        $this->name = $name;
        $this->text = $text;
        $this->time = time();
        $this->users_id = $user_id;
        $this->images_id = $images_id;
        $this->categories_id = $categories_id;
    }

    public function getImage():Image{
        return \ModelImages::instance()->getById($this->images_id);
    }
    public function getCategory():Category{
        return \ModelCategory::instance()->getById($this->categories_id);
    }
    public static function fromAssocies(array $array): array
    {
        return self::_fromAssocies($array,self::class);
    }
}