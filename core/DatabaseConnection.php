<?php
namespace Core;
use \PDO;
use \PDOException;

class DatabaseConnection {

    private string $host;
    private string $name;
    private string $username;
    private string $password;
    private $pdo;

    public function __construct() {
        $this->host = getenv('DB_HOST');
        $this->name = getenv('DB_NAME');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');

        try {
            $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->name, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getPDO() {
        return $this->pdo;
    }
}