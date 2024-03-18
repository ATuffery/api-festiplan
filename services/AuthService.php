<?php

namespace services;

class AuthService {

    /**
     * Check if an account exists with the given login and password
     * @param \PDO $pdo
     * @param string $login
     * @param string $password
     * @return array{ idUtilisateur: int, apiKey: string } | array{}
     */
    public static function connexion(\PDO $pdo, string $login, string $password): array {
        $query = "SELECT idUtilisateur, apiKey FROM utilisateur WHERE login = :login AND mdp = :password";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":login", $login);
        $stmt->bindParam(":password", $password);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return array();
        }

        $data = $stmt->fetchAll()[0];

        $return_data = array();
        $return_data["idUtilisateur"] = $data["idUtilisateur"];
        $return_data["apiKey"] = $data["apiKey"];

        return $return_data;
    }

    /**
     * Add an API key to the user
     * @param \PDO $pdo the database connection
     * @param int $user_id the user
     * @return string
     */
    public static function addApiKey(\PDO $pdo, int $user_id): string {

        $apiKeyGen = self::generateApiKey();

        $insert_apiKey_query = "UPDATE utilisateur SET apiKey = '$apiKeyGen' WHERE idUtilisateur = $user_id";

        $stmt = $pdo->prepare($insert_apiKey_query);

        $stmt->execute();

        return $apiKeyGen;
    }

    /**
     * Generate an API key
     * @return string
     */
    private static function generateApiKey(): string {
        return bin2hex(random_bytes(35));
    }

    /**
     * Get the user id from the API key
     * @param \PDO $pdo the database connection
     * @param string $apiKey the API key
     * @return array[idUtilisateur:int] | null the user id or null if not found
     */
    public static function getUserId(\PDO $pdo, string $apiKey) : array|null
    {
        try {
            $query = "SELECT idUtilisateur FROM utilisateur WHERE apiKey = :apiKey";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":apiKey", $apiKey);
            $stmt->execute();
            return $stmt->rowCount() == 0 ? null : (array) $stmt->fetch()["idUtilisateur"];
        } catch (\PDOException $e) {
            return null;
        }
    }
}