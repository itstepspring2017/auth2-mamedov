<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 11.04.2018
 * Time: 13:30
 */

class ControllerAuth extends Controller
{
    private static function is_empty()
    {
        foreach (func_get_args() as $arg) {
            if (empty($arg)) return true;
        }
        return false;
    }

    public function action_register()
    {

        $errors = [];
        try {
            $login = @$_POST["login"];
            $pass = @$_POST["pass"];
            $pass_c = @$_POST["conf"];
            $mail = @$_POST["mail"];
            if (self::is_empty($login, $pass, $pass_c, $mail)) throw new Exception("enter all fields");
            if ($pass_c !== $pass) throw new Exception("passwords are not similar");
            try {
                ModuleAuth::instance()->register($login, $pass, ["email" => $mail]);
                $this->redirect(URLROOT);
            } catch (Exception $e) {
                throw new Exception("This login is already used");
            }
        } catch (Exception $e) {
            $_SESSION["validate_error"] = $e->getMessage();
            $_SESSION["old"] = [
                "login" => @$_POST["login"],
                "mail" => @$_POST["mail"]
            ];
            $this->redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    public function action_login()
    {
        try {
            $login = @$_POST["login"];
            $pass = @$_POST["pass"];
            $remember = isset($_POST["remember"]);
            if (empty($login) || empty($pass)) throw new Exception("incorect data");
            ModuleAuth::instance()->login($login, $pass, $remember);
            $this->redirect(URLROOT);
        } catch (Exception $e) {
            $this->response($e->getMessage());
        }
    }

    public function action_logout()
    {
        ModuleAuth::instance()->logout();
        $this->redirect(URLROOT);
    }

    public function action_logoutAll()
    {
        ModuleAuth::instance()->logout(true);
        $this->redirect(URLROOT);
    }
}