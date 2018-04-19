<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 18.04.2018
 * Time: 19:19
 */

namespace Entity;


class Category extends Entity
{
    public $id,$name,$images_id;

    public function __construct(string $name="",int $image_id=0)
    {
        $this->name = $name;
        $this->images_id = $image_id;
    }

    public function getImage():Image{
        return \ModelImages::instance()->getById($this->images_id);
    }
    public static function fromAssocies(array $array): array
    {
        return self::_fromAssocies($array,self::class);
    }
}