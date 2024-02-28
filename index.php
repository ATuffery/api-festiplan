<?php

require_once 'vendor/autoload.php';
require_once 'Autoloader.php';

use ApiFestiplan\mvc\Router;
use ApiFestiplan\mvc\DataBase;
use ApiFestiplan\utils\Error;

Autoloader::autoload();

$db = new DataBase(
    "localhost",
    3306,
    "",
    "root",
    "",
    "utf8mb4"
);

try {
    Router::route($db);
} catch (\Error | \Exception $e) {
    Error::err(500, "Une erreur est survenue.");
}
