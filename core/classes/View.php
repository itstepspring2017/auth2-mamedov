<?php
class View{
    private $view,$template = NULL;
    private $params = [];
    private static $twig = NULL;
    private static $twig_template = NULL;

    private static function twigInit(){
        $loader1 = new Twig_Loader_Filesystem(TEMPLATES_PATH);
        $loader2 = new Twig_Loader_Filesystem(VIEWS_PATH);
        self::$twig = new Twig_Environment($loader2);
        self::$twig_template = new Twig_Environment($loader1);
    }

    public function useTemplate($name="default"){
        $this->template=$name;
    }


    public function __construct($name){
        if(self::$twig === NULL){
            self::twigInit();
        }
//        $this->params["auth"] = ModuleAuth::instance();
        $this->view=$name;
    }

    public function set($name,$value){
        $this->params[$name]=$value;
    }

    public function __set($name, $value){
        $this->params[$name]=$value;
    }

    public function __get($name){
        return @$this->params[$name];
    }

    protected function _renderView(){
        return self::$twig->render($this->view.".twig",$this->params);
    }

    protected function _renderViewWithTemplate(){
        $this->params["view"] = $this->_renderView();
        return self::$twig_template->render($this->template.".twig",$this->params);
    }

    public function render(array $data =[]){
        $this->params = array_merge($this->params,$data);
        return $this->template===NULL?$this->_renderView():$this->_renderViewWithTemplate();
    }

    public function __toString(){
        return $this->render();
    }


}