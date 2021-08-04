<?php
require_once 'db.php';
require_once './controller/Controller.php';
require_once './FileService/FileService.php';

/**
 * Class Company
 * @access private
 */
class Company
{
    private $id;
    private $name;
    private $address;
    private $email;
    private $contact;
    private $city;
    private $up;

    /**
     *
     * Company constructor
     *
     */
    public function __construct()
    {
        $file = new FileService();
        $this->up=$file;
        $database = new Database();
        $this->conn = $database->ConnectMe();
        $this->data = $database->Datas();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Content-Type: application/json; charset=UTF-8");

    }


    /**
     *
     * Create company table
     *
     */
    public function Table()
    {
        try {
            $sql = "drop table if exists company cascade";
            $this->conn->exec($sql);
            $sql = "CREATE TABLE company(
            id serial unique,
            name varchar(255),
            address varchar(255),
            email varchar(50),
            contact varchar(20),
            file_name varchar(255),
            city varchar(255));";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * Store details of company
     * @return array
     */
    public function PostCompany()
    {
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//        header("Content-Type: multipart/form-data; charset=UTF-8");

        $this->name = $this->data['name'];
        $this->address = $this->data['address'];
        $this->email = $this->data['email'];
        $this->contact = $this->data['contact'];
        $this->city = $this->data['city'];

//        $this->sql = "select * from company where name=:name";
//        $this->stmt = $this->conn->prepare($this->sql);
//        $this->stmt->bindValue(':name', $this->name);
//        $this->stmt->execute();
//        $this->company_data = $this->stmt->fetchAll();

        try {
            if ($this->name && $this->address && $this->email && $this->contact && $this->city) {

                $file_name_new = $this->up->upload($this->data['name'], $this->data['file_name'], $this->data['file_image']);
                $sql = "insert into company(name, address, email, contact, city, file_name) values(:name,:address,:email,:contact,:city, :file)";
                $query = $this->conn->prepare($sql);
                $query->bindValue(':name', $this->name);
                $query->bindValue(':address', $this->address);
                $query->bindValue(':email', $this->email);
                $query->bindValue(':contact', $this->contact);
                $query->bindValue(':city', $this->city);
                $query->bindValue(':file', $file_name_new);
                $result = $query->execute();
                return (array)$result;
            } else {
                throw new Exception('text');
            }
        } catch (Exception $e) {
            Controller::ErrorLog($e);
        }

    }


    /**
     * Delete company detail
     */
    public function DeleteCompany()
    {
        header("Access-Control-Allow-Methods: DELETE");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $aa = [];
        $id = $this->data['id'];
        $sql = "select invoice.id from invoice inner join company on invoice.company_id=company.id where company.id=$id";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        foreach ($data as $k => $v) {
            $aa = $v['id'];
        }
        if ($aa) {
            $sql = "delete from total where invoice_id=$aa";
            $this->result_total = $this->conn->exec($sql);
            $sql = "delete from itemrest where invoice_id = $aa";
            $this->result_itemrest = $this->conn->exec($sql);
        }
        $sql="select file_name from company where id=$id";
        $this->stmt = $this->conn->query($sql);
        $this->stmt->execute();
        $this->data = $this->stmt->fetchAll();
        $this->up->deleteimage($this->data);
        $sql = "delete from company where id=$id";
        $this->result_company = $this->conn->exec($sql);
        if ($this->result_company) {
            echo json_encode(array("Success" => "Deleted successfully"));
        } else {
            echo json_encode(array("Fail" => "fail"));
        }
    }

    /**
     * Update the company table
     */

    public function UpdateCompany()
    {
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->id = $this->data['id'];
        $this->name = $this->data['name'];
        $this->address = $this->data['address'];
        $this->email = $this->data['email'];
        $this->contact = $this->data['contact'];
        $this->city = $this->data['city'];
        $this->file_name = $this->data['file_name'];
        $this->file_image = $this->data['file_image'];
        $file_name_new = $this->up->upload($this->data['name'], $this->data['file_name'], $this->data['file_image']);
        $this->sql = "select file_name from company where id=$this->id";
        $this->stmt = $this->conn->query($this->sql);
        $this->stmt->execute();
        $this->data = $this->stmt->fetchAll();
        $this->up->deleteimage($this->data);
        $this->sql = "update company set name='{$this->name}', address='{$this->address}', email='{$this->email}', contact='{$this->contact}', city='{$this->city}', file_name='{$file_name_new}' where id=$this->id";
        $this->result = $this->conn->exec($this->sql);
        if ($this->result) {
            echo json_encode(array("Success" => "Updated successfully"));
        } else {
            echo json_encode(array("Fail" => "Update fail"));
        }
    }

    /**
     *
     * Validates whether the email address already exists in database
     *
     */

    public function EmailValidation()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->email = $this->data['email'];
        $this->sql = "select * from company where email=:email";
        $this->stmt = $this->conn->prepare($this->sql);
        $this->stmt->bindValue(':email', $this->email);
        $this->stmt->execute();
        $this->company_data = $this->stmt->fetchAll();
        if (count($this->company_data) > 0) {
            echo json_encode(array("Message" => "Email address already exists"));
        } else {
            echo json_encode(array("Message" => ""));
        }
    }


    /**
     * Get the details of company
     */
    public function GetCompany()
    {
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Credentials: true");
        if (isset($_GET['id'])) {
            $this->cid = $_GET['id'];
            $this->sql = "select * from company where id=:id";
            $this->stmt = $this->conn->prepare($this->sql);
            $this->stmt->bindValue(':id', $this->cid);
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
                    'city' => $this->v['city'],
                    'file_name' => $this->v['file_name']
                );
//        Push to array
                array_push($this->suser['data'], $this->user_data);
            }
            echo json_encode($this->suser);
        } else {
            if (isset($_GET['sort'])) {
                $field = $_GET['sort'];
            }
            if ($_GET['sort'] == 'undefined') {
                $field = 'name';
            }
            if (isset($_GET['order'])) {
                $ordertype = ($_GET['order'] == 'desc') ? 'desc' : 'asc';
            } else {
                $ordertype = 'asc';
            }

            $this->sql = "select * from company order by" . " $field $ordertype";
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

                    array_push($this->users_arr['data'], $this->user_data);
                }
                echo json_encode($this->users_arr);

            }
        }
    }

    public function __destruct()
    {
        $db = new Database();
        $db->closeme();
    }

}

