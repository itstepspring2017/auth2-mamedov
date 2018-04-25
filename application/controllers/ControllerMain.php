<?php
class ControllerMain extends Controller
{
    public function action_index(){
        $view = new View("main");
        $view->useTemplate();
        $view->posts = ModelPosts::instance()->getLast(10);
        $view->URLROOT = URLROOT;
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }

    public function action_login(){
        $view = new View("login");
        $this->response($view);
    }

    public function action_register(){
        $view = new View("register");
        if(!empty($_SESSION["validate_error"])){
            $view->error = $_SESSION["validate_error"];
            $view->old = $_SESSION["old"];
            unset($_SESSION["validate_error"]);
            unset($_SESSION["old"]);
        }
        $view->useTemplate();
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }
}