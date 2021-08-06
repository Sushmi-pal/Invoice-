<?php
require_once './model/db.php';

class Permission
{
    private $id;
    private $name;
    private $role;
    private $permission;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->ConnectMe();
        $this->data = $database->Datas();
    }

    public function CheckForPermission($role, $permission)
    {
        $sql = "select permissions from role inner join rolepermission on role.id=rolepermission.role_id inner join permissions on rolepermission.permission_id=permissions.id where role.role='$role' and permissions.permissions='$permission'";
        $stmt = $this->conn->query($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        foreach ($data as $k => $v) {
            if ($v['permissions'] === $permission) {
                return "true";
            } else {
                return "false";
            }
        }

    }
}