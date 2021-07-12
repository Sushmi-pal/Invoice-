<?php
require_once 'controller.php';
require_once './model/company.php';
require_once './model/invoice.php';
require_once './model/database.php';


//$data=$d->datas();
class company extends controllers{
    public static function companylist(){
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

    public static function companycreate(){
        $c=new Create();
        $d=new Database();
        $conn=$d->connectme();
        $data=$d->datas();
        $result=$c->postcompany($conn, $data);
        if ($result){
            echo json_encode(array("Success"=>"Company created"));
        }

    }

    public static function validateemail(){
        $c=new Create();
        $d=new Database();
        $conn=$d->connectme();
        $data=$d->datas();
        try{
            $eee=$c->emailvalidation($conn, $data);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }




    }

    public static function companyupdate(){
        $c=new Create();
        $d=new Database();
        $conn=$d->connectme();
        $data=$d->datas();
        $c->updatecompany($conn, $data);
    }

    public static function companydelete(){
        $c=new Create();
        $d=new Database();
        $conn=$d->connectme();
        $data=$d->datas();
        $c->deletecompany($conn, $data);
    }

    public static function companytable(){
        $c=new Create();
        $d=new Database();
        $conn=$d->connectme();
        $c->table($conn);
    }

    public static function getcompany(){
        $c=new Create();
        $d=new Database();
        $conn=$d->connectme();
        $c->getcompany($conn);
    }


}
