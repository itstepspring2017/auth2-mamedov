<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 19.03.2018
 * Time: 14:48
 */

abstract class controller
{
    private $response;

    protected function response(string $response): void
    {
        $this->response = $response;
    }

    protected function getUriParam(string $name):?string
    {
        return Router::getInstance()->getParamFromActiveRoute($name);
    }

    protected function redirect(string $uri): void
    {
        Router::getInstance()->redirect($uri);
    }

    protected function redirect404(): void
    {
        Router::getInstance()->redirect404();
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function sendResponse()
    {
        echo $this->response;
    }
}