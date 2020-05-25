<?php
namespace App\Library;

class DB extends \PDO
{
    public function __construct()
    {
        $dbConfig = Config::get('db');
        parent::__construct('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['db'] . ';charset=utf8mb4', $dbConfig['user'], $dbConfig['password']);
    }
}
