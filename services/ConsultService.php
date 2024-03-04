<?php

namespace services;

class ConsultService {
    public static function consultListFestival(\PDO $pdo){
        $query = "SELECT f.idFestival, f.titre, cf.nom as categorie, f.description, f.dateDebut, f.dateFin, f.illustration 
                FROM Festival f
                INNER JOIN CategorieFestival cf ON f.categorie = cf.idCategorie";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $festivals = $stmt->fetchAll();

        return $festivals;
    }

    public static function consultListFavoriteFestival(\PDO $pdo, int $idUtilisateur){
        $query = "SELECT f.idFestival, f.titre, cf.nom as categorie, f.description, f.dateDebut, f.dateFin, f.illustration 
                FROM Festival f
                INNER JOIN Favori ON f.idFestival = Favori.idFestival
                INNER JOIN CategorieFestival cf ON f.categorie = cf.idCategorie 
                INNER JOIN Utilisateur u ON Favori.idUtilisateur = u.idUtilisateur
                WHERE u.idUtilisateur = :idUtilisateur
                ORDER BY dateDebut";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idUtilisateur", $idUtilisateur);

        $stmt->execute();

        $festivals = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $festivals;
    }

    /**
     * Retourne les details d'un festival
     * @param \PDO $pdo
     * @param int $idFestival
     * @return mixed
     */
    public static function detailsFestival(\PDO $pdo, int $idFestival){
        $query = "SELECT * FROM Festival f
                INNER JOIN EquipeOrganisatrice eo ON f.idFestival = eo.idFestival
                INNER JOIN Utilisateur u ON eo.idUtilisateur = u.idUtilisateur 
                INNER JOIN SpectacleDeFestival sdf ON f.idFestival = sdf.idFestival
                INNER JOIN Spectacle s ON sdf.idSpectacle = s.idSpectacle
                INNER JOIN CategorieFestival cf ON f.categorie = cf.idCategorie
                WHERE f.idFestival = :idFestival";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idFestival", $idFestival);

        $stmt->execute();

        $festival = $stmt->fetch();

        return $festival;
    }

    /**
     * Retourne les details d'un spectacle
     * @param \PDO $pdo
     * @param int $idSpectacle
     * @return mixed
     */
    public static function detailsShow(\PDO $pdo, int $idSpectacle){
        $query = "SELECT * FROM Spectacle s
                INNER JOIN SpectacleOrganisateur so ON s.idSpectacle = so.idSpectacle
                INNER JOIN Utilisateur u ON so.idUtilisateur = u.idUtilisateur 
                INNER JOIN SpectacleDeFestival sdf ON f.idFestival = sdf.idFestival
                INNER JOIN CategorieSpectacle cs ON s.categorie = cs.idCategorie
                WHERE s.idSpectacle  = :idSpectacle ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idSpectacle", $idSpectacle);

        $stmt->execute();

        $spectacle = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $spectacle;
    }
}