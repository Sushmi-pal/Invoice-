<?php
require_once 'controller.php';
require_once './model/company.php';
require_once './model/invoice.php';
require_once './model/database.php';


function errorlog($e)
{
    ini_set("display_errors", 1);
    ini_set("log_errors", 1);
    ini_set("error_log", "./error_log.txt");
    trigger_error($e->getMessage(), E_USER_ERROR);
}

//$data=$d->datas();
class company extends controllers
{
    public static function companylist()
    {
        /* Calling retrieveinvoice() method from invoice.php */
        $i = new Invoice();
        $d = new Database();
        $conn = $d->connectme();
        try {
            $i->retrieveinvoice($conn);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }


    public static function companycreate()
    {
        $c = new Create();
        $result = $c->postcompany();
        try {
            if ($result) {
                echo json_encode(array("Success" => "Company created"));
            }
        } catch (Exception $e) {
            ini_set("display_errors", 1);
            ini_set("log_errors", 1);
            ini_set("error_log", "./error_log.txt");
            trigger_error($e->getMessage(), E_WARNING);
        }

    }


    public static function validateemail()
    {
        /* For backend validation to check whether the email already exists in the database table or not */
        $c = new Create();
        try {
            $c->emailvalidation();
        } catch (Exception $e) {
            errorlog($e);
        }


    }

    public static function companyupdate()
    {
        /* For updating the details of company by calling updatecompany() method */
        $c = new Create();
        try {
            $c->updatecompany();
        } catch (Exception $e) {
            errorlog($e);
        }
    }

    public static function companydelete()
    {
        /* For deleting the details of company by calling deletecompany() method */
        $c = new Create();
        try {
            $c->deletecompany();
        } catch (Exception $e) {
            errorlog($e);
        }
    }

    public static function companytable()
    {
        /* Creating company table*/
        $c = new Create();
        try {
            $c->table();
        } catch (Exception $e) {
            errorlog($e);
        }


    }

    public static function getcompany()
    {
        //Retrieve company details. It is fetched in dropdown menu in addcompany.html page
        $c = new Create();
        try {
            $c->getcompany();
        } catch (Exception $e) {
            errorlog($e);
        }
    }


}
