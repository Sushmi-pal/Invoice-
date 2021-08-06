<?php

require_once '../model/db.php';

class MigrateUser
{
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->ConnectMe();
    }

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
            foreign key (admin_customer) references Role(id) on delete cascade)";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function RoleTable()
    {
        try {
//            $sql = "drop table if exists role cascade";
//            $this->conn->exec($sql);
            $sql = "CREATE TABLE role(
            id serial unique,
            role varchar(10));";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function RolePermission()
    {
        $sql = "drop table if exists RolePermission cascade";
        $this->conn->exec($sql);
        $sql = "CREATE TABLE RolePermission(
            id serial unique,
            role_id int,
            permission_id int,
            foreign key (role_id) references Role(id) on delete cascade,
            foreign key (permission_id) references Permissions(id) on delete cascade );";
        $this->conn->exec($sql);
    }

    public function UserRole()
    {
        $sql = "drop table if exists userrole cascade";
        $this->conn->exec($sql);
        $sql = "CREATE TABLE userrole(
            id serial unique,
            role_id int,
            user_id int,
            foreign key (role_id) references role(id) on delete cascade,
            foreign key (user_id) references users(id) on delete cascade );";
        $this->conn->exec($sql);
    }

    public function PermissionTable()
    {
        try {
            $sql = "drop table if exists Permissions cascade";
            $this->conn->exec($sql);
            $sql = "CREATE TABLE Permissions(
            id serial unique,
            permissions text);";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$user_migration = new MigrateUser();
//$user_migration->UserTable();
//$user_migration->RoleTable();
$user_migration->PermissionTable();
//$user_migration->UserRole();
//$user_migration->RolePermission();