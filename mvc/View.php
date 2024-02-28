<?php

namespace ApiFestiplan\mvc;

use ApiFestiplan\utils\Error;

class View {

    private string $path;

    private array $vars;

    /**
     * Create a View with its path
     * @param string $path the relative path of the view file
     */
    public function __construct(string $path) {
        $path = "views/" . $path . ".php";

        if (!file_exists($path)) {
            throw new \Exception("The view does not exist.");
        }

        $this->path = $path;
        $this->vars = array();
    }

    /**
     * Create a variable that can be used in the view
     * @param string $var_name the name of the variable
     * @param mixed $var_val the value of the variable
     */
    public function setVar(string $var_name, mixed $var_val):void {
        $this->vars[$var_name] = $var_val;
    }

    /**
     * Display the view
     */
    public function render():void {
        extract($this->vars);
        require_once $this->path;
    }

    /**
     * Check if a View exists thanks to its path
     * @param string $path the path of the view file
     * @return bool true if the view exists, false otherwise
     */
    public static function exists(string $path):bool {
        return file_exists("views/" . $path);
    }

    /**
     * Get the path of the view
     * @return string the path of the view
     */
    public function getPath():string
    {
        return $this->path;
    }
}