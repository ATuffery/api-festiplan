<?php
// cls; & ./vendor/bin/phpunit --coverage-html='reports/coverage'

require __DIR__ . "/../../mvc/DataBase.php";
require __DIR__ . "/../../services/AuthService.php";
require __DIR__ . "/../../services/ConsultService.php";
require __DIR__ . "/../../services/FavoriService.php";

use PHPUnit\Framework\TestCase;

final class SetupTest extends TestCase
{
    
    public function test(): void {
        $this->assertEquals(true, true);
    }

}
