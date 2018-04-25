<?php
require_once CLASS_PATH."Route.php";
require_once CLASS_PATH."exceptions/RouterException.php";
class Router{
    private $routes = [];
    private $active_route;

    private static $inst = NULL;
    private function __construct(){
        $this->routes[] = new Route("404",[
           "controller"=>"404",
           "action"=>"index"
        ]);
    }
    public static function instance(){
        if(self::$inst==NULL) self::$inst=new self();
        return self::$inst;
    }
    public function addRoute(Route $route){
        $this->routes[] = $route;
    }


    private $components = NULL;
    private function parseURI(){
        if($this->components!=NULL) return;
        $uri = explode("?",$_SERVER["REQUEST_URI"])[0];
        $uri = trim($uri,"/");
        $this->components = explode("/",$uri);
    }


    public function navigate($controller,$action)
    {
        if(empty($controller)||empty($action)) throw new RouterException("Incorrect Route");
        $controller = "Controller".ucfirst($controller);

        $action = "action_".$action;
        $controller_path = CONTROLLERS_PATH.$controller.".php";
        if(!file_exists($controller_path)) throw new RouterException("Controller '{$controller}' not found");
        include $controller_path;
        $controller_instance = new $controller();
        if(!method_exists($controller_instance,$action)) throw new RouterException("Action '{$action}' not found");
        $controller_instance->$action();
        $controller_instance->sendResponse();
    }


    public function run(){
        $this->parseURI();
        foreach ($this->routes as $route){
            if(!$route->exec($this->components)) continue;
            $this->active_route=$route;
            $this->navigate($this->active_route->getController(),$this->active_route->getAction());
            return;
        }
        throw new RouterIncorrectUriException("not find route");
    }



    public function getParam($name){
        return $this->active_route->getParam($name);
    }
    public function redirect($uri){
        header("Location:".$uri);
    }
    public function redirect404(){
        header("Location:".URLROOT."404");
    }



}