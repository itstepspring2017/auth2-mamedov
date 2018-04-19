<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 21.03.2018
 * Time: 13:24
 */

class ControllerMain extends Controller
{
    private $mController;
    public function __construct(){
        $this->mController = new ControllerMenu();
        $this->mController->rightMenu();
    }


    public function action_index()
    {

        $view = new View("main");
        $view->useTemplate();
        $view->rmenu = $this->mController->getResponse();
        $view->posts = ModelPosts::instance()->getLast(10);
        $this->response($view);
    }

//    public function action_test()
//    {
//        $name = $this->getUriParam("name");
//        $this->response("Name:{$name}");
//    }



    public function action_login()
    {
        $view = new View("login");
        $view->rmenu = $this->mController->getResponse();
        $view->useTemplate();
        $this->response($view);
    }

    public function action_register()
    {
        $view = new View("register");
        $view->useTemplate();
        $view->rmenu = $this->mController->getResponse();
        if(!empty($_SESSION["validate_error"])){
            $view->error = $_SESSION["validate_error"];
            $view->old = $_SESSION["old"];
            unset($_SESSION["validate_error"]);
            unset($_SESSION["old"]);
        }
        $this->response($view);
    }
}