<?php

namespace servicestest;

require "../../mvc/DataBase.php";
require "../../services/ConsultService.php";
require "../../services/AuthService.php";

use ApiFestiplan\mvc\DataBase;
use PHPUnit\Framework\TestCase;
use services\ConsultService;
use services\AuthService;


class ConsultServiceTest extends TestCase
{
    private \PDO $pdo;
    private ConsultService $consultService;

    public function setUp(): void
    {
        parent::setUp();
        // given a pdo for tests
        $db = new DataBase(
            "localhost",
            3306,
            "festiplanv2",
            "root",
            "root",
            "utf8mb4"
        );
        $this->pdo = $db->__connect();
        // and a consult service
        $this->consultService = new ConsultService();
    }

    public function testConsultAllFestivalsFullTable(){
        try {
            $this->pdo->beginTransaction();
            // Given une base de données connectée avec plusieurs festivals
            // When on affiche tout les festival
            $result = $this->consultService::consultListFestival($this->pdo, "a42798bf979c69734c8f83c708610bca824dfc4e44f5c84cca72999bddf0f5725ba276");

            // Then les festivals s'affiche dans l'ordre de représentation
            // (les festivals à venir les plus proches en premier, suivis de ceux qui auront lieu plus tard)
            $premiereLigne = $result[0];
            $dateDebut = $premiereLigne["dateDebut"];
            $isOrdered = true;
            foreach ($result as $festival){
                if(strtotime($dateDebut) <= strtotime($festival["dateDebut"])){
                    $dateDebut = $festival["dateDebut"];
                } else {
                    $isOrdered = false;
                }
            }
            $this->assertTrue($isOrdered);
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }
    }

    public function testConsultAllFestivalsEmptyTable(){
        try {
            $this->pdo->beginTransaction();
            // Given une base de données connectée avec aucun festival
            $sql = "DELETE FROM festival WHERE idFestival > 0";
            $this->pdo->query($sql);

            // When on affiche tout les festival
            $result = $this->consultService::consultListFestival($this->pdo, "a42798bf979c69734c8f83c708610bca824dfc4e44f5c84cca72999bddf0f5725ba276");

            // Then aucun festival ne s'afiche
            $this->assertEquals(0,count($result));
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible", $e);
        }
    }

    public function testConsultListFavoriteFestivalFullTable(){
        try {
            $this->pdo->beginTransaction();
            // Given une base de données connectée avec plusieurs festivals et un utilisateur avec 2 festival en favori
            $sql = "INSERT INTO favori (idUtilisateur, idFestival) VALUES (4, 11), (4, 12)";
            $this->pdo->query($sql);

            // When on affiche tout les festival
            $result = $this->consultService::consultListFavoriteFestival($this->pdo, 4);

            // Then les 2 festivals s'affiche dans l'ordre de représentation
            // (les festivals à venir les plus proches en premier, suivis de ceux qui auront lieu plus tard)
            $this->assertEquals(2,count($result));
            $premiereLigne = $result[0];
            $dateDebut = $premiereLigne["dateDebut"];
            $isOrdered = true;
            foreach ($result as $festival){
                if(strtotime($dateDebut) <= strtotime($festival["dateDebut"])){
                    $dateDebut = $festival["dateDebut"];
                } else {
                    $isOrdered = false;
                }
            }
            $this->assertTrue($isOrdered);
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }
    }

    public function testConsultListFavoriteFestivalEmptyTableFavori(){
        try {
            $this->pdo->beginTransaction();
            // Given une base de données connectée avec aucun festival festival en favori pour un utilisateur donnée
            $sql = "DELETE FROM favori WHERE favori.idUtilisateur = 4";
            $this->pdo->query($sql);

            // When on affiche tout les festival
            $result = $this->consultService::consultListFavoriteFestival($this->pdo, 4);

            // Then aucun festival ne s'afiche
            $this->assertEquals(0,count($result));
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible", $e);
        }
    }

    public function testConsultListFavoriteFestivalEmptyTableFestival(){
        try {
            $this->pdo->beginTransaction();
            // Given une base de données connectée avec aucun festival
            $sql = "DELETE FROM festival WHERE idFestival > 10";
            $this->pdo->query($sql);

            // When on affiche tout les festival
            $result = $this->consultService::consultListFavoriteFestival($this->pdo, 4);

            // Then aucun festival ne s'afiche
            $this->assertEquals(0,count($result));
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible", $e);
        }
    }


    //
    public function testDetailsFestivalFullTable(){
        try {
            $this->pdo->beginTransaction();
            // Given une base de données connectée avec un festival
            $sql = "INSERT INTO festival VALUES (2,1,'Festival test','description','2024-03-18', '2024-03-19','aaaa');
                    INSERT INTO utilisateur(idUtilisateur, prenom, nom, mail, login, mdp, apiKey) VALUES (6, 'prenom6','nom6','test6@test','test6','test6','165845qzfde6zr4fs3qf4e8gy4e6gsrth84f6gjnrtj7eyh85s6v4d6fh7tyu76tdgb458');
                    INSERT INTO utilisateur(idUtilisateur, prenom, nom, mail, login, mdp, apiKey) VALUES (7, 'prenom7','nom7','test7@test','test7','test7','dze3s5et777777rh41bf5rtys6er87uhd63v1dr7hrte89dg64s6dwv4t8uhyt161dzerf');
                    INSERT INTO equipeorganisatrice VALUES (6, 2, 1), (7, 2, 0);
                    INSERT INTO spectacle VALUES (1, 'titreSpectacle1', 'descriptionSpectacle1', '00:20:00', 'aaa', 1, 1),
                                                 (2, 'titreSpectacle2', 'descriptionSpectacle2', '00:20:00', 'aaa', 2, 2);
                    INSERT INTO spectacledefestival VALUES (1, 2), (2, 2)";
            $this->pdo->exec($sql);

            // When on affiche les details du festival
            $result = $this->consultService::detailsFestival($this->pdo, 2);

            // Then le details s'affiche
            $resultat = array(
                'festival' => array(
                    'idFestival' => '2',
                    'categorie' => 'Musique',
                    'titre' => 'Festival test',
                    'description' => 'description',
                    'dateDebut' => '2024-03-18',
                    'dateFin' => '2024-03-19',
                    'illustration' => 'aaaa'
                ),
                'equipe_organisatrice' => array(
                    0 => Array (
                        'responsable' => '1',
                        'prenom' => 'prenom6',
                        'nom' => 'nom6'
                    ),
                    1 => Array (
                        'responsable' => '0',
                        'prenom' => 'prenom7',
                        'nom' => 'nom7'
                    )
                ),
                'spectacles' => Array (
                    0 => Array (
                        'idSpectacle' => '1',
                        'titreSpectacle' => 'titreSpectacle1',
                        'descriptionSpectacle' => 'descriptionSpectacle1',
                        'dureeSpectacle' => '00:20:00',
                        'illustrationSpectacle' => 'aaa',
                        'categorieSpectacle' => 'Concert' // Cette valeur doit correspondre à celle de votre base de données
                    ),
                    1 => Array (
                        'idSpectacle' => '2',
                        'titreSpectacle' => 'titreSpectacle2',
                        'descriptionSpectacle' => 'descriptionSpectacle2',
                        'dureeSpectacle' => '00:20:00',
                        'illustrationSpectacle' => 'aaa',
                        'categorieSpectacle' => 'Piece de theatre' // Cette valeur doit correspondre à celle de votre base de données
                    )
                )
            );

            self::assertEquals($resultat, $result);
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            echo $e;
            $this->fail("La base de données n'est pas accessible", $e);
        }

    }

}

// Erreur lors de l'exécution de la requête SQL : SQLSTATE[42000]: Syntax error or access violation: 1064 You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'INSERT INTO utilisateur(idUtilisateur, prenom, nom, mail, login, mdp, apiKey) VA' at line 2


//{"festival":{"idFestival":1,"categorie":"Musique","titre":"Festival test","description":"description","dateDebut":"2024-03-18","dateFin":"2024-03-19","illustration":"aaaa"},
//"equipe_organisatrice":[{"responsable":1,"prenom":"prenom3","nom":"nom3"},
//                        {"responsable":0,"prenom":"oui","nom":"oui"}],
//"spectacles":[{"idSpectacle":10,"titreSpectacle":"titreSpectacle1","descriptionSpectacle":"descriptionSpectacle1","dureeSpectacle":"00:20:00","illustrationSpectacle":"aaa","categorieSpectacle":"Concert"},
//              {"idSpectacle":11,"titreSpectacle":"titreSpectacle2","descriptionSpectacle":"descriptionSpectacle2","dureeSpectacle":"00:20:00","illustrationSpectacle":"aaa","categorieSpectacle":"Piece de theatre"}]}


//Array (
//     'festival' => Array (
//-        'idFestival' => '2'
//+        'idFestival' => 1
//         'categorie' => 'Musique'
//         'titre' => 'Festival test'
//         'description' => 'description'
//@@ @@
//     )
//     'equipe_organisatrice' => Array (
//         0 => Array (
//-            'responsable' => '1'
//-            'prenom' => 'prenom6'
//-            'nom' => 'nom6'
//+            'responsable' => 1
//+            'prenom' => 'prenom3'
//+            'nom' => 'nom3'
//         )
//         1 => Array (
//-            'responsable' => '0'
//-            'prenom' => 'prenom7'
//-            'nom' => 'nom7'
//+            'responsable' => 0
//+            'prenom' => 'oui'
//+            'nom' => 'oui'
//         )
//     )
//     'spectacles' => [...]
// )