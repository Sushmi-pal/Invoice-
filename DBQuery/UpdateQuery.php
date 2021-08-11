<?php

require_once './model/db.php';

class UpdateQuery{
    public function Update($table_name, $replace, $where){
        $a="update $table_name set $replace where $where";
        return $a;
    }
}
$u=new UpdateQuery();
//$b=1;
//$u->Update("abc","aa=$b", "id=id");

//$s=$u->Update("itemrest", "name='s', unit_cost=11, quantity=1", "id=1");
//echo $s;
