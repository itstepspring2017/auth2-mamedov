<?php

class ControllerPanel extends Controller{

    private static function is_empty(){
        foreach (func_get_args() as $arg){
            if (empty($arg)) return true;
        }
        return false;
    }

    public function action_index(){
        if(ModuleAuth::instance()->isAuth()){
            $view = new View("UserPanel/panel");
            $view->useTemplate("panel");
            $view->is_auth = ModuleAuth::instance()->isAuth();
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
            $view->URLROOT = URLROOT;
            $this->response($view);
        } else{
            $view = new View("login");
            $view->request = @$_SERVER["REQUEST_URI"];
            $this->response($view);
        }
    }

    public function action_addpost(){
        if(ModuleAuth::instance()->isAuth()){
            $view = new View("UserPanel/addpost");
            $view->useTemplate("panel");
            $view->categories = ModelCategory::instance()->getAll();
            $view->is_auth = ModuleAuth::instance()->isAuth();
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
            $view->URLROOT = URLROOT;
            $this->response($view);
        } else{
            $view = new View("login");
            $view->URLROOT = URLROOT;
            $view->request = @$_SERVER["REQUEST_URI"];
            $this->response($view);
        }
    }

    public function action_adding(){

        try{
            if(ModuleAuth::instance()->isAuth()){
                if(self::is_empty(@$_POST["name"],@$_POST["categories"],@$_FILES["image"],@$_POST["text"])){
                    throw new Exception("empty field error");
                }
                $user = ModuleAuth::instance()->getUser();
                $_ext = explode("/",$_FILES["image"]["type"]);
                $ext = end($_ext);
                $name = time()."_".$user["id"]."_".rand(10000,99999).".{$ext}";
                if(!move_uploaded_file($_FILES["image"]["tmp_name"],MEDIA_PATH."images/{$name}")){
                    throw new Exception("saving image error");
                }
                $i = new \Entity\Image("post_{$_POST["name"]}_image","media/images/$name");
                $image_id = ModelImages::instance()->addImage($i);
                $post = new \Entity\Post($_POST["name"],$_POST["text"],$user["id"],$image_id,$_POST["categories"]);
                ModelPosts::instance()->add($post);
                $this->redirect($_SERVER["HTTP_REFERER"]);
            } else{
                $view = new View("login");
                $view->URLROOT = URLROOT;
                $view->request = @$_SERVER["REQUEST_URI"];
                $this->response($view);
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public function action_myposts(){
        if(ModuleAuth::instance()->isAuth()){
            $view = new View("UserPanel/myposts");
            $view->useTemplate("panel");
            $view->posts = ModelPosts::instance()->getAllByUserId(ModuleAuth::instance()->getUserId());
            $view->URLROOT = URLROOT;
            $this->response($view);
        } else{
            $view = new View("login");
            $view->URLROOT = URLROOT;
            $view->request = @$_SERVER["REQUEST_URI"];
            $this->response($view);
        }
    }
}