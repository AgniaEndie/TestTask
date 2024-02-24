<?php
namespace core\database;

use mysqli;

class DatabaseManager
{
    public mysqli|false $connection;
    private array|false $config;

    public function __construct()
    {
        $this->config = parse_ini_file("config.ini");
        $this->connection = mysqli_connect($this->config['host'],$this->config['user'],$this->config['password'],$this->config['base']);
    }


}