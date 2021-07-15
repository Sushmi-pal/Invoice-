<?php
require_once 'database.php';
$d = new Database();
$conn = $d->connectme();
$data = $d->datas();

/**
 * Class Create
 */
class Create
{
    private $id;
    private $name;
    private $address;
    private $email;
    private $contact;
    private $city;

    public function __construct()
    {
        $d = new Database();
        $this->conn = $d->connectme();
        $this->data = $d->datas();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Content-Type: application/json; charset=UTF-8");

    }


    public function table()
    {
        //Creating company table
        try {
            $sql = "drop table if exists Company1 cascade";
            $this->conn->exec($sql);
            $sql = "CREATE TABLE Company1(
	id serial unique,
	name varchar(255),
	address varchar(255),
	email varchar(50),
	contact varchar(20),
	city varchar(255));";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * @return array
     */
    public function postcompany()
    {
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->name = $this->data['name'];
        $this->address = $this->data['address'];
        $this->email = $this->data['email'];
        $this->contact = $this->data['contact'];
        $this->city = $this->data['city'];
        try {
            if ($this->name && $this->address && $this->email && $this->contact && $this->city) {
                $sql = "insert into company1(name, address, email, contact, city) values('$this->name', '$this->address','$this->email','$this->contact','$this->city')";
                $result = $this->conn->exec($sql);
                return (array) $result;


            } else {
                throw new Exception('text');
            }
        } catch (Exception $e) {
            ini_set("display_errors", 1);
            ini_set("log_errors", 1);
            ini_set("error_log", "./error_log.txt");
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

    }



    public function deletecompany()
    {
        header("Access-Control-Allow-Methods: DELETE");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $aa = [];
        $id = $this->data['id'];
        $sql = "select invoice.id from invoice inner join company1 on invoice.company_id=company1.id where company1.id=$id";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        foreach ($data as $k => $v) {
            $aa = $v['id'];
        }
        if ($aa) {
            $sql = "delete from total where invoice_id=$aa";
            $this->result2 = $this->conn->exec($sql);
            $sql = "delete from itemrest where invoice_id = $aa";
            $this->result3 = $this->conn->exec($sql);
        }
        $sql = "delete from company1 where id=$id";
        $this->result4 = $this->conn->exec($sql);
        if ($this->result4) {
            echo json_encode(array("Success" => "Deleted successfully"));
        } else {
            echo json_encode(array("Fail" => "fail"));
        }
    }

    public function updatecompany()
    {
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->id = $this->data['id'];
        $this->name = $this->data['name'];
        $this->address = $this->data['address'];
        $this->email = $this->data['email'];
        $this->contact = $this->data['contact'];
        $this->city = $this->data['city'];
        $this->sql = "update company1 set name='{$this->name}', address='{$this->address}', email='{$this->email}', contact='{$this->contact}', city='{$this->city}' where id=$this->id";
        $this->result = $this->conn->exec($this->sql);
        if ($this->result) {
            echo json_encode(array("Success" => "Updated successfully"));
        } else {
            echo json_encode(array("Fail" => "Update fail"));
        }
    }

    public function emailvalidation()
    {
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->email = $this->data['email'];
        $this->sql = "select * from company1 where email='$this->email'";
        $this->stmt = $this->conn->query($this->sql);
        $this->stmt->execute();
        $this->data1 = $this->stmt->fetchAll();
        if (count($this->data1) > 0) {
            echo json_encode(array("Message" => "Email address already exists"));
        } else {
            echo json_encode(array("Message" => ""));
        }
    }


    public function getcompany()
    {

        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        if (isset($_GET['id'])) {
            $this->cid = $_GET['id'];
            $this->sql = "select * from company1 where id=$this->cid";
            $this->stmt = $this->conn->query($this->sql);
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
        } else {
            $this->sql = "select * from company1";
            $this->stmt = $this->conn->query($this->sql);
            $this->stmt->execute();
            $this->data = $this->stmt->fetchAll();
            if (count($this->data) > 0) {
                $this->users_arr = array();
                $this->users_arr['data'] = array();
                $rowCount = count($this->data);
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
                    array_push($this->users_arr['data'], $this->user_data);
                }
                echo json_encode($this->users_arr);

            }
        }
    }

}
//$d=new Database();
//$c=new Create($d);
//$c->table($conn);
//$c->postcompany();
//$c->deletecompany($conn, $data);
//$c->updatecompany($conn, $data);
//$c->emailvalidation($conn, $data);

