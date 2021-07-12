<?php
require_once 'database.php';
$d=new Database();
$conn=$d->connectme();
$data=$d->datas();

class Invoice{
    private $company_id;
    private $itemarray;
    private $advance;
    private $total_cost;
    private $wdiscount;
    private $due;

    public function create($conn, $data){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->advance=$data['advance'];
        $this->company_id=$data['company_id'];
        $this->itemarray=$data['itemarray'];
        $this->total_cost=$data['total'];
        $this->wdiscount=$data['wdiscount'];
        $this->due=$data['due'];

        $this->sql="insert into invoice(company_id) values ($this->company_id)";
        $result=$conn->exec($this->sql);
        $this->sql="select id from invoice";
        $this->stmt=$conn->query($this->sql);
        $this->stmt->execute();
        $this->data=$this->stmt->fetchAll();
        $invoiceid=max($this->data)['id'];
        foreach ($this->itemarray as $v){
            foreach ($v as $a){
                $name= $a['name'];
                $cost= $a['cost'];
                $quantity=$a['quantity'];
                try{
                    if ($name !=" " || cost !=" " || $quantity!=" "){
                        $this->sql="insert into itemrest(invoice_id, name, unit_cost, quantity) values ($invoiceid, '$name',$cost, $quantity)";
                        $r4=$conn->exec($this->sql);
                    }

                }
                catch (Exception $e){
                    echo $e->getMessage();
                }

            }

        }
        $this->sql="insert into total(invoice_id, advance_payment, total_cost, wdiscount, due) values($invoiceid, $this->advance, $this->total_cost, $this->wdiscount, $this->due)";
        $rr=$conn->exec($this->sql);

        if ($result && $r4 && $rr){
            echo json_encode(array("Success"=>"Invoice Created", "invoice_id"=>$invoiceid));
        }
        else{
            echo json_encode(array("Fail"=>"Not created"));
        }
    }

    public function retrieveinvoice($conn){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json; charset=UTF-8");

        if (isset($_GET['id'])) {
            $this->iid = $_GET['id'];
//            $this->sql = "Select itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company1.name, address, email, contact, city, total.advance_payment, total.total_cost, total.wdiscount, total.due from itemrest inner join invoice on itemrest.invoice_id=invoice.id inner join company1 on company1.id=invoice.company_id inner join total on total.invoice_id=invoice.id where invoice.id=$this->iid";
            $this->sql="Select itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company1.name, address, email, contact, city, total.advance_payment, total_cost, wdiscount, due from itemrest inner join invoice on itemrest.invoice_id=invoice.id inner join company1 on company1.id=invoice.company_id inner join total on total.invoice_id=invoice.id where invoice.id=$this->iid";
            $this->stmt = $conn->query($this->sql);
            $this->stmt->execute();
            $this->data = $this->stmt->fetchAll();
            $this->suser = array();
            $this->suser['data'] = array();

            foreach ($this->data as $this->k => $this->v) {
                $this->user_data = array(
                    'item_id' => $this->v['item_id'],
                    'company_name' => $this->v['name'],
                    'item_name'=>$this->v[1],
                    'unit_cost' => $this->v['unit_cost'],
                    'quantity' => $this->v['quantity'],
                    'company_id' => $this->v['company_id'],
                    'created_at' => $this->v['created_at'],
                    'address' => $this->v['address'],
                    'email'=>$this->v['email'],
                    'contact'=>$this->v['contact'],
                    'city'=>$this->v['city'],
                    'invoice_id'=>$this->v[5],
                    'advance_payment'=>$this->v['advance_payment'],
                    'total_cost'=>$this->v['total_cost'],
                    'wdiscount'=>$this->v['wdiscount'],
                    'due'=>$this->v['due']
                );
//        Push to array
                array_push($this->suser['data'], $this->user_data);
            }
            echo json_encode($this->suser);
        }

        else{
            $this->sql = "Select itemrest.id as item_id, itemrest.name, unit_cost, quantity, company_id, invoice.id, invoice.created_at, company1.name, address, email, contact, city from itemrest inner join invoice on itemrest.invoice_id=invoice.id inner join company1 on company1.id=invoice.company_id";
            $this->stmt = $conn->query($this->sql);
            $this->stmt->execute();
            $this->data = $this->stmt->fetchAll();
            $this->suser = array();
            $this->suser['data'] = array();

            foreach ($this->data as $this->k => $this->v) {
                $this->user_data = array(
                    'item_id' => $this->v['item_id'],
                    'company_name' => $this->v['name'],
                    'item_name'=>$this->v[1],
                    'unit_cost' => $this->v['unit_cost'],
                    'quantity' => $this->v['quantity'],
                    'company_id' => $this->v['company_id'],
                    'created_at' => $this->v['created_at'],
                    'address' => $this->v['address'],
                    'email'=>$this->v['email'],
                    'contact'=>$this->v['contact'],
                    'city'=>$this->v['city'],
                    'invoice_id'=>$this->v[5]
                );
//        Push to array
                array_push($this->suser['data'], $this->user_data);
            }
            echo json_encode($this->suser);
        }
}

    public function deleteinvoice($conn, $data){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: DELETE");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->id=$data['id'];
        $this->sql="delete from itemrest where invoice_id=$this->id";
        $this->result=$conn->exec($this->sql);
        $this->sql="delete from invoice where id=$this->id";
        $this->result1=$conn->exec($this->sql);
        $this->sql="delete from total where invoice_id=$this->id";
        $this->result2=$conn->exec($this->sql);
        if ($this->result && $this->result1 && $this->result2){
            echo json_encode(array("Success"=>"Records deleted successfully"));
        }
        else{
            echo json_encode(array("Fail"=>"No such records found"));
        }

    }

    public function updateinvoice($conn, $data){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: PUT");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        $this->invoice_id=$data['invoice_id'];
        $this->itemarray=$data['itemarray'];
        $this->advance=$data['advance'];
//        delete item
        $this->sql="select itemrest.id from itemrest inner join invoice on itemrest.invoice_id=invoice.id where invoice.id=$this->invoice_id";
        $this->stmt = $conn->query($this->sql);
        $this->stmt->execute();
        $this->data = $this->stmt->fetchAll();
        $a1=array_values($this->data);
        $a3=[];
        foreach ($a1 as $a4){
            array_push($a3,(int)$a4['id']);
        }
//        echo json_encode($a3); #dbma bhako itemid
//        echo json_encode(array("s"=>$a1));
        $a2=array();

        foreach ($this->itemarray as $v){
            foreach ($v as $a){
                array_push($a2,(int)$a['id']);
            }
        }
//        echo json_encode($a2); #febata pathako itemid
        $diff=array_diff($a3,$a2);
        $final=[];
        foreach ($diff as $a=>$b){
            array_push($final,$b);
        }
        for ($i=0; $i<count($final); $i++){
            $this->sql="delete from itemrest where id=$final[$i]";
            $conn->exec($this->sql);
        }
//        update item
        foreach ($this->itemarray as $v){
            foreach ($v as $a){
                $invoice_id=$a['invoice_id'];
                $item_id=$a['id'];
                $name= $a['name'];
                $cost= $a['cost'];
                $quantity=$a['quantity'];
                if ($item_id!=""){
                    $this->sql="update itemrest set name='$name', unit_cost=$cost, quantity=$quantity where id=$item_id";
                    $conn->exec($this->sql);
                }
                else{
                    $this->sql="insert into itemrest(invoice_id, name, unit_cost, quantity) values($invoice_id, '$name', $cost, $quantity)";
                    $conn->exec($this->sql);
                }

            }

        }
        $this->sql="update total set advance_payment=$this->advance where invoice_id=$this->invoice_id";
        $ret_ad=$conn->exec($this->sql);
        if ($ret_ad){
            echo json_encode(array("Success"=>"Invoice updated"));
        }
        else{
            echo json_encode(array("Fail"=>"Update fail"));
        }
    }

    public function invoicepages($conn){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json; charset=UTF-8");
        if (isset($_GET['offset']) & isset($_GET['limit'])) {
            $uid = $_GET['offset'];
            $limit=$_GET['limit'];
            $sql = "Select invoice.id,company1.name, company1.id as cid from invoice inner join company1 on invoice.company_id=company1.id where invoice.id>=$uid limit $limit";
            $stmt = $conn->query($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            $suser = array();
            $suser['data'] = array();

            foreach ($data as $k => $v) {
                $user_data = array(
                    'id' => $v[0],
                    'cid'=>$v['cid'],
                    'cname'=>$v['name']

                );
//        Push to array
                array_push($suser['data'], $user_data);
            }
            echo json_encode($suser);
        }
        else{
            echo json_encode(["Wrong"=>"Check the url"]);
        }
    }

    public function searchinvoice($conn){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json; charset=UTF-8");
        $kname=isset($_GET["cname"]) ? $_GET["cname"] : "";
        if ($kname) {
//            $address = $_GET["address"];
            $name=$_GET["cname"];
//            $aa = trim($address, ' ""');
            $na = trim($name, ' ""');
            $nam=strtoupper($na);
//            $capitalAddress=strtoupper($aa);


            $sql = "Select invoice.id as iid, company1.id as cid, company1.name from invoice inner join company1 on invoice.company_id=company1.id where upper(company1.name) like '%$nam%'";
            $stmt = $conn->query($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            if (count($data) > 0) {
                $suser = array();
                $suser['data'] = array();

                foreach ($data as $k => $v) {
                    $user_data = array(
                        'iid'=>$v['iid'],
                        'cid'=>$v['cid'],
                        'name'=>$v['name']
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

}

$i=new Invoice();
//$i->create($conn, $data);
//$i->retrieveinvoice($conn);
//$i->deleteinvoice($conn, $data);