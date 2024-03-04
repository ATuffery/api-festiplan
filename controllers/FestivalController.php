<?php

namespace ApiFestiplan\controllers;

use ApiFestiplan\mvc\HttpHelper;
use ApiFestiplan\mvc\View;
use ApiFestiplan\utils\Error;
use services\AuthService;
use services\FavoriService;
use services\ConsultService;

class FestivalController
{
    /**
     * Get all the festivals
     * @param \PDO $pdo the database connection
     * @return View the data in json format
     */
    public function all(\PDO $pdo) {
        HttpHelper::checkMethod("GET");

        try {
            $infos = ConsultService::consultListFestival($pdo);
            $view = new View("api");
            $view->setVar("http_code", 200);
            $view->setVar("json", $infos);
            return $view;
        } catch (\PDOException $e) {
            Error::err(500, "Base de données injoignable.");
        }
    }

    /**
     * get all favorite festival
     * @param \PDO $pdo the database connection
     * @return View|void the data in json format
     */
    public function listFavoriteFestival(\PDO $pdo) {
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam())) {
            Error::err(400, "ApiKey manquant.");
        }

        $user_apiKey = HttpHelper::getParam();
        try {
            $user_id = FavoriService::getUserId($pdo, $user_apiKey);
        } catch (\PDOException $e) {
            Error::err(500, "ApiKey invalide.");
        }

        try {
            $infos = ConsultService::consultListFavoriteFestival($pdo, $user_id);
            $view = new View("api");
            $view->setVar("http_code", 200);
            $view->setVar("json", $infos);
            return $view;
        } catch (\PDOException $e) {
            var_dump($e);
            die();
            Error::err(500, "Base de données injoignable.");
        }
    }

    /**
     * Get the details of a festival
     * @param \PDO $pdo the database connection
     * @return View the festival data in json format
     */
    public function detailsFestival(\PDO $pdo) {
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam())) {
            Error::err(400, "L'id du festival est manquant.");
        }
        try {
            $infos = ConsultService::detailsFestival($pdo, HttpHelper::getParam());
            $view = new View("api");
            $view->setVar("http_code", 200);
            $view->setVar("json", $infos );
            return $view;
        } catch (\PDOException $e) {
            Error::err(500, "Base de données injoignable.");
        }
    }

    /**
     * Get the details of a festival
     * @param \PDO $pdo the database connection
     * @return View the festival data in json format
     */
    public function detailsShow(\PDO $pdo) {
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam())) {
            Error::err(400, "L'id du spectacle est manquant.");
        }
        try {
            $infos = ConsultService::detailsShow($pdo, HttpHelper::getParam());
            $view = new View("api");
            $view->setVar("http_code", 200);
            $view->setVar("json", $infos );
            return $view;
        } catch (\PDOException $e) {
            Error::err(500, "Base de données injoignable.");
        }
    }



    /**
     * Allow a user to connect to the app via his login and password
     * @param \PDO $pdo the database connection
     * @return View the festival data in json format
     */
    public function connexion(\PDO $pdo){
//        HttpHelper::checkMethod("POST");
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam(0))) {
            Error::err(400, "Aucun identifiant n'a été envoyé.");
        }

        if (is_null(HttpHelper::getParam(1))) {
            Error::err(400, "Aucun mot de passe n'a été envoyé.");
        }

        $login = HttpHelper::getParam(0);
        $password = HttpHelper::getParam(1);

        try {
            $user = AuthService::connexion($pdo, $login, $password);
        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacéssible. (co)");
        }

        if (is_null($user) || empty($user)) {
            Error::err(401, "Identifiant ou mot de passe incorrect.");
        } else {
            try {
                AuthService::addApiKey($user, $pdo);
            } catch (\PDOException $e) {
                var_dump($e);
                Error::err(500, "Base de donnée inacéssible. (api)");
            }
        }

        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", $user["apiKey"]);

        return $view;
    }


    /**
     * Add a festival to the user's favorite
     * @param \PDO $pdo the database connection
     * @return View the festival data in json format
     */
    public function add_to_fav(\PDO $pdo) {
//        HttpHelper::checkMethod("POST");
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam(0))) {
            Error::err(400, "L'id du festival est manquant.");
        }

        if (is_null(HttpHelper::getParam(1))) {
            Error::err(400, "ApiKey invalide.");
        }

        $festival_id = HttpHelper::getParam(0);
        $user_apiKey = HttpHelper::getParam(1);

        try {
            $user_id = FavoriService::getUserId($pdo, $user_apiKey);
        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacéssible.");
        }

        if (is_null($user_id)) {
            Error::err(401, "ApiKey invalide.");
        }

        try {
            FavoriService::addFavori($pdo, $festival_id, $user_id);
        } catch (\RuntimeException $e) {
            Error::err(400, $e->getMessage());

        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacéssible.");
        }

        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", "Festival ajouté aux favoris.");

        return $view;

    }

    /**
     * Removes a festival to the user's favorite
     * @param \PDO $pdo the database connection
     * @return View the festival data in json format
     */
    public function remove_fav(\PDO $pdo) {
//        HttpHelper::checkMethod("POST");
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam(0))) {
            Error::err(400, "L'id du festival est manquant.");
        }

        if (is_null(HttpHelper::getParam(1))) {
            Error::err(400, "ApiKey invalide.");
        }

        $festival_id = HttpHelper::getParam(0);
        $user_apiKey = HttpHelper::getParam(1);

        try {
            $user_id = FavoriService::getUserId($pdo, $user_apiKey);
        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacéssible.");
        }

        if (is_null($user_id)) {
            Error::err(401, "ApiKey invalide.");
        }

        try {
            FavoriService::removeFavoris($pdo, $festival_id, $user_id);
        } catch (\PDOException $e) {
            var_dump($e);
            die();
            Error::err(500, "Base de donnée inacéssible.");
        }

        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", "Festival supprimé des favoris.");

        return $view;

    }




}