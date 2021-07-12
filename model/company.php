<?php
require_once 'database.php';
$d=new Database();
$conn=$d->connectme();
$data=$d->datas();

class Create{
    private $id;
    private $name;
    private $address;
    private $email;
    private $contact;
    private $city;

    public function table($conn){

        try{
            $sql="drop table if exists Company1 cascade";
            $conn->exec($sql);
            $sql="CREATE TABLE Company1(
	id serial unique,
	name varchar(255),
	address varchar(255),
	email varchar(50),
	contact varchar(20),
	city varchar(255));";
            $conn->exec($sql);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public function postcompany($conn,$data){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->name=$data['name'];
        $this->address=$data['address'];
        $this->email=$data['email'];
        $this->contact=$data['contact'];
        $this->city=$data['city'];
        $sql = "insert into company1(name, address, email, contact, city) values('$this->name', '$this->address','$this->email','$this->contact','$this->city')";
        $result = $conn->exec($sql);
        if ($result){
            echo json_encode(array("Success"=>"Created"));
        }
        else{
            echo json_encode(array("Fail"=>"Not"));
        }
    }
    public function deletecompany($conn,$data){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: DELETE");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $aa=[];
        $id=$data['id'];
        $sql="select invoice.id from invoice inner join company1 on invoice.company_id=company1.id where company1.id=$id";
        $stmt = $conn->query($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        foreach ($data as $k=>$v){
            $aa= $v['id'];
        }
        if ($aa){
            $sql="delete from total where invoice_id=$aa";
            $this->result2=$conn->exec($sql);
            $sql="delete from itemrest where invoice_id = $aa";
            $this->result3=$conn->exec($sql);
        }


        $sql="delete from company1 where id=$id";
        $this->result4=$conn->exec($sql);





        if ($this->result4){
            echo json_encode(array("Success"=>"Deleted successfully"));
        }
        else{
            echo json_encode(array("Fail"=>"fail"));
        }

    }

    public function updatecompany($conn, $data){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: PUT");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->id=$data['id'];
        $this->name=$data['name'];
        $this->address=$data['address'];
        $this->email=$data['email'];
        $this->contact=$data['contact'];
        $this->city=$data['city'];
        $this->sql = "update company1 set name='{$this->name}', address='{$this->address}', email='{$this->email}', contact='{$this->contact}', city='{$this->city}' where id=$this->id";
        $this->result=$conn->exec($this->sql);
        if ($this->result){
            echo json_encode(array("Success"=>"Updated successfully"));
        }
        else{
            echo json_encode(array("Fail"=>"Update fail"));
        }
    }

    public function emailvalidation($conn, $data){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: POST");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->email = $data['email'];
        $this->sql = "select * from company1 where email='$this->email'";
        $this->stmt = $conn->query($this->sql);
        $this->stmt->execute();
        $this->data1 = $this->stmt->fetchAll();
        if (count($this->data1)>0){
            echo json_encode(array("Message"=>"Email address already exists"));
        }
        else{
            echo json_encode(array("Message"=>""));
        }
    }

    public function getcompany($conn){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json; charset=UTF-8");
        if (isset($_GET['id'])) {
            $this->cid = $_GET['id'];
            $this->sql = "select * from company1 where id=$this->cid";
            $this->stmt = $conn->query($this->sql);
            $this->stmt->execute();
            $this->data = $this->stmt->fetchAll();
            $this->suser = array();
            $this->suser['data'] = array();

            foreach ($this->data as $this->k => $this->v) {
                $this->user_data = array(
                    'id' => $this->v['id'],
                    'name' => $this->v['name'],
                    'email' => $this->v['email'],
                    'address' => $this->v['address'],
                    'contact' => $this->v['contact'],
                    'city' => $this->v['city']
                );
//        Push to array
                array_push($this->suser['data'], $this->user_data);
            }
            echo json_encode($this->suser);
        }
        else{
            $this->sql="select * from company1";
            $this->stmt=$conn->query($this->sql);
            $this->stmt->execute();
            $this->data=$this->stmt->fetchAll();
            if (count($this->data)>0){
                $this->users_arr=array();
                $this->users_arr['data']=array();
                $rowCount=count($this->data);
                foreach ($this->data as $this->k=>$this->v){



                    $this->user_data=array(
                        'id' => $this->v['id'],
                        'name' => $this->v['name'],
                        'email' => $this->v['email'],
                        'address' => $this->v['address'],
                        'contact' => $this->v['contact'],
                        'city' => $this->v['city']
                    );
//        Push to array
                    array_push($this->users_arr['data'],$this->user_data);
                }
                echo json_encode($this->users_arr);

            }
        }
    }

}

$c=new Create();
//$c->table($conn);
//$c->postcompany($conn, $data);
//$c->deletecompany($conn, $data);
//$c->updatecompany($conn, $data);
//$c->emailvalidation($conn, $data);