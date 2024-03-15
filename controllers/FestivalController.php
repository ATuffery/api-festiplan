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
     * @return View|null the data in json format
     */
    public function all(\PDO $pdo): ?View {
        HttpHelper::checkMethod("GET");

        $apiKey = "";
        if (!is_null(HttpHelper::getParam())) {
            $apiKey = (string) HttpHelper::getParam();
        }

        try {
            $infos = ConsultService::consultListFestival($pdo, $apiKey);
            $view = new View("api");
            $view->setVar("http_code", 200);
            $view->setVar("json", $infos);
            return $view;
        } catch (\PDOException $e) {
            Error::err(500, "Base de données injoignable.");
        }

        return null;
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

        $user_apiKey = (string) HttpHelper::getParam();
        $user_id = 0;
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
            Error::err(500, "Base de données injoignable.");
        }
    }

    /**
     * Get the details of a festival
     * @param \PDO $pdo the database connection
     * @return View|null the festival data in json format
     */
    public function detailsFestival(\PDO $pdo): View|null {
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam())) {
            Error::err(400, "L'id du festival est manquant.");
        }
        try {
            $infos = ConsultService::detailsFestival($pdo, (int) HttpHelper::getParam());
            $view = new View("api");
            $view->setVar("http_code", 200);
            $view->setVar("json", $infos );
            return $view;
        } catch (\PDOException $e) {
            Error::err(500, "Base de données injoignable.");
        }

        return null;
    }

    /**
     * Get the details of a festival
     * @param \PDO $pdo the database connection
     * @return View|null the festival data in json format
     */
    public function detailsShow(\PDO $pdo): View|null {
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam())) {
            Error::err(400, "L'id du spectacle est manquant.");
        }
        try {
            $infos = ConsultService::detailsShow($pdo, (int) HttpHelper::getParam());
            $view = new View("api");
            $view->setVar("http_code", 200);
            $view->setVar("json", $infos );
            return $view;
        } catch (\PDOException $e) {
            Error::err(500, "Base de données injoignable.");
        }

        return null;
    }



    /**
     * Allow a user to connect to the app via his login and password
     * @param \PDO $pdo the database connection
     * @return View|null the festival data in json format
     */
    public function connexion(\PDO $pdo): View|null {
//        HttpHelper::checkMethod("POST");
        HttpHelper::checkMethod("GET");

        if (is_null(HttpHelper::getParam(0))) {
            Error::err(400, "Aucun identifiant n'a été envoyé.");
        }

        if (is_null(HttpHelper::getParam(1))) {
            Error::err(400, "Aucun mot de passe n'a été envoyé.");
        }

        $login = (string) HttpHelper::getParam(0);
        $password = (string) HttpHelper::getParam(1);

        $user = null;
        try {
            $user = AuthService::connexion($pdo, $login, $password);
        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacessible. (co)");
        }

        $user_apiKey = "";
        if (empty($user)) {
            Error::err(401, "Identifiant ou mot de passe incorrect.");
        } else {
            $user_id = (int) $user["idUtilisateur"];
            try {
                $user_apiKey = (string) AuthService::addApiKey($pdo, $user_id);
                $user["apiKey"] = $user_apiKey;
            } catch (\PDOException $e) {
                Error::err(500, "Base de donnée inacessible. (api)");
            }
        }

        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", $user);

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

        $festival_id = (int) HttpHelper::getParam(0);
        $user_apiKey = (string) HttpHelper::getParam(1);

        $user_id = 0;
        try {
            $user_id = FavoriService::getUserId($pdo, $user_apiKey);
        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacéssible.");
        }

        if ($user_id == 0) {
            Error::err(401, "ApiKey invalide.");
        }

        try {
            FavoriService::addFavori($pdo, $festival_id, $user_id);
        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacéssible.");
        } catch (\RuntimeException $e) {
            Error::err(400, $e->getMessage());
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

        $festival_id = (int) HttpHelper::getParam(0);
        $user_apiKey = (string) HttpHelper::getParam(1);

        $user_id = 0;
        try {
            $user_id = FavoriService::getUserId($pdo, $user_apiKey);
        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacéssible.");
        }

        if ($user_id == 0) {
            Error::err(401, "ApiKey invalide.");
        }

        try {
            FavoriService::removeFavoris($pdo, $festival_id, $user_id);
        } catch (\PDOException $e) {
            Error::err(500, "Base de donnée inacéssible.");
        }

        $view = new View("api");
        $view->setVar("http_code", 200);
        $view->setVar("json", array("message" => "Festival supprimé des favoris."));

        return $view;

    }




}