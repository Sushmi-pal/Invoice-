<?php

class CreateTable
{

    /**
     *
     * Create company table
     *
     */
    public function CompanyTable()
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
     *
     * Create invoice table
     *
     */

    public function InvoiceTable()
    {
        try {
            $sql = "drop table if exists invoice cascade";
            $this->conn->exec($sql);
            $sql = "CREATE TABLE Invoice (
            id serial unique,
            company_id int,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            primary key(id),
            foreign key (company_id) references Company(id) on delete cascade)";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *
     * Create item table
     *
     */

    public function ItemTable()
    {
        try {
            $sql = "drop table if exists itemrest cascade ";
            $this->conn->exec($sql);
            $sql = "create table if not exists itemrest(
            id serial unique,
            invoice_id int,
            name varchar(255),
            unit_cost numeric (10,2),
            quantity int )";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *
     * Create total table
     *
     */

    public function TotalTable()
    {
        try {
            $sql = "drop table if exists total cascade";
            $this->conn->exec($sql);
            $sql = "create table total(
            id serial unique ,
            invoice_id int,
            advance_payment numeric (10,2),
            total_cost numeric(10,2),
            wdiscount numeric (10,2),
            due numeric(10,2),
            foreign key (invoice_id) references invoice(id) on delete cascade );";
            $this->conn->exec($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$table = new CreateTable();
$table->CompanyTable();
$table->InvoiceTable();
$table->ItemTable();
$table->InvoiceTable();
$table->TotalTable();
