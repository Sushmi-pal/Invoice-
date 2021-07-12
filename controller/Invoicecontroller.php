<?php
require_once 'controller.php';
require_once './model/invoice.php';
class invoicepage extends controllers{
    public static function invoicelist(){
        $i=new Invoice();
        $d=new Database();
        $conn=$d->connectme();
        try{
            $i->retrieveinvoice($conn);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }

    }

    public static function invoiceupdate(){
        $i=new Invoice();
        $d=new Database();
        $conn=$d->connectme();
        $data=$d->datas();
        try{
            $i->updateinvoice($conn, $data);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public static function createinvoice(){
        $i=new Invoice();
        $d=new Database();
        $conn=$d->connectme();
        $data=$d->datas();
        try{
            $i->create($conn, $data);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public static function deleteinvoice(){
        $i=new Invoice();
        $d=new Database();
        $conn=$d->connectme();
        $data=$d->datas();
        try{
            $i->deleteinvoice($conn, $data);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public static function invoicepages(){
        $i=new Invoice();
        $d=new Database();
        $conn=$d->connectme();
        try{
            $i->invoicepages($conn);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }

    }

    public static function searchinvoice(){
        $i=new Invoice();
        $d=new Database();
        $conn=$d->connectme();
        try{
            $i->searchinvoice($conn);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }

}