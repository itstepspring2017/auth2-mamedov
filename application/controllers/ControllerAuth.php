<?php

class ControllerAuth extends Controller{

    private static function is_empty(){
        foreach (func_get_args() as $arg){
            if (empty($arg)) return true;
        }
        return false;
    }

    public function action_register(){
        try{
            $login = @$_POST["login"];
            $pass = @$_POST["pass"];
            $pass2 = @$_POST["pass2"];
            $email = @$_POST["email"];
            if(self::is_empty($login,$pass,$pass2,$email)){
                throw new Exception("fields is empty");
            }
            if($pass !== $pass2){
                throw new Exception("passwords not the same");
            }
            try{
                ModuleAuth::instance()->register($login,$pass,[
                    "email"=>$email
                ]);
                $this->redirect(URLROOT);
            }catch (Exception $e){
                throw new Exception("name is zanyato");
            }
        } catch (Exception $e){
            $_SESSION["validate_error"] = $e->getMessage();
            $_SESSION["old"] = [
                "login"=>@$_POST["login"],
                "email"=>@$_POST["email"]
            ];
            $this->redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    public function action_login(){
        try{
            $long = isset($_POST["long"]);
            $login = @$_POST["login"];
            $pass = @$_POST["pass"];
            $request = @$_POST["request"];
            if(empty($login) || empty($pass)) throw new Exception("data invalid");
            ModuleAuth::instance()->login($login,$pass,$long);
            if(empty($request)){
                $this->redirect(URLROOT);
            }else{
                $this->redirect($request);
            }

        } catch (Exception $e){
            $this->response($e->getMessage());
        }
    }

    public function action_logout(){
        ModuleAuth::instance()->logout();
        $this->redirect(URLROOT);
    }

    public function action_logoutall(){
        ModuleAuth::instance()->logout(true);
        $this->redirect(URLROOT);
    }
}