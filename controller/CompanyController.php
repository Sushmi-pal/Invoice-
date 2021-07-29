<?php
require_once 'Controller.php';
require_once './model/Company.php';
require_once './model/Invoice.php';
require_once './model/db.php';


class CompanyController extends Controller
{
    /**
     * $i instance of Invoice
     * catch Exception if cannot be executed in try block
     */
    public static function CompanyList()
    {
        /* Calling retrieveinvoice() method from Invoice.php */
        $i = new Invoice();
        try {
            $i->RetrieveInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }


    /**
     * @instance of Company $c
     * Executes if $result
     * Catch Exception if not executed in try block
     */
    public static function CompanyCreate()
    {
        $c = new Company();
        $result = $c->PostCompany();
        try {
            if ($result) {
                echo json_encode(array("Success" => "Company created"));
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }


    /**
     * @instance of Company $c
     * Catch Exception if not executed as desired
     */
    public static function ValidateEmail()
    {
        /* For backend validation to check whether the email already exists in the database table or not */
        $c = new Company();
        try {
            $c->EmailValidation();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }


    }

    /**
     * @instance $c
     * catch exception if not executed in try block
     */
    public static function CompanyUpdate()
    {
        /* For updating the details of company by calling updatecompany() method */
        $c = new Company();
        try {
            $c->UpdateCompany();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    public static function CompanyDelete()
    {
        /* For deleting the details of company by calling deletecompany() method */
        $c = new Company();
        try {
            $c->DeleteCompany();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    public static function CompanyTable()
    {
        /* Creating company table*/
        $c = new Company();
        try {
            $c->Table();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }


    }

    public static function GetCompany()
    {
        //Retrieve company details. It is fetched in dropdown menu in AddCompany.html page
        $c = new Company();
        try {
            $c->GetCompany();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }


}
