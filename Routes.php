<?php

require_once './classes/Route.php';
require_once './controller/Companycontroller.php';
require_once './controller/Invoicecontroller.php';



Route::set('companycreate',function (){
    company::companycreate();
});

Route::set('companylist',function (){
    company::companylist();
});

Route::set('companyupdate',function (){
    company::companyupdate();
});

Route::set('companydelete',function (){
    company::companydelete();
});

Route::set('emailvalidate',function (){
    company::validateemail();
});

Route::set('invoiceupdate',function (){
    invoicepage::invoiceupdate();
});

Route::set('invoicecreate',function (){
    invoicepage::createinvoice();
});

Route::set('deleteinvoice',function (){
    invoicepage::deleteinvoice();
});

Route::set('searchinvoice',function (){
    invoicepage::searchinvoice();
});

Route::set('invoicepages',function (){
    invoicepage::invoicepages();
});

Route::set('getcompany',function (){
    company::getcompany();
});





