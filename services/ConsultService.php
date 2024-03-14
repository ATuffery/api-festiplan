<?php

namespace services;

class ConsultService {
    /**
     * Returns the list of all festivals
     * @param \PDO $pdo
     * @return array<array{idFestival:int, titre:string, categorie:string, description:string, dateDebut:string, dateFin:string, illustration:string}>|bool
     */
    public static function consultListFestival(\PDO $pdo): array|bool {
        $query = "SELECT f.idFestival, f.titre, cf.nom as categorie, f.description, f.dateDebut, f.dateFin, f.illustration 
                FROM festival f
                INNER JOIN categoriefestival cf ON f.categorie = cf.idCategorie
                ORDER BY f.dateDebut";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        return (array) $stmt->fetchAll();
    }

    /**
     * Returns the list of all favorite festivals
     * @param \PDO $pdo
     * @param int $idUtilisateur
     * @return array<array{idFestival:int, titre:string, categorie:string, description:string, dateDebut:string, dateFin:string, illustration:string}>|bool
     */
    public static function consultListFavoriteFestival(\PDO $pdo, int $idUtilisateur): array|bool
    {
        $query = "SELECT f.idFestival, f.titre, cf.nom as categorie, f.description, f.dateDebut, f.dateFin, f.illustration 
                FROM festival f
                INNER JOIN favori ON f.idFestival = Favori.idFestival
                INNER JOIN categoriefestival cf ON f.categorie = cf.idCategorie 
                INNER JOIN utilisateur u ON Favori.idUtilisateur = u.idUtilisateur
                WHERE u.idUtilisateur = :idUtilisateur
                ORDER BY f.dateDebut";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idUtilisateur", $idUtilisateur);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Retourne les details d'un festival
     * @param \PDO $pdo
     * @param int $idFestival
     * @return mixed
     */
    public static function detailsFestival(\PDO $pdo, int $idFestival): mixed {
        $query = "SELECT f.idFestival, cf.nom as categorie, f.titre, f.description, f.dateDebut, f.dateFin, f.illustration, 
                eo.responsable, u.prenom, u.nom, 
                s.titre as titreSpectacle, s.description as descriptionSpectacle, s.duree as dureeSpectacle, 
                s.illustration as illustrationSpectacle, cs.nomCategorie as categorieSpectacle
                FROM festival f
                LEFT JOIN equipeorganisatrice eo ON f.idFestival = eo.idFestival
                LEFT JOIN utilisateur u ON eo.idUtilisateur = u.idUtilisateur 
                LEFT JOIN spectacledefestival sdf ON f.idFestival = sdf.idFestival
                LEFT JOIN spectacle s ON sdf.idSpectacle = s.idSpectacle
                LEFT JOIN categoriespectacle cs ON s.categorie = cs.idCategorie
                LEFT JOIN categoriefestival cf ON f.categorie = cf.idCategorie
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
        $query = "SELECT s.idSpectacle, s.titre, s.description, s.duree, cs.nomCategorie as categorie, s.illustration, 
                u.prenom as prenomOrganisateur , u.nom as nomOrganisateur
                FROM Spectacle s
                LEFT JOIN spectacleorganisateur so ON s.idSpectacle = so.idSpectacle
                LEFT JOIN utilisateur u ON so.idUtilisateur = u.idUtilisateur
                LEFT JOIN categoriespectacle cs ON s.categorie = cs.idCategorie
                WHERE s.idSpectacle = :idSpectacle ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":idSpectacle", $idSpectacle);

        $stmt->execute();

        $spectacle = $stmt->fetch();

        return $spectacle;
    }
}