<?php
class Controller{
    public static function ErrorLog($e){
        ini_set("display_errors", 1);
        ini_set("log_errors", 1);
        ini_set("error_log", "./error_log.txt");
        trigger_error($e->getMessage(), E_USER_ERROR);
    }
}