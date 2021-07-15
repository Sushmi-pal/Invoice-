<?php
require_once 'controller.php';
require_once './model/invoice.php';


class invoicepage extends controllers
{
    public static function invoicelist()
    {
        $i = new Invoice();

        try {
            $i->retrieveinvoice();
        } catch (Exception $e) {
            errorlog($e);
        }

    }

    public static function invoiceupdate()
    {
        /* Calls the updateinvoice() method of invoice.php which is in the model directory*/
        $i = new Invoice();
        try {
            $i->updateinvoice();
        } catch (Exception $e) {
            errorlog($e);
        }
    }

    public static function createinvoice()
    {
        /* Calls the create() method from model/invoice.php */
        $i = new Invoice();
        try {
            $i->create();
        } catch (Exception $e) {
            errorlog($e);
        }
    }

    public static function deleteinvoice()
    {
        /* Calls deleteinvoice() which is in model/invoice.php */
        $i = new Invoice();
        try {
            $i->deleteinvoice();
        } catch (Exception $e) {
            errorlog($e);
        }
    }

    public static function invoicepages()
    {
        /* For pagination. Calls the invoicepages() method present in model/invoice.php */
        $i = new Invoice();
        try {
            $i->invoicepages();
        } catch (Exception $e) {
            errorlog($e);
        }

    }

    public static function searchinvoice()
    {
        /* For searching. Calls the searchinvoice() method */
        $i = new Invoice();
        try {
            $i->searchinvoice();
        } catch (Exception $e) {
            errorlog($e);
        }
    }

}