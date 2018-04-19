<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 18.04.2018
 * Time: 18:48
 */
class ControllerHome extends Controller{
    private $mController;
    public function __construct(){
        $this->mController = new ControllerMenu();
        $this->mController->rightMenu();
    }
    public function action_myposts(){
        if(!ModuleAuth::instance()->isAuth()) $this->redirect404();
        $view = new View("home/posts");
        $view->rmenu = $this->mController->getResponse();
        $view->posts = ModelPosts::instance()->getByUserId(
            ModuleAuth::instance()->getUser()["id"]
        );
        $view->useTemplate();
        $this->response($view);
    }

    public function action_addpost(){
        if(!ModuleAuth::instance()->isAuth()) $this->redirect404();
        $view = new View("home/addpost");
        $view->useTemplate();
        $view->rmenu = $this->mController->getResponse();
        $view->categories = ModelCategory::instance()->getAll();
        $this->response($view);
    }
    private static function is_empty()
    {
        foreach (func_get_args() as $arg) {
            if (empty($arg)) return true;
        }
        return false;
    }
    public function action_addpostaction()
    {
        try {
            if (!ModuleAuth::instance()->isAuth()) $this->redirect404();
            if (self::is_empty(@$_POST["name"], @$_POST["cat"], @$_POST["text"], @$_FILES["image"])) {
                throw new Exception("ERROR EMPTY FIELD");
            }
            $user = ModuleAuth::instance()->getUser();
            $_ext = explode("/", $_FILES["image"]["type"]);
            $ext = end($_ext);

            $name = time() . "_" . $user["id"] . "_" . rand(10000, 99999) . ".{$ext}";
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_PATH . "images/{$name}")) {
               throw new Exception("ERROR SAVE IMAGE");
            }

            $i = new \Entity\Image("post_{$_POST["name"]}_image", "/media/images/$name");
            $image_id = ModelImages::instance()->addImage($i);

            $post = new \Entity\Post($_POST["name"],$_POST["text"],$user["id"],$image_id,$_POST["cat"]);
            ModelPosts::instance()->add($post);
            $this->redirect("/home/posts");
        }catch (Exception $e){
            echo $e->getMessage();
        }


    }
}
