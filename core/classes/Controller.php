<?php


abstract class Controller{
    private $response;

    protected function response($response){
        $this->response = (string)$response;
    }

    protected function getUriParam($name){
        return Router::instance()->getParam($name);
    }

    protected function redirect($uri){
        Router::instance()->redirect($uri);
    }

    protected function redirect404(){
        Router::instance()->redirect404();
    }

    public function getResponse(){
        return $this->response;
    }

    public function sendResponse(){
        echo $this->response;
    }
}