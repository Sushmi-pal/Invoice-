<?php
require_once 'db.php';

class User
{
    private $id;
    private $name;
    private $address;
    private $email;
    private $contact;
    private $city;
    private $admin_customer;

    /**
     *
     * User constructor
     *
     */
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->ConnectMe();
        $this->data = $database->Datas();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Content-Type: application/json; charset=UTF-8");

    }


    /**
     *
     * Create user table
     *
     */
    public function Table()
    {
        try {
//            $sql = "drop table if exists User cascade";
//            $this->conn->exec($sql);
            $sql = "CREATE TABLE UserRole(
            id serial unique,
            name varchar(255),
            address varchar(255),
            email varchar(50),
            contact varchar(20),
            admin_customer varchar(10),
            city varchar(255));";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function PostUser()
    {
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->name = $this->data['name'];
        $this->address = $this->data['address'];
        $this->email = $this->data['email'];
        $this->contact = $this->data['contact'];
        $this->city = $this->data['city'];
        $this->admin_customer=$this->data['admin_customer'];
        try {
            if ($this->name && $this->address && $this->email && $this->contact && $this->city && $this->admin_customer) {
                $sql = "insert into userrole(name, address, email, contact, admin_customer, city) values(:name,:address,:email,:contact, :admin_customer, :city)";
                $query=$this->conn->prepare($sql);
                $query->bindValue(':name',$this->name);
                $query->bindValue(':address',$this->address);
                $query->bindValue(':email',$this->email);
                $query->bindValue(':contact',$this->contact);
                $query->bindValue(':admin_customer',$this->admin_customer);
                $query->bindValue(':city',$this->city);
                $result = $query->execute();
                return (array)$result;


            } else {
                throw new Exception('text');
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }

}
//$user=new User();
//try{
//    $result=$user->Table();
//    if ($user){
//        echo json_encode(array("Success"=>"User Table Created"));
//    }
//}
//catch (Exception $e){
//    echo $e->getMessage();
//}