<?php

use Entity\Image;

class ModelImages extends Model{
    private static $instance = null;

    protected function __construct(){
        parent::__construct();
    }

    public static function instance():self {
        return self::$instance ? self::$instance : self::$instance = new self();
    }

    public function getAll():array{
        return Image::fromAssocies($this->db->images->getAll());
    }

    public function getById(int $id):Image{
        $img = new Image();
        $img->fromAssoc($this->db->images->get($id));
        return $img;
    }

    public function addImage(Image $image):int{
        return $this->db->images->insert([
           "name"=>$image->name,
           "url"=>$image->url
        ]);
    }
}