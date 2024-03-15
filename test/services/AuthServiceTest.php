<?php

namespace services;

use ApiFestiplan\mvc\DataBase;
use PHPUnit\Framework\TestCase;

require "../../mvc/DataBase.php";
require "../../services/AuthService.php";

class AuthServiceTest extends TestCase {
    private \PDO $pdo;
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
    }

    public function testAuthentification() {
        try {
            $this->pdo->beginTransaction();
            // When on s'authentifie avec un bon login et mot de passe
            $result = AuthService::connexion($this->pdo, "admin", "admin");
            // Then on reçoit un token (l'api key) qui permettra d'accéder à l'application
            $this->assertNotNull($result);
            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }
    }

    public function testAuthentificationWrongPassword() {
        try {
            $this->pdo->beginTransaction();
            // When on s'authentifie avec un bon login et un mauvais mot de passe
            $result = AuthService::connexion($this->pdo, "admin", "wrongpassword");
            // Then on reçoit une array vide
            $this->assertEmpty($result);

            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }
    }

    public function testAuthentificationWrongLogin() {
        try {
            $this->pdo->beginTransaction();
            // When on s'authentifie avec un mauvais login et un bon mot de passe
            $result = AuthService::connexion($this->pdo, "wronglogin", "admin");
            // Then on reçoit une array vide
            $this->assertEmpty($result);

            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }
    }

    public function testAddApiKey() {
        try {
            $this->pdo->beginTransaction();
            // Given un utilisateur
            $user = AuthService::connexion($this->pdo, "ouiTest", "ouiTest");
            // When on lui ajoute une api key
            $result = AuthService::addApiKey($this->pdo, $user["idUtilisateur"]);
            // Then on reçoit une api key
            $this->assertNotNull($result);

            $this->pdo->rollBack();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            $this->fail("La base de données n'est pas accessible");
        }
    }

}
