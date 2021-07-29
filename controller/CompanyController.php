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
     * @return Exception[]|string[]
     */
    public static function CompanyList()
    {
        $invoice = new Invoice();
        try {
            $invoice->RetrieveInvoice();
            return (array("Success"=>"Invoice data retrieved"));
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Fail"=>$e));
        }

    }

    /**
     * @instance of Company $c
     * Executes if $result
     * Catch Exception if not executed in try block
     * @return Exception[]|string[]
     */
    public static function CompanyCreate()
    {
        $company = new Company();
        $result = $company->PostCompany();
        try {
            if ($result) {
                return (array("Success" => "New Company created"));
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Exception occured"=>$e));
        }

    }


    /**
     * @instance of Company $c
     * Catch Exception if not executed as desired
     * @return Exception[]|string[]
     */
    public static function ValidateEmail()
    {
        $company = new Company();
        try {
            $company->EmailValidation();
            return (array("Success"=>"Email Validated Successfully"));
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Failed"=>$e));
        }
    }

    /**
     * @instance $c
     * catch exception if not executed in try block
     * @return Exception[]|string[]
     */
    public static function CompanyUpdate()
    {
        $company = new Company();
        try {
            $company->UpdateCompany();
            return (array("Success"=>"Company Details Updated Successfully"));
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Failed"=>$e));
        }
    }

    /**
     * Deletes the details of company by calling deletecompany() method
     * @return Exception[]|string[]
     */
    public static function CompanyDelete()
    {
        $company = new Company();
        try {
            $company->DeleteCompany();
            return (array("Success"=>"Company Details Deleted Successfully"));
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return array("Failed"=>$e);
        }
    }

    /**
     * Creates company table
     * @return void
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
     * @return void
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
