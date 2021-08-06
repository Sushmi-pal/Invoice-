<?php
session_start();
require_once 'db.php';
require_once './PasswordHash/PasswordEncryptDecrypt.php';

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
        $this->pwd= new PasswordEncryptDecrypt();
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


    public function PostPermission(){
        $permissions=['invoice_create', 'invoice_edit', 'invoice_show', 'invoice_delete', 'invoice_access',
            'company_create', 'company_edit', 'company_show', 'company_delete', 'company_access',
            'file_create', 'file_edit', 'file_show', 'file_delete', 'file_access', 'email_validate'];
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
                $sql="select * from role where role='$this->admin_customer'";
                $stmt = $this->conn->query($sql);
                $stmt->execute();
                $data = $stmt->fetchAll();
                if (count($data)===0){
                    $sql = "insert into role(role) values('$this->admin_customer')";
                    $this->conn->exec($sql);
                }
                $sql="select id from role where role='$this->admin_customer'";
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

                $sql="select id from users where email='$this->email'";
                $stmt = $this->conn->query($sql);
                $stmt->execute();
                $data = $stmt->fetchAll();
                foreach ($data as $k => $v) {
                    $user_id = $v['id'];
                }
                echo $user_id;

                $sql = "insert into userrole(role_id, user_id) values(:role_id,:user_id)";
                $query=$this->conn->prepare($sql);
                $query->bindValue(':user_id',$user_id);
                $query->bindValue(':role_id',$role_id);
                $result_userrole = $query->execute();
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
            $sql="select role from role where id=$role_id";
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
//$user->UserRole();
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