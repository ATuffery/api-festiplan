<?php

namespace ApiFestiplan\controllers;

use ApiFestiplan\mvc\View;

class FestivalController
{
    public function all(\PDO $pdo) {
        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", "Test");
    }

}