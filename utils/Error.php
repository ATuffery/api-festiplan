<?php

namespace ApiFestiplan\utils;

use ApiFestiplan\mvc\View;

class Error {

    /**
     * Redirect to an error page
     * @param int $code the error code
     * @param string $msg the error message
     */
    public static function err(int $code, string $msg): void {
        $view = new View("api");
        $view->setVar("http_code", $code);
        $view->setVar("json", array('error' => htmlspecialchars($msg)));
        $view->render();
        exit;
    }

}