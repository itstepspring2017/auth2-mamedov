<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 18.04.2018
 * Time: 19:24
 */

use \Entity\Category;
class ModelCategory extends Model
{
    private static $instance = null;
    protected function __construct(){
        parent::__construct();
    }
    public static function instance():self{
        return self::$instance ? self::$instance : self::$instance = new self();
    }

    public function getAll()
    {
        return Category::fromAssocies($this->db->categories->getAllWhere());
    }

    public function getById(int $id): Category
    {
        $img = new Category();
        $img->fromAssoc($this->db->categories->getElementById($id));
        return $img;
    }

    public function add(Category $cat): void
    {
        $this->db->categories->insert([
            "name"=>$cat->name,
            "image_id"=>$cat->image_id
        ]);
    }
}