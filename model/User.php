<?php
session_start();
require_once 'db.php';
require_once './PasswordHash/PasswordEncrptDecrypt.php';

class User
{
    private $id;
    private $name;
    private $address;
    private $email;
    private $contact;
    private $city;
    private $admin_customer;
    private $password;
    private $confirm_password;
    private $pwd;


    /**
     *
     * User constructor
     *
     */
    public function __construct()
    {
        $this->pwd= new PasswordEncrptDecrypt();
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
    public function UserTable()
    {
        try {
            $sql = "drop table if exists Users cascade";
            $this->conn->exec($sql);
            $sql = "CREATE TABLE Users(
            id serial unique,
            name varchar(255),
            address varchar(255),
            email varchar(50),
            contact varchar(20),
            admin_customer int,
            password varchar(255),
            confirm_password varchar(255),
            city varchar(255),
            foreign key (admin_customer) references UserRole(id) on delete cascade)";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function RoleTable(){
        try{
//            $sql = "drop table if exists UserRole cascade";
//            $this->conn->exec($sql);
            $sql="CREATE TABLE UserRole(
            id serial unique,
            role varchar(10));";
            $this->conn->exec($sql);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public function RolePermission(){
        $sql="drop table if exists RolePermission cascade";
        $this->conn->exec($sql);
        $sql="CREATE TABLE RolePermission(
            id serial unique,
            role_id int,
            permission_id int,
            foreign key (role_id) references UserRole(id) on delete cascade,
            foreign key (permission_id) references Permissions(id) on delete cascade );";
        $this->conn->exec($sql);
    }

    public function PermissionTable(){
        try{
            $sql="drop table if exists Permissions cascade";
            $this->conn->exec($sql);
            $sql="CREATE TABLE Permissions(
            id serial unique,
            permissions text);";
            $this->conn->exec($sql);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public function PostPermission(){
        $permissions=['invoice_create', 'invoice_edit', 'invoice_show', 'invoice_delete', 'invoice_access',
            'company_create', 'company_edit', 'company_show', 'company_delete', 'company_access',
            'file_create', 'file_edit', 'file_show', 'file_delete', 'file_access'];
        foreach ($permissions as $permission){
            $sql="insert into permissions(permissions) values('$permission')";
            $this->conn->exec($sql);
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
        $this->password=$this->data['password'];
        $this->password_hash=$this->pwd->PasswordEncrypt($this->password);
        $this->confirm_password=$this->data['confirm_password'];
        $this->confirm_password_hash=$this->pwd->PasswordEncrypt($this->confirm_password);

        if ($this->password===$this->confirm_password){
            $similar_password="true";
        }
        else{
            $similar_password="false";
        }

        try {
            if ($this->name && $this->address && $this->email && $this->contact && $this->city && $this->admin_customer && $similar_password==="true") {
                $sql="select * from userrole where role='$this->admin_customer'";
                $stmt = $this->conn->query($sql);
                $stmt->execute();
                $data = $stmt->fetchAll();
                if (count($data)===0){
                    $sql = "insert into userrole(role) values('$this->admin_customer')";
                    $this->conn->exec($sql);
                }
                $sql="select id from userrole where role='$this->admin_customer'";
                $stmt = $this->conn->query($sql);
                $stmt->execute();
                $data = $stmt->fetchAll();
                foreach ($data as $k => $v) {
                    $role_id = $v['id'];
                }
                echo $role_id;
                $sql = "insert into users(name, address, email, contact, admin_customer, city, password, confirm_password) values(:name,:address,:email,:contact, :admin_customer, :city, :password, :confirm_password)";
                $query=$this->conn->prepare($sql);
                $query->bindValue(':name',$this->name);
                $query->bindValue(':address',$this->address);
                $query->bindValue(':email',$this->email);
                $query->bindValue(':contact',$this->contact);
                $query->bindValue(':admin_customer',$role_id);
                $query->bindValue(':city',$this->city);
                $query->bindValue(':password',$this->password_hash);
                $query->bindValue(':confirm_password',$this->confirm_password_hash);
                $result = $query->execute();
                return (array)$result;


            } else {
                throw new Exception('text');
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }
    function LoginUser(){
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->email = $this->data['email'];
        $this->password=$this->data['password'];
        try {
            $password_hash=$this->pwd->PasswordEncrypt($this->password);
            $sql="select admin_customer from users where email='$this->email' and password='$password_hash'";
            $stmt = $this->conn->query($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            foreach ($data as $k => $v) {
                $_SESSION["role_id"]= $v['admin_customer'];
            }
            $role_id=$_SESSION["role_id"];
            $sql="select role from userrole where id=$role_id";
            $stmt = $this->conn->query($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            foreach ($data as $k => $v) {
                $_SESSION["role"]= $v['role'];
            }

        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }
        if ($_SESSION["role"]==="Admin"){
            return json_encode(array("role"=>"admin"));
        }
        else{
            return json_encode(array("role"=>"customer"));
        }


    }


}



$user=new User();
//$user->RolePermission();
//$user->UserTable();
//$user->RoleTable();
//$user->PermissionTable();
//$user->PostPermission();
//try{
//    $result=$user->Table();
//    if ($user){
//        echo json_encode(array("Success"=>"User Table Created"));
//    }
//}
//catch (Exception $e){
//    echo $e->getMessage();
//}