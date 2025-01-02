<?php

namespace Cyouliao\Goaleval;

use PDO;
use PDOException;

class DBConnect extends PDO
{
    private $dsn;
    private $host;
    private $dbname;
    private $charset;
    private $username;
    private $password;
    private $options;

    public function __construct()
    {
        $this->dbname = 'qsteense_goaleval';
        $this->username = 'qsteense_goaleval_script';
        $this->password = 'x1ycl_j7aN0Ck[kV';
        $this->host = 'localhost';
        $this->charset = 'UTF8';
        $this->dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset";
        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ];

        try {
            parent::__construct($this->dsn, $this->username, $this->password, $this->options);

        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function getInstance(): DBConnect
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new DBConnect();
        }
        return $instance;
    }
}