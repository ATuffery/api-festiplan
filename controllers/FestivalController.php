<?php

namespace ApiFestiplan\controllers;

use ApiFestiplan\mvc\HttpHelper;
use ApiFestiplan\mvc\View;
use ApiFestiplan\utils\Error;
use services\AuthService;

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

    /**
     * Allow a user to connect to the app via his login and password
     * @param \PDO $pdo the database connection
     * @return View the festival data in json format
     */
    public function connexion(\PDO $pdo){
        HttpHelper::checkMethod("POST");

        if (is_null(HttpHelper::getParam(0))) {
            Error::err(400, "Aucun identifiant n'a été envoyé.");
        }

        if (is_null(HttpHelper::getParam(1))) {
            Error::err(400, "Aucun mot de passe n'a été envoyé.");
        }

        $login = HttpHelper::getParam(0);
        $password = HttpHelper::getParam(1);

        $user = AuthService::connexion($pdo, $login, $password);

        if (is_null($user) || empty($user)) {
            Error::err(401, "Identifiant ou mot de passe incorrect.");
        }

        $_SESSION["user_id"] = $user["idUtilisateur"];
        $_SESSION["logged"] = true;

        $this->all($pdo);
    }


    /**
     * Add a festival to the user's favorite
     * @param \PDO $pdo the database connection
     * @return View the festival data in json format
     */
    public function add_to_fav(\PDO $pdo) {
        HttpHelper::checkMethod("POST");

        if (is_null(HttpHelper::getParam(0))) {
            Error::err(400, "L'id du festival est manquant.");
        }

        $user_id = $_SESSION["user_id"];
        $festival_id = HttpHelper::getParam(0);

        $query = "INSERT INTO Favori (idUtilisateur, idFestival) VALUES (:user_id, :festival_id)";

    }
}