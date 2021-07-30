<?php
require_once 'Routes.php';


function myAutoload($class_name)
{
    if (file_exists('./classes/' . $class_name . '.php')) {
        require_once './classes/' . $class_name . '.php';
    } elseif (file_exists('./controller/' . $class_name . '.php')) {
        require_once './controller/' . $class_name . '.php';
    } elseif (file_exists('./model/' . $class_name . '.php')) {
        require_once './model/' . $class_name . '.php';
    } else {
        require_once $class_name . '.php';
    }

}

spl_autoload_register('myAutoload');
