<?php

use Entity\Category;

class ModelCategory extends Model{
    private static $instance = null;

    protected function __construct(){
        parent::__construct();
    }

    public static function instance():self {
        return self::$instance ? self::$instance : self::$instance = new self();
    }

    public function getAll():array{
        return Category::fromAssocies($this->db->categories->getAll());
    }

    public function getById(int $id):Category{
        $img = new Category();
        $img->fromAssoc($this->db->categories->get($id));
        return $img;
    }

    public function add(Category $cat):void{
        $this->db->categories->insert([
            "name"=>$cat->name,
            "author"=>$cat->image_id
        ]);
    }
}