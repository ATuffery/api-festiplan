<?php

namespace services;

use http\Exception\RuntimeException;

class FavoriService {

    public static function getUserId(\PDO $pdo, string $apiKey): int {
        $query = "SELECT idUtilisateur FROM utilisateur WHERE apiKey = :apiKey";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":apiKey", $apiKey);

        $stmt->execute();

        $userId = (array) $stmt->fetch();

        return 0 + $userId['idUtilisateur'];
    }


    public static function addFavori(\PDO $pdo, int $idFestival, int $idUser): void {
        $query = "INSERT INTO favori (idUtilisateur, idFestival) VALUES (:idUtilisateur, :idFestival)";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idUtilisateur", $idUser);
        $stmt->bindParam(":idFestival", $idFestival);

        try {
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \RuntimeException("Ce festival n'existe pas ou il est déjà en favoris.");
        }
    }

    public static function removeFavoris(\PDO $pdo, int $idFestival, int $idUser): void {
        $query = "DELETE FROM favori WHERE idUtilisateur = :idUtilisateur AND idFestival = :idFestival";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idUtilisateur", $idUser);
        $stmt->bindParam(":idFestival", $idFestival);

        try {
            $stmt->execute();

        } catch (\PDOException $e) {
            throw new \RuntimeException("Ce festival n'existe pas ou il n'est pas en favoris.");
        }
    }
}
