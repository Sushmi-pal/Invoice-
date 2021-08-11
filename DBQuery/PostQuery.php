<?php
require_once './model/db.php';

class PostQuery{
    public function Insert($table_name, $column_name, $value){
        $database = new Database();
        $conn = $database->ConnectMe();
        $n=explode(',',$column_name);
        $a='';
        for ($inc=0;$inc<count($n);$inc++){
            $a=$a. ":$n[$inc], ";

        }
        $values=substr($a, 0, strlen($a)-2);
        $sql = "insert into $table_name ($column_name) values($values) ";
        $query = $conn->prepare($sql);
        $explode_value=explode(',', $value);

        for ($inc=0; $inc<count($n); $inc++){
            $query->bindValue(":$n[$inc]", $explode_value[$inc]);

        }
        $result = $query->execute();
        return json_encode(array("Success"=>"Data Created"));

    }
}

$c=new PostQuery();
//$c->Insert('Company', 'name,address,email,contact,city,file_name', "S, A, E, C, C, F");



