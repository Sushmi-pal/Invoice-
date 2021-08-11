<?php
require_once './model/db.php';
//$column_name, $table_name, $join, $where

class RetrieveQuery{
    public function Get( $column_name, $table_name, $join, $where, $check, $sort, $order, $limit){
        $database = new Database();
        $conn = $database->ConnectMe();
//        $column_name="itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company.name, address, email, contact, city, file_name, total.advance_payment, total_cost, wdiscount, due";
//        $table_name="itemrest";
//        $order='name';
        $orderby='';
//        $join=['invoice on itemrest.invoice_id=invoice.id', 'invoice on itemrest.invoice_id=invoice.id', 'company on company.id=invoice.company_id'];
//        $join=['invoice on itemrest.invoice_id=invoice.id', 'company on company.id=invoice.company_id', 'total on total.invoice_id=invoice.id'];
//        $where='a=b';
//        $where='invoice.id=:invoice_id';
        $inner_join='';
//        $sort='asc';
        $sortby='';
        if ($join!=''){
            for ($inc=0; $inc<count($join); $inc++){
                $inner_join=$inner_join."inner join ".$join[$inc].' ';
            }
        }
        if ($where){
            $where_clause="where $where";
        }
        else{
            $where_clause='';
        }
        if ($order){
            $orderby="order by ".$order;
        }
        if ($sort){
            $sortby="$sort";
        }
        $limit_5='';
        if ($limit){
            $limit_5="limit 5";
        }
        if ($where_clause){
            $sql="select $column_name from $table_name $inner_join $where_clause $orderby $sortby $limit_5";
            $stmt = $conn->prepare($sql);
            $value=explode("=",$where);

            $stmt->bindValue("$value[1]", $check);
            $stmt->execute();
            $data = $stmt->fetchAll();
        }
        else{
            $sql="select $column_name from $table_name $inner_join $where_clause $orderby $sortby $limit_5";
            $stmt = $conn->query($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();

        }
        return $data;

//        echo $a;
//        echo $where[1];
    }
}

$r=new RetrieveQuery();
$r->Get("itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company.name, address, email, contact, city, file_name, total.advance_payment, total_cost, wdiscount, due", "itemrest", ['invoice on itemrest.invoice_id=invoice.id', 'company on company.id=invoice.company_id', 'total on total.invoice_id=invoice.id'], 'invoice.id=:invoice_id', 0, "", "","");
//$r->Get("invoice.id,company.name, company.id as cid", "invoice", ['company on invoice.company_id=company.id where invoice.id>=$uid'], "","$ordertype","$field","");
