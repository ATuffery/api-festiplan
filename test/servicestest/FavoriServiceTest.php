<?php

namespace servicestest;

require "../../mvc/DataBase.php";
require "../../services/FavoriService.php";
require "../../services/AuthService.php";

use services\FavoriService;
use ApiFestiplan\mvc\DataBase;
use PHPUnit\Framework\TestCase;

class FavoriServiceTest extends TestCase
{
    private \PDO $pdo;

    public function setUp(): void
    {
        parent::setUp();

        $db = new DataBase(
            "localhost",
            3306,
            "festiplanv2",
            "root",
            "root",
            "utf8mb4"
        );
        $this->pdo = $db->__connect();

        $this->userIdTest = 4;
        $this->festivalIdTest = 11;
    }

    public function testAddFavori()
    {
        try {
            $this->pdo->beginTransaction();
            FavoriService::addFavori($this->pdo, $this->festivalIdTest, $this->userIdTest);

            $testQuery = "SELECT * FROM favori WHERE idUtilisateur = :idUtilisateur AND idFestival = :idFestival";
            $stmt = $this->pdo->prepare($testQuery);
            $stmt->bindParam(":idUtilisateur", $this->userIdTest);
            $stmt->bindParam(":idFestival", $this->festivalIdTest);
            $stmt->execute();

            $this->assertNotEmpty($stmt->fetch());

            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }

    }

    public function testGetUserId()
    {
        try {
            $this->pdo->beginTransaction();
            $result = FavoriService::getUserId($this->pdo, "a42798bf979c69734c8f83c708610bca824dfc4e44f5c84cca72999bddf0f5725ba276");
            $this->assertIsInt($result);
            $this->equalTo(4, $result);
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }

    }

    public function testRemoveFavoris()
    {
        try {
            $this->pdo->beginTransaction();
            FavoriService::addFavori($this->pdo, $this->festivalIdTest, $this->userIdTest);
            FavoriService::removeFavoris($this->pdo, $this->festivalIdTest, $this->userIdTest);

            $testQuery = "SELECT * FROM favori WHERE idUtilisateur = :idUtilisateur AND idFestival = :idFestival";
            $stmt = $this->pdo->prepare($testQuery);
            $stmt->bindParam(":idUtilisateur", $this->userIdTest);
            $stmt->bindParam(":idFestival", $this->festivalIdTest);
            $stmt->execute();

            $this->assertEmpty($stmt->fetch());

            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }

    }
}
