<?php
namespace Core;
use \PDO;
use \PDOException;
use Core\H;
use Core\DatabaseConnection;

class DB {
    private static $_instance = null;
    private $_query, $pdo, $_error = false, $_result, $_count = 0, $_lastInsertID = null;

    private function __construct(DatabaseConnection $connection) {
        $this->pdo = $connection->pdo;
        H::dnd($this->pdo);
    }

    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}