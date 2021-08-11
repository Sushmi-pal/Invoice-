<?php
require_once './FileService/FileService.php';
require_once './DBQuery/PostQuery.php';

class CompanyRequest
{
    public function __construct()
    {
        $file = new FileService();
        $up = $file;
        $dbquery = new PostQuery();
    }

    public function PostCompany($name, $address, $email, $contact, $city, $file_name, $file_image)
    {
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: *, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        if ($name && $address && $email && $contact && $city && $file_name && $file_image) {

            $file_name_new = $up->upload($name, $file_name, $file_image);
            return $file_name_new;

        } else {
            echo "Validation";
        }
    }
    
    public function DeleteFromId($data){
        $aa = [];
        foreach ($data as $k => $v) {
            $aa = $v['id'];
        }
        return $aa;
    }
    
    public function UDResult($result,$Operation){
        if ($result) {
            return json_encode(array("Success" => "$Operation successfully"));
        } else {
            return json_encode(array("Fail" => "fail"));
        }
    }
    
    public function GetCompany($data){
        $suser = array();
        $suser['data'] = array();

        foreach ($data as $k => $v) {
            $user_data = array(
                'id' => $v['id'],
                'name' => $v['name'],
                'email' => $v['email'],
                'address' => $v['address'],
                'contact' => $v['contact'],
                'city' => $v['city'],
                'file_name' => $v['file_name']
            );
//        Push to array
            array_push($suser['data'], $user_data);
        }
        return json_encode($suser);
    }
    
    public function GetCompanyElse($sort, $order){
        $field=$sort;
        if (isset($order)) {
            $ordertype = ($order == 'desc') ? 'desc' : 'asc';
        } else {
            $ordertype = 'asc';
        }
        return array($field, $ordertype);
    }
    
    public function GetCompanyThree($data){
        if (count($data) > 0) {
            $users_arr = array();
            $users_arr['data'] = array();
            $rowCount = count($data);
            foreach ($data as $k => $v) {


                $user_data = array(
                    'id' => $v['id'],
                    'name' => $v['name'],
                    'email' => $v['email'],
                    'address' => $v['address'],
                    'contact' => $v['contact'],
                    'city' => $v['city']
                );

                array_push($users_arr['data'], $user_data);
            }
            echo json_encode($users_arr);

        }
    }
}