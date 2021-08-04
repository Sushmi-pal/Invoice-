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
        try {
            $invoice->UpdateInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    public static function InvoiceTable()
    {
        $invoice = new Invoice();
        try {
            $invoice->InvoiceTable() ;
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    public static function ItemTable(){
        $invoice = new Invoice();
        try {
            $invoice->ItemTable();
            return json_encode(array("Success"=>"Table 'item' created"));
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    public static function TotalTable(){
        $invoice = new Invoice();
        try {
            $invoice->TotalTable();
            return json_encode(array("Success"=>"Table 'total' created"));
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
        try {
            $invoice->CreateInvoice();
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
        try {
            $invoice->DeleteInvoice();
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
        try {
            $invoice->InvoicePages();
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
        try {
            $invoice->SearchInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

}