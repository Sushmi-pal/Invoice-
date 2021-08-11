<?php
require_once 'db.php';
require_once './controller/Controller.php';
require_once './FileService/FileService.php';
require_once './ValidatorClass/Validate.php';
require_once './ServiceClass/CompanyRequest.php';
require_once './DBQuery/PostQuery.php';
require_once './DBQuery/RetrieveQuery.php';
require_once './DBQuery/DeleteQuery.php';

/**
 * Class Company
 * @access private
 */
class Company extends Database
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
        $this->up = $file;
        $database = new Database();
        $this->conn = $database->ConnectMe();
        $this->data = $database->Datas();
        $this->dbquery = new PostQuery();
        $this->delete = new DeleteQuery();
        $this->retrieve = new RetrieveQuery();
        $this->update = new UpdateQuery();
        $this->company_request = new CompanyRequest();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: access");
        header("Content-Type: application/json; charset=UTF-8");
    }

    /**
     * Store details of company
     * @return array
     */
    public function PostCompany()
    {
        $this->name = $this->data['name'];
        $this->address = $this->data['address'];
        $this->email = $this->data['email'];
        $this->contact = $this->data['contact'];
        $this->city = $this->data['city'];
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $validate_name=Validate::CheckEmpty('name', $this->name);
        $validate_address=Validate::CheckEmpty('address', $this->address);
        $validate_email=Validate::CheckEmpty('email', $this->email);
        $validate_contact=Validate::CheckEmpty('contact', $this->contact);
        $validate_city=Validate::CheckEmpty('city', $this->city);
        $email_format=Validate::EmailFormat($this->email);


        if ($validate_name && $validate_address && $validate_email && $validate_contact && $validate_city && $email_format) {

            $file_name_new = $this->up->upload($this->data['name'], $this->data['file_name'], $this->data['file_image']);
//        $this->company_request->PostCompany("'$this->name'", "'$this->address'", "'$this->email'", "'$this->contact'", "'$this->city'", "$this->data['file_name']", "$this->data['file_image']");
//        $file_name_new = $this->up->upload($this->data['name'], $this->data['file_name'], $this->data['file_image']);
            $result = $this->dbquery->Insert('Company', 'name,address,email,contact,city,file_name', "$this->name,$this->address,$this->email,$this->contact,$this->city,$file_name_new");
            echo $result;
        }
        else{
            echo $validate_name;
            echo $validate_address;
            echo $validate_email;
            echo $validate_city;
            echo $validate_contact;
            echo $email_format;
        }
    }


    /**
     * Delete company detail
     */
    public function DeleteCompany()
    {
        header("Access-Control-Allow-Methods: DELETE");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $id = $this->data['id'];
        $data = $this->retrieve->Get("invoice.id", "invoice", ['company on invoice.company_id=company.id'], "company.id=:company_id", $id, "", "", "");

        $aa = $this->company_request->DeleteFromId($data);
        if ($aa) {
            $this->result_total = $this->delete->Delete("total", "invoice_id", "$aa");
            $this->result_itemrest = $this->delete->Delete("itemrest", "invoice_id", "$aa");
        }
        $this->data = $this->retrieve->Get("file_name", "company", "", "id=:id", $id, "", "", "");
        $this->up->deleteimage($this->data);
        $this->result_company = $this->delete->Delete("company", "id", "$id");
        $result = $this->company_request->UDResult($this->result_company, "Delete");
        echo $result;
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
        $this->data = $this->retrieve->Get("file_name", "company", "", "id=:id", $this->id, "", "", "");
        if ($file_name_new !== "false") {
            $this->up->deleteimage($this->data);
            $this->sql = $this->update->Update("company", "name={$this->name}, address='{$this->address}', email='{$this->email}', contact='{$this->contact}', city='{$this->city}', file_name='{$file_name_new}'", "id=$this->id");
            $this->result = $this->conn->exec($this->sql);
            $result = $this->company_request->UDResult($this->result, "Update");
            echo $result;
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
        $this->company_data = $this->retrieve->Get("*", "company", "", "email=:email", $this->email, "", "", "");
        $validate_result = Validate::EmailValidation($this->company_data);
        echo $validate_result;
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
            $this->data = $this->retrieve->Get("*", "company", "", "id=:id", $this->cid, "", "", "");
            $user_data = $this->company_request->GetCompany($this->data);
            echo $user_data;

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
            $this->data = $this->retrieve->Get("*", "company", "", "", "", "$ordertype", "$field", "");
            $this->company_request->GetCompanyThree($this->data);
        }
    }

    public function __destruct()
    {
        $db = new Database();
        $db->closeme();
    }

}

