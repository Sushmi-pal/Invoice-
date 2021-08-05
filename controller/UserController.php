<?php
require_once 'Controller.php';
require_once './model/User.php';
require_once './model/db.php';

class UserController extends Controller{
    /**
     * Creates user table
     * @return void
     */
    public static function UserTable()
    {
        $user = new User();
        try {
            $user->Table();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Creates user with role
     * @return false|string
     */
    public static function PostUser()
    {
        $user = new User();
        try {
            $user->PostUser();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Exception"=>$e));
        }
    }

    public static function LoginUser()
    {
        $user = new User();
        try {
            echo $user->LoginUser();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Exception"=>$e));
        }
    }
}