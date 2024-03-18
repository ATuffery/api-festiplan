<?php

namespace services;

require "../../mvc/DataBase.php";
require "../../services/ConsultService.php";

use ApiFestiplan\mvc\DataBase;
use PHPUnit\Framework\TestCase;
use services\ConsultService;

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
            $sql = "DELETE FROM festival WHERE idFestival > 10";
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
            // Given une base de données connectée avec aucun festival
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

    public function testDetailsFestivalFullTable(){
        try {
            $this->pdo->beginTransaction();
            // Given une base de données connectée avec aucun festival
            $sql = "INSERT INTO festival VALUES (1,1,'Festival test','description','2024-03-18'202024-03-19','aaaa');
                    INSERT INTO utilisateur VALUES (3,'test','test','test@test','test','test','ecef9bc7c00bac56615da543155fed2f20ad2eabfcf6882e4eda85ffedb76921216485');
                    INSERT INTO equipeorganisatrice ";
            $this->pdo->query($sql);
            // When on affiche tout les festival
            $result = $this->consultService::detailsFestival($this->pdo, 1);
            // Then aucun festival ne s'afiche
            $this->assertEquals(0,count($result));
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible", $e);
        }

    }

}
