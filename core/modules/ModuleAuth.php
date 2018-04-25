<?php

include MODULES_PATH."ModuleAuth/UserSession.php";

use ModuleAuth\UserSession;

class ModuleAuth{
    private $hasher;
    private $db;
    private $session;
    private $user = null;
    private $is_auth = null;
    private $id = null;
    private static $inst = NULL;

    public static function instance(){
        return self::$inst === NULL
             ? self::$inst = new self()
             : self::$inst;
    }

    private function __construct(){
        $this->hasher = ModuleHash::getPassHasher();
        $this->db = ModuleDatabase::instance();
        $this->session = UserSession::instance();
    }

    public function register($login,$pass,$data=[]){
        if($this->db->users->countOf("login=?",[$login]) > 0) throw new Exception("user exist");
        $data["login"] = $login;
        $data["pass"] = $this->hasher->passHash($pass);
        $this->db->users->insert($data);
    }

    public function login($login,$pass,$save=false){
        $user = $this->db->users->where("login",$login)->first();
        if(!$user) throw new Exception("login invalid");
        if(!$this->hasher->comparePass($pass,$user["pass"])) throw new Exception("pass invalid");
        $this->session->createSession((int)$user["id"],$save);
    }

    public function logout($deep=false){
        $this->session->destroySession($deep);
    }

    public function isAuth(){
        if($this->is_auth === null){
            $this->is_auth = $this->session->validateSession();
        }
        return $this->is_auth;
    }

    public function getUser(){
        if($this->user === null){
            if(!$this->isAuth()) throw new Exception("no authentificate");
            $id = $this->session->getUserIdFromSession();
            $this->user = $this->db->users->get($id);
        }
        return $this->user;
    }

    public function getUserId(){
        if($this->id === null){
            if(!$this->isAuth()) throw new Exception("no authentificate");
            $this->id = $this->session->getUserIdFromSession();
        }
        return $this->id;
    }

    public function hasRole($role_name){
        if($this->user === null){
            if(!$this->isAuth()) return false;
            $id = $this->session->getUserIdFromSession();
            $this->user = $this->db->users->get($id);
        }
        $roles = $this->db->roles->join("user_roles","role_id")->where("user_id",(int)$this->user["id"])->all();
        foreach ($roles as $role){
            if($role["name"] == $role_name) return true;
        }
        return false;
    }
}