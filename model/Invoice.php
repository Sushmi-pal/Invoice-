<?php
require_once 'db.php';


class Invoice
{
    private $company_id;
    private $itemarray;
    private $advance;
    private $total_cost;
    private $wdiscount;
    private $due;

    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Content-Type: application/json; charset=UTF-8");
        $d = new Database();
        $this->conn = $d->connectme();
        $this->data = $d->datas();
    }

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

        $this->sql = "insert into invoice(company_id) values ($this->company_id)";
        $result = $this->conn->exec($this->sql);
        $this->sql = "select id from invoice";
        $this->stmt = $this->conn->query($this->sql);
        $this->stmt->execute();
        $this->data = $this->stmt->fetchAll();
        $invoiceid = max($this->data)['id'];
        foreach ($this->itemarray as $v) {
            foreach ($v as $a) {
                $name = $a['name'];
                $cost = $a['cost'];
                $quantity = $a['quantity'];
                try {
                    if ($name != " " || cost != " " || $quantity != " ") {
                        $this->sql = "insert into itemrest(invoice_id, name, unit_cost, quantity) values ($invoiceid, '$name',$cost, $quantity)";
                        $r4 = $this->conn->exec($this->sql);
                    }

                } catch (Exception $e) {
                    echo $e->getMessage();
                }

            }

        }
        $this->sql = "insert into total(invoice_id, advance_payment, total_cost, wdiscount, due) values($invoiceid, $this->advance, $this->total_cost, $this->wdiscount, $this->due)";
        $rr = $this->conn->exec($this->sql);

        if ($result && $r4 && $rr) {
            echo json_encode(array("Success" => "Invoice Created", "invoice_id" => $invoiceid));
        } else {
            echo json_encode(array("Fail" => "Not created"));
        }
    }

    public function RetrieveInvoice()
    {
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");

        if (isset($_GET['id'])) {
            $this->iid = $_GET['id'];
            $this->sql = "Select itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company1.name, address, email, contact, city, total.advance_payment, total_cost, wdiscount, due from itemrest inner join invoice on itemrest.invoice_id=invoice.id inner join company1 on company1.id=invoice.company_id inner join total on total.invoice_id=invoice.id where invoice.id=$this->iid";
            $this->stmt = $this->conn->query($this->sql);
            $this->stmt->execute();
            $this->data = $this->stmt->fetchAll();
            $this->suser = array();
            $this->suser['data'] = array();

            foreach ($this->data as $this->k => $this->v) {
                $this->user_data = array(
                    'item_id' => $this->v['item_id'],
                    'company_name' => $this->v['name'],
                    'item_name' => $this->v[1],
                    'unit_cost' => $this->v['unit_cost'],
                    'quantity' => $this->v['quantity'],
                    'company_id' => $this->v['company_id'],
                    'created_at' => $this->v['created_at'],
                    'address' => $this->v['address'],
                    'email' => $this->v['email'],
                    'contact' => $this->v['contact'],
                    'city' => $this->v['city'],
                    'invoice_id' => $this->v[5],
                    'advance_payment' => $this->v['advance_payment'],
                    'total_cost' => $this->v['total_cost'],
                    'wdiscount' => $this->v['wdiscount'],
                    'due' => $this->v['due']
                );
//        Push to array
                array_push($this->suser['data'], $this->user_data);
            }
            echo json_encode($this->suser);
        } else {
            $this->sql = "Select itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company1.name, address, email, contact, city from itemrest inner join invoice on itemrest.invoice_id=invoice.id inner join company1 on company1.id=invoice.company_id";
            $this->stmt = $this->conn->query($this->sql);
            $this->stmt->execute();
            $this->data = $this->stmt->fetchAll();
            $this->suser = array();
            $this->suser['data'] = array();

            foreach ($this->data as $this->k => $this->v) {
                $this->user_data = array(
                    'item_id' => $this->v['item_id'],
                    'company_name' => $this->v['name'],
                    'item_name' => $this->v[1],
                    'unit_cost' => $this->v['unit_cost'],
                    'quantity' => $this->v['quantity'],
                    'company_id' => $this->v['company_id'],
                    'created_at' => $this->v['created_at'],
                    'address' => $this->v['address'],
                    'email' => $this->v['email'],
                    'contact' => $this->v['contact'],
                    'city' => $this->v['city'],
                    'invoice_id' => $this->v[5]
                );
//        Push to array
                array_push($this->suser['data'], $this->user_data);
            }
            echo json_encode($this->suser);
        }
    }

    public function DeleteInvoice()
    {
        header("Access-Control-Allow-Methods: DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->id = $this->data['id'];
        $this->sql = "delete from itemrest where invoice_id=$this->id";
        $this->result = $this->conn->exec($this->sql);
        $this->sql = "delete from invoice where id=$this->id";
        $this->result1 = $this->conn->exec($this->sql);
        $this->sql = "delete from total where invoice_id=$this->id";
        $this->result2 = $this->conn->exec($this->sql);
        if ($this->result && $this->result1 && $this->result2) {
            echo json_encode(array("Success" => "Records deleted successfully"));
        } else {
            echo json_encode(array("Fail" => "No such records found"));
        }

    }

    public function UpdateInvoice()
    {
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->invoice_id = $this->data['invoice_id'];
        $this->itemarray = $this->data['itemarray'];
        $this->advance = $this->data['advance'];
//        delete item
        $this->sql = "select itemrest.id from itemrest inner join invoice on itemrest.invoice_id=invoice.id where invoice.id=$this->invoice_id";
        $this->stmt = $this->conn->query($this->sql);
        $this->stmt->execute();
        $this->data = $this->stmt->fetchAll();
        $a1 = array_values($this->data);
        $a3 = [];
        foreach ($a1 as $a4) {
            array_push($a3, (int)$a4['id']);
        }

        $a2 = array();

        foreach ($this->itemarray as $v) {
            foreach ($v as $a) {
                array_push($a2, (int)$a['id']);
            }
        }
//        echo json_encode($a2); #febata pathako itemid
        $diff = array_diff($a3, $a2);
        $final = [];
        foreach ($diff as $a => $b) {
            array_push($final, $b);
        }
        for ($i = 0; $i < count($final); $i++) {
            $this->sql = "delete from itemrest where id=$final[$i]";
            $this->conn->exec($this->sql);
        }
//        update item
        foreach ($this->itemarray as $v) {
            foreach ($v as $a) {
                $invoice_id = $a['invoice_id'];
                $item_id = $a['id'];
                $name = $a['name'];
                $cost = $a['cost'];
                $quantity = $a['quantity'];
                if ($item_id != "") {
                    $this->sql = "update itemrest set name='$name', unit_cost=$cost, quantity=$quantity where id=$item_id";
                    $this->conn->exec($this->sql);
                } else {
                    $this->sql = "insert into itemrest(invoice_id, name, unit_cost, quantity) values($invoice_id, '$name', $cost, $quantity)";
                    $this->conn->exec($this->sql);
                }

            }

        }
        $this->sql = "update total set advance_payment=$this->advance where invoice_id=$this->invoice_id";
        $ret_ad = $this->conn->exec($this->sql);
        if ($ret_ad) {
            echo json_encode(array("Success" => "Invoice updated"));
        } else {
            echo json_encode(array("Fail" => "Update fail"));
        }
    }

    public function InvoicePages()
    {
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        if (isset($_GET['offset']) & isset($_GET['limit'])) {
            $uid = $_GET['offset'];
            $limit = $_GET['limit'];
            $sql = "Select invoice.id,company1.name, company1.id as cid from invoice inner join company1 on invoice.company_id=company1.id where invoice.id>=$uid limit $limit";
            $stmt = $this->conn->query($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            $suser = array();
            $suser['data'] = array();

            foreach ($data as $k => $v) {
                $user_data = array(
                    'id' => $v[0],
                    'cid' => $v['cid'],
                    'cname' => $v['name']

                );
//        Push to array
                array_push($suser['data'], $user_data);
            }
            echo json_encode($suser);
        } else {
            echo json_encode(["Wrong" => "Check the url"]);
        }
    }

    public function SearchInvoice()
    {
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        $kname = isset($_GET["cname"]) ? $_GET["cname"] : "";
        if ($kname) {

            $name = $_GET["cname"];
            $na = trim($name, ' ""');
            $nam = strtoupper($na);
            $sql = "Select invoice.id as iid, company1.id as cid, company1.name from invoice inner join company1 on invoice.company_id=company1.id where upper(company1.name) like '%$nam%'";
            $stmt = $this->conn->query($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            if (count($data) > 0) {
                $suser = array();
                $suser['data'] = array();

                foreach ($data as $k => $v) {
                    $user_data = array(
                        'iid' => $v['iid'],
                        'cid' => $v['cid'],
                        'name' => $v['name']
                    );
//        Push to array
                    array_push($suser['data'], $user_data);
                }
                echo json_encode($suser);
            } else {
                echo json_encode(["message" => "not found"]);
            }


        } else {
            echo json_encode(["message" => "cname unset"]);
        }
    }
//    public function __destruct(){
//        $this->conn->close();
//    }

}

$i = new Invoice();
