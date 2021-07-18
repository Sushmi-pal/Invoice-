<?php
require_once 'Controller.php';
require_once './model/Invoice.php';


/**
 * Class InvoiceController
 * @instance $i
 * Catch exception if not executed in try block
 */
class InvoiceController extends Controller
{
    public static function InvoiceUpdate()
    {
        /* Calls the updateinvoice() method of Invoice.php which is in the model directory*/
        $i = new Invoice();
        try {
            $i->UpdateInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    public static function CreateInvoice()
    {
        /* Calls the create() method from model/Invoice.php */
        $i = new Invoice();
        try {
            $i->CreateInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    public static function DeleteInvoice()
    {
        /* Calls deleteinvoice() which is in model/Invoice.php */
        $i = new Invoice();
        try {
            $i->DeleteInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

    public static function InvoicePages()
    {
        /* For pagination. Calls the invoicepages() method present in model/Invoice.php */
        $i = new Invoice();
        try {
            $i->InvoicePages();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }

    public static function SearchInvoice()
    {
        /* For searching. Calls the searchinvoice() method */
        $i = new Invoice();
        try {
            $i->SearchInvoice();
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
    }

}