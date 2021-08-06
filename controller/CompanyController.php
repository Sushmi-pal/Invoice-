<?php
require_once 'Controller.php';
require_once './model/Company.php';
require_once './model/Invoice.php';
require_once './model/db.php';
require_once './PermissionClass/Permission.php';

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
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'company_show');
            if ($permission_result === "true") {
                $invoice->RetrieveInvoice();
                return (array("Success" => "Invoice data retrieved"));
            } else {
                return "Page not found";
            }

        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Fail" => $e));
        }

    }

    /**
     * @instance of Company $c
     * Executes if $result
     * Catch Exception if not executed in try block
     * @return Exception[]
     */
    public static function CompanyCreate()
    {
        $company = new Company();
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'company_create');
            if ($permission_result === "true") {
                $result = $company->PostCompany();
                if ($result) {
                    echo json_encode(array("Success" => "New Company created"));
                }
            } else {
                return "Page Not Found";
            }

        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Exception occured" => $e));
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
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'email_validate');
            if ($permission_result === "true") {
                $company->EmailValidation();
                return (array("Success" => "Email Validated Successfully"));
            } else {
                return "Page Not Found";
            }

        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Failed" => $e));
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
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'company_edit');
            if ($permission_result === "true") {
                $company->UpdateCompany();
                return (array("Success" => "Company Details Updated Successfully"));
            } else {
                return "Page Not Found";
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Failed" => $e));
        }
    }

    /**
     * Deletes the details of company by calling deletecompany() method
     * @return Exception[]|string[]
     */
    public static function CompanyDelete()
    {
        $company = new Company();
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'email_validate');
            if ($permission_result === "true") {
                $company->DeleteCompany();
                return (array("Success" => "Company Details Deleted Successfully"));
            } else {
                return "Page Not Found";
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return array("Failed" => $e);
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
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'company_show');
            if ($permission_result === "true") {
                $company->GetCompany();
            } else {
                return "Page Not Found";
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }
}
