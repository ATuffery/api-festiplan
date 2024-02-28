<?php

namespace ApiFestiplan\controllers;

use ApiFestiplan\mvc\View;

class FestivalController
{
    /**
     * Get all the festivals
     * @param \PDO $pdo the database connection
     * @return View the data in json format
     */
    public function all(\PDO $pdo) {
        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", "Test");
        return $view;
    }

}