<?php
require_once 'Controller.php';
require_once './model/Invoice.php';


/**
 * Class InvoiceController
 * @instance $invoice
 * Catch exception if not executed in try block
 */
class InvoiceController extends Controller
{

    /**
     * Calls the updateinvoice() method
     * @return void
     */
    public static function InvoiceUpdate()
    {
        $invoice = new Invoice();

        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'invoice_edit');
            if ($permission_result === "true") {
                $invoice->UpdateInvoice();
            } else {
                return 'Page Not Found';
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Calls createinvoice
     * @return void
     */
    public static function CreateInvoice()
    {
        $invoice = new Invoice();
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'invoice_create');
            if ($permission_result === "true") {
                $invoice->CreateInvoice();
            } else {
                return 'Page Not Found';
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Deletes the invoice
     * @return void
     */
    public static function DeleteInvoice()
    {
        $invoice = new Invoice();
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'invoice_delete');
            if ($permission_result === "true") {
                $invoice->DeleteInvoice();
            } else {
                return 'Page Not Found';
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Pagination
     * @return void
     */
    public static function InvoicePages()
    {
        $invoice = new Invoice();
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'invoicepage_create');
            if ($permission_result === "true") {
                $invoice->InvoicePages();
            } else {
                return 'Page Not Found';
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }

    /**
     * Searching
     * @return void
     */
    public static function SearchInvoice()
    {
        $invoice = new Invoice();
        $permission = new Permission();
        try {
            $permission_result = $permission->CheckForPermission($_SESSION['name'], 'invoice_search');
            if ($permission_result === "true") {
                $invoice->SearchInvoice();
            } else {
                return 'Page Not Found';
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

}