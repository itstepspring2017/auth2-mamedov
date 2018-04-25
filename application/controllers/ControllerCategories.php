<?php

class ControllerCategories extends Controller{
    public function action_index(){
        $view = new View("categories/categories");
        $view->useTemplate();
        $view->URLROOT = URLROOT;
        $view->categories = ModelCategory::instance()->getAll();
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }

    public function action_category(){
        $id = (int)$this->getUriParam("id");
        $view = new View("categories/category");
        $view->useTemplate();
        $view->id = $id;
        $view->URLROOT = URLROOT;
        $cat = ModelCategory::instance()->getById($id);
        if($cat->id === null){
            $this->redirect404();
        }else{
            $view->category = $cat;
        }
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }

}