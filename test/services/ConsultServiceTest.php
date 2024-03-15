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
            $result = $this->consultService::consultListFestival($this->pdo);
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
            $result = $this->consultService::consultListFestival($this->pdo);
            // Then
            $this->assertEquals(0,count($result));
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible", $e);
        }
    }

    public function testConsultListFavoriteFestivalFullTable(){

    }

    public function testConsultListFavoriteFestivalEmptyTableFavori(){

    }

    public function testConsultListFavoriteFestivalEmptyTableFavori(){

    }

}
