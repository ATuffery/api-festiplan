<?php

/**
 * Manage automatic import of classes
 * with their namespaces.
 */
class Autoloader {

    /**
     * Define the __autoload function on 
     * Autoloader::festiplan-api()
     */
    public static function autoload() {
        spl_autoload_register(array(__CLASS__, "festiplan_api_autoload"));
    }

    /**
     * Manages autoload by including files
     * with their path defined in the namespace 
     */
    private static function festiplan_api_autoload($namespace) {
        $path = str_replace("\\","/", $namespace);
        $path = str_replace("ApiFestiplan", "", $path);
        $path = $path[0] == "/" ? substr($path, 1) : $path;
        require $path . ".php";
    }

}
