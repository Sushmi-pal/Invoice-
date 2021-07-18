<?php


/**
 * Class Route
 */
class Route
{

    /**
     * @var array
     */
    public static $validRoutes = array();

    /**
     * @param $route
     * @param $function
     * @return void
     */
    public static function set($route, $function)
    {
        self::$validRoutes = $route;
        if ($_GET['url'] == $route) {
            $function->__invoke();
        }


    }
}