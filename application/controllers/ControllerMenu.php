<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 18.04.2018
 * Time: 20:47
 */

class ControllerMenu extends Controller{
    public function rightMenu(){
        $view = new View("components/rmenu");
        $view->categories = ModelCategory::instance()->getAll();
        $this->response($view);
    }
}