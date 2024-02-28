<?php

namespace ApiFestiplan\mvc;

use ApiFestiplan\utils\Error;

/**
 * 
 */
class HttpHelper {

    /** 
     * Get the all url parameters
     * @param string | null the link to analyse
     * @return string | null all the url parameters or null if no parameters found 
     */
    public static function getUrlParams(string $path = null):string | null {
        $path = is_null($path) && isset($_GET['p']) ? $_GET['p'] : $path;
        return !is_null($path) && !empty($path) ? htmlspecialchars($path) : null;
    }

    /** 
     * Get the controller name in the url parameters or in POST parameters
     * @param string | null the link to analyse
     * @return string | null the controller name or null if not found 
     */
    public static function getControllerName(string $path = null):string | null {
        //Try to find the controller name in the URL params
        $path = is_null($path) && isset($_GET['p']) ? $_GET['p'] : $path;
        $params = explode("/", $path ?? "");
        $controller_name = count($params) > 0 && !empty($params[0]) ? htmlspecialchars($params[0]) : "";
        
        if (!empty($controller_name)) {
            return $controller_name;
        }

        //Try to find the controller name in the POST params
        $controller_name = isset($_POST['controller']) && !empty($_POST['controller']) ? htmlspecialchars($_POST['controller']) : "";

        if (!empty($controller_name)) {
            return $controller_name;
        }

        return null;

    }

    /** 
     * Get the method name in the url parameters or in POST parameters
     * @return string | null the method name or null if not found 
     */
    public static function getMethodName(string $path = null):string | null {
        //Try to find the action name in the URL params
        $path = is_null($path) && isset($_GET['p']) ? $_GET['p'] : $path;
        $params = explode("/", $path ?? "");
        $action_name = count($params) > 1 && !empty($params[1]) ? htmlspecialchars($params[1]) : "";
        
        if (!empty($action_name)) {
            return $action_name;
        }

        //Try to find the action name in the POST params
        $action_name = isset($_POST['action']) && !empty($_POST['action']) ? htmlspecialchars($_POST['action']) : "";

        if (!empty($action_name)) {
            return $action_name;
        }

        return null;
    }

    /** 
     * Get parmeters of url
     * @param string | null the link to analyse
     * @return string | null all the parameters or null if no parameters found 
     */
    public static function getParams(string $path = null):array | null {
        $path = is_null($path) && isset($_GET['p']) ? $_GET['p'] : $path;
        $str_params = explode("/", $path, 3);
        return count($str_params) > 2 && !empty($str_params[2]) ? explode("/", htmlspecialchars($str_params[2])) : null;
    }

    /**
     * Check if a param exists at the position $noParam of the url. 
     * Warning! the position start at the index 0 (controller/method/param0/param1/...)
     * @param int $noParam the position of the param in the URL
     * @return bool true if at the position $noParam a param exists,
     *              false otherwise.
     */
    public static function paramGetExists(int $noParam = 0):bool {
        if (is_null(self::getParams())) {
            return false;
        }
        $valToTest =  $noParam < count(self::getParams()) ? self::getParams()[$noParam] : null;
        return !empty($valToTest);
    }

    /**
     * Return the param at the position $noParam of the url. 
     * Warning! the position start at the index 0 (controller/method/param0/param1/...)
     * @param int $noParam the position of the param in the URL
     * @return string|null the param if exists, null otherwise.
     */
    public static function getParam(int $noParam = 0):string|null {
        return self::paramGetExists($noParam) ? htmlspecialchars(self::getParams()[$noParam]) : null;
    }

    /**
     * Check if the HTTP method used is the one expected
     * @param string $method the method to check
     * @return void
     */
    public static function checkMethod(string $method)
    {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            Error::err(405, "Method not allowed.");
        }
    }

}