<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 19.03.2018
 * Time: 21:08
 */

class Controller404 extends Controller{
    public function action_index()
    {
        header("HTTP/1.1 404 Not Found",true,404);
        echo "<h1>404</h1>";
    }
}