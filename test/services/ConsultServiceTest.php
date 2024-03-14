<?php

namespace services;

use ApiFestiplan\mvc\DataBase;
use PHPUnit\Framework\TestCase;

class ConsultServiceTest extends TestCase
{
    private PDO $pdo;
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
        $this->pdo = $db->connexion();
        // and a consult service
        $this->consultService = new ConsultService();
    }

    public function consultAllFestivals(){
        try {
            $this->pdo->beginTransaction();
            // Given une base de données connectée avec plusieurs festivals
            // When on affiche tout les festival
            $result = $this->consultService::consultListFestival($this->pdo);
            // Then les festivals s'affiche dans l'ordre de représentation
            // (les festivals à venir les plus proches en premier, suivis de ceux qui auront lieu plus tard)
            $dateDebut = $result->fetch();
            foreach ($result as $festival){
                if(strtotime($dateDebut) )
            }
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }
    }
}
