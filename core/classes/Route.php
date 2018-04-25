<?php
class Route{
    private $components = [];
    private $params=[];

    public function __construct($pattern,$default_params=[]){
        $pattern=ltrim(URLROOT.$pattern,"/");
        $this->components = explode("/",$pattern);
        $this->params = $default_params;
    }
    const PARAM_REGEXP = "/\{\??([a-z][a-z0-9]*)\}/i";
    const OPT_PARAM_REGEXP = "/\{\?([a-z][a-z0-9]*)\}/i";
    private function isParam($name,$value){
        $value = strtolower($value);
        if(!preg_match(self::PARAM_REGEXP,$name,$arr)) return false;
        $param = $arr[1];
        $this->params[$param] = $value;
        return true;
    }
    private function isTmpParam($name){
        return preg_match(self::OPT_PARAM_REGEXP,$name);
    }


    public function exec(array $real_route_components){
        if($real_route_components[1]=="" && $this->components[1]=="") return true;
        $count = count($this->components);
        if(count($real_route_components)>$count) return false;
        for($i=0;$i<$count;$i++){
//            if($real_route_components[$i] === $this->components[$i]) continue;
            if(empty($real_route_components[$i])&&$this->isTmpParam($this->components[$i])) return true;
            if(empty($real_route_components[$i])) return false;
            if(
                (!$this->isParam($this->components[$i],$real_route_components[$i]))
                && ($this->components[$i]!=$real_route_components[$i])
            ) return false;
        }
        return true;
    }

    public function getController(){
        return strtolower(@$this->params["controller"]);
    }

    public function getAction(){
        return strtolower(@$this->params["action"]);
    }
    public function getParam($name){
        return @$this->params[$name];
    }



}