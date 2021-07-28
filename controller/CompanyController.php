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
        $invoice = new Invoice();
        try {
            $invoice->RetrieveInvoice();
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
        $company = new Company();
        $result = $company->PostCompany();
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
        $company = new Company();
        try {
            $company->EmailValidation();
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
        $company = new Company();
        try {
            $company->UpdateCompany();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Deletes the details of company by calling deletecompany() method
     */
    public static function CompanyDelete()
    {
        $company = new Company();
        try {
            $company->DeleteCompany();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Creates company table
     */
    public static function CompanyTable()
    {
        $company = new Company();
        try {
            $company->Table();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }


    /**
     * Retrieves company details. It is fetched in dropdown menu in AddCompany.html page
     */
    public static function GetCompany()
    {
        $company = new Company();
        try {
            $company->GetCompany();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }
}
