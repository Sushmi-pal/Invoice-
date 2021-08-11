<?php

require_once './FileService/FileService.php';
require_once './DBQuery/PostQuery.php';

class InvoiceRequest
{
    public function __construct()
    {
        $file = new FileService();
        $up = $file;
        $dbquery = new PostQuery();
    }

    public function PostInvoice($advance, $company_id, $itemarray, $total, $wdiscount, $due)
    {
        $sql = "insert into invoice(company_id) values (:company_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':company_id', $company_id);
        $result = $stmt->execute();
        $sql = "select id from invoice";
        $stmt = $conn->query($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $invoiceid = max($data)['id'];
        foreach ($itemarray as $v) {
            foreach ($v as $a) {
                $name = $a['name'];
                $cost = $a['cost'];
                $quantity = $a['quantity'];
                try {
                    if ($name != " " || cost != " " || $quantity != " ") {

                        $sql = "insert into itemrest(invoice_id, name, unit_cost, quantity) values ($invoiceid, '$name',$cost, $quantity)";

                        $result_itemrest = $conn->exec($sql);
                    }

                } catch (Exception $e) {
                    echo $e->getMessage();
                }

            }
        }
        $sql = "insert into total(invoice_id, advance_payment, total_cost, wdiscount, due) values($invoiceid, $advance, $total_cost, $wdiscount, $due)";
        $result_total = $conn->exec($sql);

        if ($result && $result_itemrest && $result_total) {
            echo json_encode(array("Success" => "Invoice Created", "invoice_id" => $invoiceid));
        } else {
            echo json_encode(array("Fail" => "Not created"));
        }
    }
    
    public function RetrieveInvoiceAll($data){
                    $suser = array();
            $suser['data'] = array();

            foreach ($data as $k => $v) {
                $user_data = array(
                    'item_id' => $v['item_id'],
                    'company_name' => $v['name'],
                    'item_name' => $v[1],
                    'unit_cost' => $v['unit_cost'],
                    'quantity' => $v['quantity'],
                    'company_id' => $v['company_id'],
                    'created_at' => $v['created_at'],
                    'address' => $v['address'],
                    'email' => $v['email'],
                    'contact' => $v['contact'],
                    'city' => $v['city'],
                    'file_name' => $v['file_name'],
                    'invoice_id' => $v[5],
                    'advance_payment' => $v['advance_payment'],
                    'total_cost' => $v['total_cost'],
                    'wdiscount' => $v['wdiscount'],
                    'due' => $v['due']
                );
//        Push to array
                array_push($suser['data'], $user_data);
            }
            echo json_encode($suser);
        }


        public function RetrieveInvoice($data){
            $suser = array();
            $suser['data'] = array();

            foreach ($data as $k => $v) {
                $user_data = array(
                    'item_id' => $v['item_id'],
                    'company_name' => $v['name'],
                    'item_name' => $v[1],
                    'unit_cost' => $v['unit_cost'],
                    'quantity' => $v['quantity'],
                    'company_id' => $v['company_id'],
                    'created_at' => $v['created_at'],
                    'address' => $v['address'],
                    'email' => $v['email'],
                    'contact' => $v['contact'],
                    'city' => $v['city'],
                    'file_name' => $v['file_name'],
                    'invoice_id' => $v[5]
                );
//        Push to array
                array_push($suser['data'], $user_data);
            }
            echo json_encode($suser);
        }


    /**
     * Delete the invoice and all the columns related to invoice id
     */
    public function DeleteInvoice($result_itemrest, $result_invoice, $result_total)
    {

        if ($result_itemrest && $result_invoice && $result_total) {
            echo json_encode(array("Success" => "Records deleted successfully"));
        } else {
            echo json_encode(array("Fail" => "No such records found"));
        }

    }

    public function UpdateInvoice($data, $itemarray){
        $data_array = array_values($data);
        $array_value = [];
        foreach ($data_array as $each_data) {
            array_push($array_value, (int)$each_data['id']);
        }

        $define_array = array();

        foreach ($itemarray as $v) {
            foreach ($v as $a) {
                array_push($define_array, (int)$a['id']);
            }
        }
        $diff = array_diff($array_value, $define_array);
        $final = [];
        foreach ($diff as $a => $b) {
            array_push($final, $b);
        }
        return $final;
    }

    public function InvoicePagesOne($data){
        $suser = array();
        $suser['data'] = array();

        foreach ($data as $k => $v) {
            $user_data = array(
                'id' => $v[0],
                'cid' => $v['cid'],
                'cname' => $v['name']

            );

            array_push($suser['data'], $user_data);
        }
        return json_encode($suser);
    }

    public function InvoicePagesTwo($data){
        $suser = array();
        $suser['data'] = array();

        foreach ($data as $k => $v) {
            $user_data = array(
                'iid'=>$v['iid'],
                'id' => $v[0],
                'cid' => $v['cid'],
                'cname' => $v['name']

            );

            array_push($suser['data'], $user_data);
        }
        return json_encode($suser);
    }
}