<?php

namespace ApiFestiplan\controllers;

use ApiFestiplan\mvc\HttpHelper;
use ApiFestiplan\mvc\View;
use ApiFestiplan\utils\Error;

class FestivalController
{
    /**
     * Get all the festivals
     * @param \PDO $pdo the database connection
     * @return View the data in json format
     */
    public function all(\PDO $pdo) {
        HttpHelper::checkMethod("GET");

        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", "Test");
        return $view;
    }

    /**
     * Get the details of a festival
     * @param \PDO $pdo the database connection
     * @return View the festival data in json format
     */
    public function details(\PDO $pdo) {
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam())) {
            Error::err(400, "L'id du festival est manquant.");
        }

        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", HttpHelper::getParam());
        return $view;
    }

}