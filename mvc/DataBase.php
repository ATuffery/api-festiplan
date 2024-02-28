<?php

namespace ApiFestiplan\mvc;

class DataBase {
    
    private string $host;
    private int $port;
    private string $db;
    private string $user;
    private string $password;
    private string $charset;
    
    /**
     * Create a DataBase
     *
     * @param string $host the host of the database
     * @param int $port the database port
     * @param string $db the name  of the database
     * @param string $user the login to connect to the database
     * @param string $password the password to connect to the database
     * @param string $charset the charset used by the database
     */
    public function __construct(string $host, int $port, string $db, string $user, string $password, string $charset) {
        $this->host = $host;
        $this->port = $port;
        $this->db = $db;
        $this->user = $user;
        $this->password = $password;
        $this->charset = $charset;
    }

    /**
     * Connect to the database
     * @return \PDO the connection to the database
     */
    public function __connect() {
        $ds_name = "mysql:host=$this->host;port=$this->port;dbname=$this->db;charset=$this->charset";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_PERSISTENT => true
        ];

        return new \PDO($ds_name, $this->user, $this->password, $options);
    }

}