<?php
namespace Core;
use \PDO;
use \PDOException;
use Core\H;

class DatabaseConnection {

    private string $host;
    private string $name;
    private string $username;
    private string $password;
    public $pdo;

    public function __construct(string $host, string $name, string $username, string $password) {
        $this->host = getenv('DB_HOST');
        $this->name = getenv('DB_NAME');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');

        try {
            $this->pdo = new PDO('mysql:host=' . $host . ';dbname=' . $name, $username, $password);
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