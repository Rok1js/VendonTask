<?php

namespace Vendon\core;

use PDO;

class Database
{
    /**
     * @var PDO
     */
    public PDO $pdo;

    /**
     * @param $config
     */
    //vvv Establish connection with Database
    public function __construct($config)
    {
        $host = $config['mysql']['host'] ?? '';
        $port = $config['mysql']['port'] ?? '';
        $database = $config['mysql']['database'] ?? '';
        $username = $config['mysql']['username'] ?? '';
        $password = $config['mysql']['password'] ?? '';

        $dns = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $database .= ";charset=utf8mb4";

        $this->pdo = new PDO($dns, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}