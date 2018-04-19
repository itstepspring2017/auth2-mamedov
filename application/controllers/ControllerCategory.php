<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 18.04.2018
 * Time: 21:01
 */

class ControllerCategory extends Controller
{
    public function action_category(){
        $id = (int)$this->getUriParam("id");
        echo $id;
    }
}