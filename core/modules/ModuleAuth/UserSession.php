<?php

namespace ModuleAuth;

class UserSession{
    const COOKIE_KEY = "zarathustra";
    private $time;
    private $long_time;
    private static $inst = NULL;
    private $session = NULL;

    public static function instance(){
        return self::$inst === NULL
            ? self::$inst = new self()
            : self::$inst;
    }

    private function __construct(){
        $config = \Config::load("user_session");
        $this->time = $config->time;
        $this->long_time = $config->longtime;
    }

    private static function getIp(){
        return md5($_SERVER["REMOTE_ADDR"]);
    }

    private static function getAgent(){
        return md5($_SERVER["HTTP_USER_AGENT"]);
    }

    private static function getToken($id){
        $data = $id.self::getAgent().self::getIp().time();
        return \ModuleHash::getPassHasher()->passHash($data);
    }

    public function createSession($id,$long=false){
        $time = $long ? $this->long_time : $this->time;
        $token = self::getToken($id);
        \ModuleDatabase::instance()->user_tokens->insert([
            "user_agent"=>self::getAgent(),
            "user_ip"=>self::getIp(),
            "user_id"=>$id,
            "token"=>$token,
            "expires"=>$time + time(),
            "created"=>time()
        ]);
        setcookie(self::COOKIE_KEY,$token,$time + time(),URLROOT);
    }

    public function validateSession():bool{
        if($this->session !== null) return !empty($this->session);
        if(empty($_COOKIE[self::COOKIE_KEY])) return false;
        $this->session = \ModuleDatabase::instance()
            ->user_tokens
            ->where("token",$_COOKIE[self::COOKIE_KEY])
            ->first();
        if(empty($this->session)) return false;
        if($this->session["user_ip"] != self::getIp()) return false;
        if($this->session["user_agent"] != self::getAgent()) return false;
        if(time() > $this->session["expires"]) return false;
        $over = $this->session["expires"] - ($this->session["expires"] - $this->session["created"]) / 2;
        if(time() > $over) $this->continueSession();
        return true;
    }

    public function getUserIdFromSession():int {
        if(empty($this->session)) throw new \Exception("ayayay");
        return (int)$this->session["user_id"];
    }

    private function _destroySession(bool $deep=false){
        if(!$deep){
            \ModuleDatabase::instance()->user_tokens->delete($this->session["id"]);
            \ModuleDatabase::instance()->user_tokens
                ->deleteWhere("user_id=? AND expires<?",[(int)$this->session["user_id"],time()]);
            setcookie(self::COOKIE_KEY,"",time() - 1,URLROOT);
        } else{
            \ModuleDatabase::instance()->user_tokens
                ->deleteWhere("user_id=?",[(int)$this->session["user_id"]]);
            setcookie(self::COOKIE_KEY,"",time() - 1,URLROOT);
        }
    }

    public function destroySession(bool $deep=false){
        if(!$this->validateSession()) return;
        $this->_destroySession($deep);
    }

    private function continueSession(){
        $id = (int)$this->session["id"];
        $time = time() - $this->session["created"];
        \ModuleDatabase::instance()->user_tokens->update($id,[
            "expires"=>$this->session["expires"] + time(),
            "created"=>time()
        ]);
        setcookie(self::COOKIE_KEY,$this->session["token"],$time + time(),URLROOT);
    }
}