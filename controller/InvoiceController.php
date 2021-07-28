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
     */
    public static function InvoiceUpdate()
    {
        $invoice = new Invoice();
        try {
            $invoice->UpdateInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Calls createinvoice()
     */
    public static function CreateInvoice()
    {
        $invoice = new Invoice();
        try {
            $invoice->CreateInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Deletes the invoice
     */
    public static function DeleteInvoice()
    {
        $invoice = new Invoice();
        try {
            $invoice->DeleteInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    /**
     * Pagination
     */
    public static function InvoicePages()
    {
        $invoice = new Invoice();
        try {
            $invoice->InvoicePages();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }

    /**
     * Searching
     */
    public static function SearchInvoice()
    {
        $invoice = new Invoice();
        try {
            $invoice->SearchInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

}