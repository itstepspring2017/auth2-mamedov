<?php
defined("DOCROOT") or die("NO DIRECT ACCESS");
include CLASS_PATH."Config.php";
include CLASS_PATH."Router.php";
include CLASS_PATH."Controller.php";
include CLASS_PATH."View.php";
include CLASS_PATH."Model.php";
include CLASS_PATH."Entity.php";
include CLASS_PATH."Autoloader.php";

session_start();

spl_autoload_register("Autoloader::load");

$router = Router::instance();

$router->addRoute(new Route("",[
    "controller"=>"main",
    "action"=>"index"
]));

$router->addRoute(new Route("regestry",[
    "controller"=>"main",
    "action"=>"register"
]));

$router->addRoute(new Route("registration",[
    "controller"=>"auth",
    "action"=>"register"
]));

$router->addRoute(new Route("login",[
    "controller"=>"auth",
    "action"=>"login"
]));

$router->addRoute(new Route("logout",[
    "controller"=>"auth",
    "action"=>"logout"
]));

$router->addRoute(new Route("panel",[
    "controller"=>"panel",
    "action"=>"index"
]));

$router->addRoute(new Route("categories",[
    "controller"=>"categories",
    "action"=>"index"
]));

$router->addRoute(new Route("authorization",[
    "controller"=>"main",
    "action"=>"login"
]));

$router->addRoute(new Route("panel/addpost",[
    "controller"=>"panel",
    "action"=>"addpost"
]));

$router->addRoute(new Route("panel/adding",[
    "controller"=>"panel",
    "action"=>"adding"
]));

$router->addRoute(new Route("panel/myposts",[
    "controller"=>"panel",
    "action"=>"myposts"
]));

$router->addRoute(new Route("categories/{id}",[
    "controller"=>"categories",
    "action"=>"category"
]));

try{
    $router->run();
}catch (RouterException $e){
    //        $router->redirect404();
    echo $e->getMessage();
}


