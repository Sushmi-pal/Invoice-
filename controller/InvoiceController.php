<?php
require_once 'Controller.php';
require_once './model/Invoice.php';


/**
 * Class InvoiceController
 * @instance $i
 * Catch exception if not executed in try block
 * @return Exception[]|string[]
 */
class InvoiceController extends Controller
{
    public static function InvoiceUpdate()
    {
        $i = new Invoice();
        try {
            $i->UpdateInvoice();
            return (array("Success" => "Invoice Updated"));
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Exception" => $e));
        }
    }

    /**
     * Creates an invoice
     * @return Exception[]|string[]
     */
    public static function CreateInvoice()
    {
        $i = new Invoice();
        try {
            $i->CreateInvoice();
            return (array("Success" => "Invoice Created Successfully"));
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Exception" => $e));
        }
    }

    /**
     * Deletes an invoice
     * @return Exception[]|string[]
     */
    public static function DeleteInvoice()
    {
        $i = new Invoice();
        try {
            $i->DeleteInvoice();
            return (array("Success" => "Invoice Deleted Successfully"));
        } catch (Exception $e) {
            Controller::ErrorLog($e);
            return (array("Exception" => $e));
        }
    }

    /**
     * Pagination
     * @return void
     */
    public static function InvoicePages()
    {
        $i = new Invoice();
        try {
            $i->InvoicePages();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }

    /**
     * Search invoice based on company name
     * @return void
     */
    public static function SearchInvoice()
    {
        $i = new Invoice();
        try {
            $i->SearchInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

}