<?php

namespace controllerstest;

use ApiFestiplan\controllers\FestivalController;
use PHPUnit\Framework\TestCase;

require "../../controllers/FestivalController.php";

class FestivalControllerTest extends TestCase
{
    private string $apiKey;
    private FestivalController $festivalController;
    private \PDO $pdo;
    private \PDOStatement $stmt;

    public function setUp(): void
    {
        parent::setUp();
        // Given un controller de festival et une api key
        $this->festivalController = new FestivalController();
        $this->apiKey = "a42798bf979c69734c8f83c708610bca824dfc4e44f5c84cca72999bddf0f5725ba276";

        // and a pdo for tests
        $this->pdo = $this->createStub(\PDO::class);
        $this->stmt = $this->createStub(\PDOStatement::class);
    }

    public function testAll()
    {
        // Lorsque on demande tout les festivals
        $result = $this->festivalController->all($this->pdo, $this->apiKey);
        // Alors on reçoit une vue avec tout les festivals
        $this->assertNotNull($result);
    }

    public function testListFavoriteFestival()
    {
        // Lorsque on demande tout les festivals favoris
        $result = $this->festivalController->listFavoriteFestival($this->pdo);
        // Alors on reçoit une vue avec tout les festivals favoris
        $this->assertNotNull($result);

    }

    public function testConnexion()
    {
        // Lorsque on s'authentifie avec un bon login et mot de passe
        $result = $this->festivalController->connexion($this->pdo, "oui", "oui");
        // Alors on reçoit un token (l'api key) qui permettra d'accéder à l'application
        $this->assertNotNull($result);
    }

    public function testDetailsFestival()
    {
        // Lorsque on demande les détails d'un festival
        $result = $this->festivalController->detailsFestival($this->pdo, 1);
        // Alors on reçoit une vue avec les détails du festival
        $this->assertNotNull($result);
    }

    public function testDetailsShow()
    {
        // Lorsque on demande les détails d'un show
        $result = $this->festivalController->detailsShow($this->pdo, 1);
        // Alors on reçoit une vue avec les détails du show
        $this->assertNotNull($result);
    }

    public function testRemove_fav()
    {
        // Lorsque on supprime un festival des favoris
        $result = $this->festivalController->remove_fav($this->pdo, 1);
        // Alors on reçoit une vue avec les détails du festival
        $this->assertNotNull($result);
    }

    public function testAdd_to_fav()
    {
        // Lorsque on ajoute un festival aux favoris
        $result = $this->festivalController->add_to_fav($this->pdo, 1);
        // Alors on reçoit une vue avec les détails du festival
        $this->assertNotNull($result);
    }
}
