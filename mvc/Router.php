<?php

namespace ApiFestiplan\mvc;

use ApiFestiplan\utils\Error;


class Router {

    /**
     * Create the right controller and call the right method
     * in function of the url parameters.
     */
    public static function route(DataBase $dataBase = null): void {
        $path = HttpHelper::getUrlParams();

        /** 
         * Formats the controller name with the first letter in uppercase 
         * and the word Controller at the end 
         */
        $controller_name = HttpHelper::getControllerName($path) ?: 'home';
        $controller_name = ucfirst($controller_name);
        $controller_name = $controller_name . "Controller";

        $method_name = HttpHelper::getMethodName($path) ?: 'index';

        /** Check if the controller exists and if the method to call also exists */
        if (!file_exists("controllers/$controller_name.php")
            || !method_exists("ApiFestiplan\\controllers\\" . $controller_name, $method_name)) {

            Error::err(404, "Le controller ou la méthode n'existe pas.");

        }

        $controllerName = "ApiFestiplan\\controllers\\" . $controller_name;
        $controller = new $controllerName();

        $view = null;
        if (is_null($dataBase)) {
            $view = $controller->$method_name();
        } else {
            try {
                $view = $controller->$method_name($dataBase->__connect());
            } catch (\PDOException $e) {
                Error::err(500, "Base de donnée inacessible.");
            }
        }

        $view->render();

    }

}