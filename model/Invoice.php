<?php
require_once 'db.php';


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
    }

    public function InvoiceTable()
    {
        try {
            $sql = "drop table if exists invoice cascade";
            $this->conn->exec($sql);
            $sql = "CREATE TABLE Invoice (
            id serial unique,
            company_id int,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            primary key(id),
            foreign key (company_id) references Company(id) on delete cascade)";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

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
        $this->sql = "insert into invoice(company_id) values (:company_id)";
        $this->stmt = $this->conn->prepare($this->sql);
        $this->stmt->bindValue(':company_id', $this->company_id);
        $result = $this->stmt->execute();
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
                        $result_itemrest = $this->conn->exec($this->sql);
                    }

                } catch (Exception $e) {
                    echo $e->getMessage();
                }

            }
        }
        $this->sql = "insert into total(invoice_id, advance_payment, total_cost, wdiscount, due) values($invoiceid, $this->advance, $this->total_cost, $this->wdiscount, $this->due)";
        $result_total = $this->conn->exec($this->sql);

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
            $this->sql = "Select itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company.name, address, email, contact, city, file_name, total.advance_payment, total_cost, wdiscount, due from itemrest inner join invoice on itemrest.invoice_id=invoice.id inner join company on company.id=invoice.company_id inner join total on total.invoice_id=invoice.id where invoice.id=:invoice_id";
            $this->stmt = $this->conn->prepare($this->sql);
            $this->stmt->bindValue(':invoice_id', $this->iid);
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
                    'file_name' => $this->v['file_name'],
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
            if (isset($_GET['sort'])) {
                $field = $_GET['sort'];
            } else {
                $field = 'company.name';
            }
            if (isset($_GET['order'])) {
                $ordertype = ($_GET['order'] == 'desc') ? 'desc' : 'asc';
            } else {
                $ordertype = 'asc';
            }
            $this->sql = "Select itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company.name, address, email, contact, city, file_name from itemrest inner join invoice on itemrest.invoice_id=invoice.id inner join company on company.id=invoice.company_id order by $field $ordertype";
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
                    'file_name' => $this->v['file_name'],
                    'invoice_id' => $this->v[5]
                );
//        Push to array
                array_push($this->suser['data'], $this->user_data);
            }
            echo json_encode($this->suser);
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
        $this->sql = "delete from itemrest where invoice_id=$this->id";
        $this->result_itemrest = $this->conn->exec($this->sql);
        $this->sql = "delete from invoice where id=$this->id";
        $this->result_invoice = $this->conn->exec($this->sql);
        $this->sql = "delete from total where invoice_id=$this->id";
        $this->result_total = $this->conn->exec($this->sql);
        if ($this->result_itemrest && $this->result_invoice && $this->result_total) {
            echo json_encode(array("Success" => "Records deleted successfully"));
        } else {
            echo json_encode(array("Fail" => "No such records found"));
        }

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
        $this->sql = "select itemrest.id from itemrest inner join invoice on itemrest.invoice_id=invoice.id where invoice.id=$this->invoice_id";
        $this->stmt = $this->conn->query($this->sql);
        $this->stmt->execute();
        $this->data = $this->stmt->fetchAll();
        $data_array = array_values($this->data);
        $array_value = [];
        foreach ($data_array as $each_data) {
            array_push($array_value, (int)$each_data['id']);
        }

        $define_array = array();

        foreach ($this->itemarray as $v) {
            foreach ($v as $a) {
                array_push($define_array, (int)$a['id']);
            }
        }
//        echo json_encode($define_array); #febata pathako itemid
        $diff = array_diff($array_value, $define_array);
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
        $this->sql = "update total set advance_payment=$this->advance, total_cost=$this->total_cost, wdiscount=$this->wdiscount, due=$this->due where invoice_id=$this->invoice_id";
        $ret_ad = $this->conn->exec($this->sql);
        if ($ret_ad) {
            echo json_encode(array("Success" => "Invoice updated"));
        } else {
            echo json_encode(array("Fail" => "Update fail"));
        }
    }

    /**
     * For pagination
     */

    public function InvoicePages()
    {
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        if (isset($_GET['offset']) & isset($_GET['limit'])) {
            if (isset($_GET['sort'])) {
                $field = $_GET['sort'];
            } else {
                $field = 'company.name';
            }
            if (isset($_GET['order'])) {
                $ordertype = ($_GET['order'] == 'desc') ? 'desc' : 'asc';
            } else {
                $ordertype = 'asc';
            }

            $uid = $_GET['offset'];
            $limit = $_GET['limit'];
            $sql = "Select invoice.id,company.name, company.id as cid from invoice inner join company on invoice.company_id=company.id where invoice.id>=$uid  order by $field $ordertype limit $limit";
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
            if (isset($_GET['sort'])) {
                $field = $_GET['sort'];
            } else {
                $field = 'company.name';
            }
            if (isset($_GET['order'])) {
                $ordertype = ($_GET['order'] == 'desc') ? 'desc' : 'asc';
            } else {
                $ordertype = 'asc';
            }
            $sql = "Select invoice.id,company.name, company.id as cid from invoice inner join company on invoice.company_id=company.id  order by $field $ordertype";
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
        }
    }

    /**
     * Search on the basis of company name
     */

    public function SearchInvoice()
    {
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        $kname = isset($_GET["cname"]) ? $_GET["cname"] : "";
        if ($kname) {
            if (isset($_GET['sort'])) {
                $field = $_GET['sort'];
            } else {
                $field = 'company.name';
            }
            if (isset($_GET['order'])) {
                $ordertype = ($_GET['order'] == 'desc') ? 'desc' : 'asc';
            } else {
                $ordertype = 'asc';
            }
            $name = $_GET["cname"];
            $na = trim($name, ' ""');
            $nam = strtoupper($na);
            $sql = "Select invoice.id as iid, company.id as cid, company.name from invoice inner join company on invoice.company_id=company.id where upper(company.name) like '%$nam%' order by $field $ordertype";
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

    public function ItemTable()
    {
        try {
            $sql = "drop table if exists itemrest cascade ";
            $this->conn->exec($sql);
            $sql = "create table if not exists itemrest(
            id serial unique,
            invoice_id int,
            name varchar(255),
            unit_cost numeric (10,2),
            quantity int )";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }

    public function TotalTable()
    {

        try {
            $sql = "drop table if exists total cascade";
            $this->conn->exec($sql);
            $sql = "create table total(
            id serial unique ,
            invoice_id int,
            advance_payment numeric (10,2),
            total_cost numeric(10,2),
            wdiscount numeric (10,2),
            due numeric(10,2),
            foreign key (invoice_id) references invoice(id) on delete cascade );";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    public function __destruct()
    {
        $db = new Database();
        $db->closeme();
    }


}

$i = new Invoice();
