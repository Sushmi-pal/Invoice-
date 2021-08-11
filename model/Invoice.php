<?php
require_once 'db.php';
require_once './DBQuery/PostQuery.php';
require_once './DBQuery/RetrieveQuery.php';
require_once './DBQuery/DeleteQuery.php';
require_once './DBQuery/UpdateQuery.php';
require_once './ServiceClass/InvoiceRequest.php';
require_once './ServiceClass/CompanyRequest.php';

/**
 * Class Invoice
 * @access private
 */
class Invoice
{
    private $company_id;
    private $itemarray;
    private $advance;
    private $total_cost;
    private $wdiscount;
    private $due;

    /**
     * Invoice constructor.
     */
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Content-Type: application/json; charset=UTF-8");
        $d = new Database();
        $this->conn = $d->connectme();
        $this->data = $d->datas();
        $this->retrieve=new RetrieveQuery();
        $this->update=new UpdateQuery();
        $this->delete=new DeleteQuery();
        $this->invoice_request=new InvoiceRequest();
        $this->company_request=new CompanyRequest();
        $this->post_invoice=new PostQuery();

    }



    /**
     * Store the invoice information
     */
    public function CreateInvoice()
    {
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->advance = $this->data['advance'];
        $this->company_id = $this->data['company_id'];
        $this->itemarray = $this->data['itemarray'];
        $this->total_cost = $this->data['total'];
        $this->wdiscount = $this->data['wdiscount'];
        $this->due = $this->data['due'];
        $result=$this->post_invoice->Insert('invoice', 'company_id', "$this->company_id");
        $this->data = $this->retrieve->Get("id", "invoice", "", "", "", "", "", "");
        $invoiceid = max($this->data)['id'];
        foreach ($this->itemarray as $v) {
            foreach ($v as $a) {
                $name = $a['name'];
                $cost = $a['cost'];
                $quantity = $a['quantity'];
                try {
                    if ($name != " " || cost != " " || $quantity != " ") {
                        $result_itemrest=$this->post_invoice->Insert('itemrest', 'invoice_id,name,unit_cost,quantity', "$invoiceid, '$name',$cost, $quantity");
                    }

                } catch (Exception $e) {
                    echo $e->getMessage();
                }

            }
        }
        $result_total = $this->post_invoice->Insert('total','invoice_id,advance_payment,total_cost,wdiscount,due', "$invoiceid, $this->advance, $this->total_cost, $this->wdiscount, $this->due");

        if ($result && $result_itemrest && $result_total) {
            echo json_encode(array("Success" => "Invoice Created", "invoice_id" => $invoiceid));
        } else {
            echo json_encode(array("Fail" => "Not created"));
        }
    }

    /**
     * Get all the invoices
     */
    public function RetrieveInvoice()
    {
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");

        if (isset($_GET['id'])) {
            $this->iid = $_GET['id'];
            $this->data=$this->retrieve->Get("itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company.name, address, email, contact, city, file_name, total.advance_payment, total_cost, wdiscount, due", "itemrest", ['invoice on itemrest.invoice_id=invoice.id', 'company on company.id=invoice.company_id', 'total on total.invoice_id=invoice.id'], "invoice.id=:invoice_id",$this->iid,"","","");
            $this->invoice_request->RetrieveInvoiceAll($this->data);
        } else {
            if (isset($_GET['sort'])){
                $sort=$_GET['sort'];
            }
            else{
                $sort='company.name';
            }
            $order = isset($_GET['order'])?$_GET['order']:'';
            $field = $this->company_request->GetCompanyElse($sort, $order)[0];
            $ordertype = $this->company_request->GetCompanyElse($sort, $order)[1];
            $this->data=$this->retrieve->Get("itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company.name, address, email, contact, city, file_name, total.advance_payment, total_cost, wdiscount, due", "itemrest", ['invoice on itemrest.invoice_id=invoice.id', 'company on company.id=invoice.company_id', 'total on total.invoice_id=invoice.id'], "","", "$ordertype", "$field","");
            $this->invoice_request->RetrieveInvoice($this->data);
        }
    }

    /**
     * Delete the invoice and all the columns related to invoice id
     */
    public function DeleteInvoice()
    {
        header("Access-Control-Allow-Methods: DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->id = $this->data['id'];
        $this->result_itemrest = $this->delete->Delete("itemrest", "invoice_id", "$this->id");
        $this->result_invoice = $this->delete->Delete("invoice", "id", "$this->id");
        $this->result_total = $this->delete->Delete("total", "invoice_id", "$this->id");
        $this->invoice_request->DeleteInvoice($this->result_itemrest, $this->result_invoice, $this->result_total);
    }

    /**
     * Update invoice
     */
    public function UpdateInvoice()
    {
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->invoice_id = $this->data['invoice_id'];
        $this->itemarray = $this->data['itemarray'];
        $this->advance = $this->data['advance'];
        $this->total_cost = $this->data['total_cost'];
        $this->wdiscount = $this->data['wdiscount'];
        $this->due = $this->data['due'];
//        delete item
        $this->data = $this->retrieve->Get("itemrest.id", "itemrest",["invoice on itemrest.invoice_id=invoice.id"],"invoice.id=:invoice_id", $this->data['invoice_id'],"","","");
        $final=$this->invoice_request->UpdateInvoice($this->data, $this->itemarray);
        for ($i = 0; $i < count($final); $i++) {
            $result  =$this->delete->Delete("itemrest", "id", "$final[$i]");

        }
//        update item
        foreach ($this->itemarray as $v) {
            foreach ($v as $a) {
                $invoice_id = $a['invoice_id'];
                $item_id = $a['id'];
                $name = $a['name'];
                $trimmed_name=ltrim($name);
                $cost = $a['cost'];
                $trim_name=substr($name, 1, strlen($name)-2);
                $quantity = $a['quantity'];
                if ($item_id != "") {
                    $this->sql = $this->update->Update("itemrest", "name='$trimmed_name', unit_cost=$cost, quantity=$quantity", "id=$item_id");
                    $this->conn->exec($this->sql);
                } else {
                    $result=$this->post_invoice->Insert('itemrest', 'invoice_id,name,unit_cost,quantity', "$invoice_id,$trim_name,$cost,$quantity");
                    echo $result;
                }
            }
        }
        $this->sql = $this->update->Update("total", "advance_payment=$this->advance, total_cost=$this->total_cost, wdiscount=$this->wdiscount, due=$this->due", "invoice_id=$this->invoice_id");
        $ret_ad = $this->conn->exec($this->sql);
        $result = $this->company_request->UDResult($ret_ad, "Invoice Updated");
        echo $result;
    }

    /**
     * For pagination
     *
     * Search on the basis of company name
     *
     */

    public function InvoicePages()
    {
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        $kname = isset($_GET["cname"]) ? $_GET["cname"] : "";
        if (isset($_GET['page'])) {
            if (isset($_GET['sort'])){
                $sort=$_GET['sort'];
            }
            else{
                $sort='name';
            }
            $order = $_GET['order'];
            $field = $this->company_request->GetCompanyElse($sort, $order)[0];
            $ordertype = $this->company_request->GetCompanyElse($sort, $order)[1];
            $name = $kname;
            $page=$_GET['page'];
            $data=$this->retrieve->Get("invoice.id", "invoice", ['company on invoice.company_id=company.id'], "","","","","");
            $off=array();
            foreach ($data as $k=>$v){
            array_push($off, $v['id']);
            }
            $uid= $off[($page-1)*5];
            if($kname === ""){
                $data=$this->retrieve->Get("invoice.id,company.name, company.id as cid", "invoice", ["company on invoice.company_id=company.id where invoice.id>=$uid"], "","","$ordertype","$field","5");
                $result=$this->invoice_request->InvoicePagesOne($data);
                echo $result;
            }
            else{
                $data=$this->retrieve->Get("invoice.id as iid,company.name, company.id as cid", "invoice", ["company on invoice.company_id=company.id where invoice.id>=$uid and upper(company.name)='$kname'"], "","","$ordertype","$field","5");
                $result=$this->invoice_request->InvoicePagesTwo($data);
                echo $result;
            }
        } else {
            if (isset($_GET['sort'])){
                $sort=$_GET['sort'];
            }
            else{
                $sort='name';
            }
            $order = $_GET['order'];
            $field = $this->company_request->GetCompanyElse($sort, $order)[0];
            $ordertype = $this->company_request->GetCompanyElse($sort, $order)[1];
            $data=$this->retrieve->Get("invoice.id as iid,company.name, company.id as cid", "invoice", ["company on invoice.company_id=company.id  where upper(company.name)='$kname'"], "","","$ordertype","$field","");
            $result=$this->invoice_request->InvoicePagesTwo($data);
            echo $result;
        }
    }




    public function __destruct()
    {
        $db = new Database();
        $db->closeme();
    }


}

$i = new Invoice();
