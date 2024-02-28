<?php

require_once 'vendor/autoload.php';
require_once 'Autoloader.php';

use ApiFestiplan\mvc\Router;
use ApiFestiplan\mvc\DataBase;

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
} catch (Error | Exception $e) {
    echo $e;
    // redirect to error page
}
