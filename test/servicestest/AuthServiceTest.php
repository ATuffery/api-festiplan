<?php

namespace servicestest;

use ApiFestiplan\mvc\DataBase;
use PHPUnit\Framework\TestCase;
use services\AuthService;

// require "../../mvc/DataBase.php";
// require "../../services/AuthService.php";

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

        // Préparation des objets mock pour simuler la base de données
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->stmtMock = $this->createMock(\PDOStatement::class);
        
        // Configuration pour que PDO utilise le PDOStatement mocké
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
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

    public function testGetUserIdWithExistingApiKey()
    {
        // Given: Une API key existante et un environnement mocké attendu pour retourner un utilisateur spécifique
        $apiKey = "existingApiKey";
        $userIdExpected = 1;
        $this->stmtMock->method('execute');
        $this->stmtMock->method('fetch')->willReturn(['idUtilisateur' => $userIdExpected]);
        $this->stmtMock->method('rowCount')->willReturn(1);

        // When: La méthode getUserId est appelée avec une API key existante
        $userId = AuthService::getUserId($this->pdoMock, $apiKey);
        
        // Then: L'ID de l'utilisateur attendu est retourné
        $this->assertEquals($userIdExpected, $userId);
    }

    public function testGetUserIdWithNonExistingApiKey()
    {
        // Given: Une API key qui n'existe pas
        $apiKey = "nonExistingApiKey";
        $this->stmtMock->method('execute');
        $this->stmtMock->method('fetch')->willReturn(false);
        $this->stmtMock->method('rowCount')->willReturn(0);

        // When: La méthode getUserId est appelée avec une API key inexistante
        $userId = AuthService::getUserId($this->pdoMock, $apiKey);
        
        // Then: null est retourné car aucun utilisateur n'est associé à l'API key
        $this->assertNull($userId);
    }

    public function testGetUserIdThrowsPDOException()
    {
        // Given: Une API key qui provoque une exception PDO lors de la requête
        $apiKey = "apiKeyCausingException";
        $this->stmtMock->method('execute')->will($this->throwException(new \PDOException()));

        // When: La méthode getUserId est appelée et provoque une exception
        $userId = AuthService::getUserId($this->pdoMock, $apiKey);
        
        // Then: null est retourné, indiquant une gestion correcte de l'exception
        $this->assertNull($userId);
    }
}
