<?php

namespace services;

class ConsultService {
    public function consulterFestival(\PDO $pdo){
        $query = "SELECT * FROM Festival ORDER BY dateDebut";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $festivals = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $festivals;
    }

    public function consulterFestivalFavori(\PDO $pdo, string $apiKey){
        $query = "SELECT * FROM Festival  
                INNER JOIN Favori ON Festival.idFestival = Favori.idFestival 
                INNER JOIN Utilisateur ON Favori.idUtilisateur = Utilisateur.idUtilisateur
                WHERE Utilisateur.apiKey = :apiKey
                ORDER BY dateDebut";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":apiKey", $apiKey);

        $stmt->execute();

        $festivals = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $festivals;
    }

    /**
     * Retourne les details d'un festival
     * @param \PDO $pdo
     * @param int $idFestival
     * @return mixed
     */
    public function detailFestival(\PDO $pdo, int $idFestival){
        $query = "SELECT * FROM Festival f
                INNER JOIN EquipeOrganisatrice eo ON f.idFestival = eo.idFestival
                INNER JOIN Utilisateur u ON eo.idUtilisateur = u.idUtilisateur 
                INNER JOIN SpectacleDeFestival sdf ON f.idFestival = sdf.idFestival
                INNER JOIN Spectacle s ON sdf.idSpectacle = s.idSpectacle
                INNER JOIN CategorieSpectacle cs ON f.categorie = cs.idCategorie
                WHERE f.idFestival = :idFestival
                ORDER BY dateDebut";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idFestival", $idFestival);

        $stmt->execute();

        $festival = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $festival;
    }
}