<?php

namespace services;

use http\Exception\RuntimeException;

class FavoriService {

    public static function getUserId(\PDO $pdo, string $apiKey) {
        $query = "SELECT idUtilisateur FROM utilisateur WHERE apiKey = :apiKey";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":apiKey", $apiKey);

        $stmt->execute();

        $userId = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $userId["idUtilisateur"];
    }


    public static function addFavori(\PDO $pdo, int $idFestival, int $idUser) {
        $query = "INSERT INTO Favori (idUtilisateur, idFestival) VALUES (:idUtilisateur, :idFestival)";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idUtilisateur", $idUser);
        $stmt->bindParam(":idFestival", $idFestival);

        try {
            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \RuntimeException("Ce festival n'existe pas ou il est déjà en favoris.");
        }
    }

    public static function removeFavoris(\PDO $pdo, int $idFestival, int $idUser) {
        $query = "DELETE FROM Favori WHERE idUtilisateur = :idUtilisateur AND idFestival = :idFestival";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idUtilisateur", $idUser);
        $stmt->bindParam(":idFestival", $idFestival);

        $stmt->execute();
    }
}
