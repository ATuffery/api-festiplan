<?php

namespace services;

class AuthService {

    /**
     * Check if an account exists with the given login and password
     * @param \PDO $pdo
     * @param string $login
     * @param string $password
     * @return mixed
     */
    public static function connexion(\PDO $pdo, string $login, string $password){
        $query = "SELECT * FROM utilisateur WHERE login = :login AND mdp = :password";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":login", $login);
        $stmt->bindParam(":password", $password);

        $stmt->execute();

        $user = $stmt->fetch();

        return $user;
    }

    /**
     * Add an API key to the user
     * @param $user
     * @param $pdo
     * @return void
     */
    public static function addApiKey($user, $pdo) {

        $apiKeyGen = self::generateApiKey();

        $user_id = $user['idUtilisateur'];

        $insert_apiKey_query = "UPDATE utilisateur SET apiKey = '$apiKeyGen' WHERE idUtilisateur = $user_id";

        $stmt = $pdo->prepare($insert_apiKey_query);

        $stmt->execute();
    }

    /**
     * Generate an API key
     * @param $login
     * @param $password
     * @return string
     */
    private static function generateApiKey() {
        return bin2hex(random_bytes(35));
    }
}