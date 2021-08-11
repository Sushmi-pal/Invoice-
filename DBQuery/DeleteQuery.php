<?php
require_once './model/db.php';

class DeleteQuery{
    public function Delete($table_name, $column_name, $id){
        $database = new Database();
        $conn = $database->ConnectMe();
        $d_query="Delete from ".$table_name. " where ".$column_name." = ".$id;
        $result=$conn->exec($d_query);
        return $result;
    }
}


