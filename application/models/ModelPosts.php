<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 18.04.2018
 * Time: 20:22
 */
use Entity\Post;
class ModelPosts extends Model
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
        return Post::fromAssocies($this->db->posts->getAllWhere());
    }
    public function getByUserId(int $id)
    {
        return Post::fromAssocies($this->db->posts->getAllWhere("users_id=?",[$id]));
    }
    public function getLast(int $n){
        return Post::fromAssocies($this->db->posts->desc()->limit($n)->all());
    }

    public function getById(int $id): Post
    {
        $img = new Post();
        $img->fromAssoc($this->db->posts->getElementById($id));
        return $img;
    }

    public function add(Post $post): int
    {
        return $this->db->posts->insert([
            "name"=>$post->name,
            "text"=>$post->text,
            "time"=>$post->time,
            "images_id"=>(int)$post->images_id,
            "categories_id"=>(int)$post->categories_id,
            "users_id"=>(int)$post->users_id
        ]);
    }
}