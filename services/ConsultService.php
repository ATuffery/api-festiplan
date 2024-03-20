<?php

namespace services;

use services\AuthService;

class ConsultService {
    /**
     * Returns the list of all festivals
     * @param \PDO $pdo
     * @param int $user_id
     * @return array<array{idFestival:int, titre:string, categorie:string, description:string, dateDebut:string, dateFin:string, illustration:string}>|bool
     */
    public static function consultListFestival(\PDO $pdo, int $user_id): array|bool {

        $query = "SELECT f.idFestival, f.titre, cf.nom as categorie, f.description, f.dateDebut, f.dateFin, f.illustration, 
                EXISTS(SELECT * FROM favori WHERE idFestival = f.idFestival AND idUtilisateur = :user_id) as isFavorite
            FROM festival f
            INNER JOIN categoriefestival cf ON f.categorie = cf.idCategorie
            ORDER BY f.dateDebut";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":user_id", $user_id);

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
    public static function detailsFestival(\PDO $pdo, int $idFestival, int $userId): mixed {
        $query1 = "SELECT f.idFestival, cf.nom as categorie, f.titre, f.description, f.dateDebut, f.dateFin, f.illustration,
                EXISTS(SELECT * FROM favori WHERE idFestival = f.idFestival AND idUtilisateur = :user_id) as isFavorite
                FROM festival f
                INNER JOIN categoriefestival cf ON f.categorie = cf.idCategorie
                WHERE f.idFestival = :idFestival";

        $stmt1 = $pdo->prepare($query1);
        $stmt1->bindParam(":idFestival", $idFestival);
        $stmt1->bindParam(":user_id", $userId);
        $stmt1->execute();
        $festivalDetails = $stmt1->fetch();

        if (!$festivalDetails) {
            throw new \RuntimeException("Ce festival n'existe pas.");
        }

        $query2 = "SELECT eo.responsable, u.prenom, u.nom
                FROM equipeorganisatrice eo
                INNER JOIN festival f ON f.idFestival = eo.idFestival
                INNER JOIN utilisateur u ON eo.idUtilisateur = u.idUtilisateur 
                WHERE f.idFestival = :idFestival";

        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(":idFestival", $idFestival);
        $stmt2->execute();
        $equipeOrganisatrice = $stmt2->fetchAll();

        $query3 = "SELECT s.idSpectacle, s.titre as titreSpectacle, s.description as descriptionSpectacle, s.duree as dureeSpectacle, 
                s.illustration as illustrationSpectacle, cs.nomCategorie as categorieSpectacle
                FROM spectacle s
                INNER JOIN spectacledefestival sdf ON sdf.idSpectacle = s.idSpectacle
                INNER JOIN festival f ON f.idFestival = sdf.idFestival
                INNER JOIN categoriespectacle cs ON s.categorie = cs.idCategorie
                WHERE f.idFestival = :idFestival";

        $stmt3 = $pdo->prepare($query3);
        $stmt3->bindParam(":idFestival", $idFestival);
        $stmt3->execute();
        $spectacles = $stmt3->fetchAll();

        // Rassembler les résultats dans un tableau associatif
        $result = array(
            'festival' => $festivalDetails,
            'equipe_organisatrice' => $equipeOrganisatrice,
            'spectacles' => $spectacles
        );

        return $result;
    }
}