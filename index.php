<?php

require_once './classes/Route.php';
require_once './controller/CompanyController.php';
require_once './controller/InvoiceController.php';

Route::set('companycreate', function () {
    CompanyController::CompanyCreate();
});

Route::set('companylist', function () {
    CompanyController::CompanyList();
});

Route::set('companyupdate', function () {
    CompanyController::CompanyUpdate();
});

Route::set('companydelete', function () {
    CompanyController::CompanyDelete();
});

Route::set('emailvalidate', function () {
    CompanyController::ValidateEmail();
});

Route::set('invoiceupdate', function () {
    InvoiceController::InvoiceUpdate();
});

Route::set('invoicecreate', function () {
    InvoiceController::CreateInvoice();
});

Route::set('deleteinvoice', function () {
    InvoiceController::DeleteInvoice();
});

Route::set('searchinvoice', function () {
    InvoiceController::SearchInvoice();
});

Route::set('invoicepages', function () {
    InvoiceController::InvoicePages();
});

Route::set('getcompany', function () {
    CompanyController::GetCompany();
});


?>