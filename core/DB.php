<?php
namespace Core;
use \PDO;
use Core\DatabaseConnection;

class DB {
    private static $instance = null;
    private $connection;
    private $query;
    private $pdo;
    private $error = false;
    private $result;
    private int $count = 0;
    private $lastInsertID = null;


    private function __construct(DatabaseConnection $connect) {
        $this->connection = $connect;
        $this->pdo = $this->connection->getPDO();
    }

    public static function getInstance() {
        if(!isset(self::$instance)) {
            $connect = new DatabaseConnection();
            self::$instance = new self($connect);
        }
        return self::$instance;
    }

    public function query($sql, $params = [],$class = false) {
        $this->error = false;
        if($this->query = $this->pdo->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->query->bindValue($x, $param);
                    $x++;
                }
            }
            if($this->query->execute()) {
                if($class){
                    $this->result = $this->query->fetchAll(PDO::FETCH_CLASS,$class);
                } else {
                    $this->result = $this->query->fetchALL(PDO::FETCH_OBJ);
                }
                $this->count = $this->query->rowCount();
                $this->lastInsertID = $this->pdo->lastInsertId();
            } else {
                $this->error = true;
            }
        }
        return $this;
    }

    public function get() {
        return $this->result;
    }

    public function insert($table, $fields = []) {
        $fieldString = '';
        $valueString = '';
        $values = [];

        foreach($fields as $field => $value) {
            $fieldString .= '`' . $field . '`,';
            $valueString .= '?,';
            $values[] = $value;
        }
        $fieldString = rtrim($fieldString, ',');
        $valueString = rtrim($valueString, ',');
        $sql = "INSERT INTO {$table} ({$fieldString}) VALUES ({$valueString})";
        if(!$this->query($sql, $values)->error()) {
            return true;
        }
        return false;
    }

    public function update($table, $id, $fields = []) {
        $fieldString = '';
        $values = [];
        foreach($fields as $field => $value) {
            $fieldString .= ' ' . $field . ' = ?,';
            $values[] = $value;
        }
        $fieldString = trim($fieldString);
        $fieldString = rtrim($fieldString, ',');
        $sql = "UPDATE {$table} SET {$fieldString} WHERE id = {$id}";
        if(!$this->query($sql, $values)->error()) {
            return true;
        }
        return false;
    }

    public function delete($table, $id) {
        $sql = "DELETE FROM {$table} WHERE id = {$id}";
        if(!$this->query($sql)->error()) {
            return true;
        }
        return false;
    }

    protected function read($table, $params=[], $class) {
        $conditionString = '';
        $bind = [];
        $order = '';
        $limit = '';

        // conditions
        if(isset($params['conditions'])) {
            if(is_array($params['conditions'])) {
                foreach($params['conditions'] as $condition) {
                    $conditionString .= ' ' . $condition . ' AND';
                }
                $conditionString = trim($conditionString);
                $conditionString = rtrim($conditionString, ' AND');
            } else {
                $conditionString = $params['conditions'];
            }
            if($conditionString != '') {
                $conditionString = ' Where ' . $conditionString;
            }
        }

        // bind
        if(array_key_exists('bind', $params)) {
            $bind = $params['bind'];
        }

        // order
        if(array_key_exists('order', $params)) {
            $order = ' ORDER BY ' . $params['order'];
        }

        // limit
        if(array_key_exists('limit', $params)) {
            $limit = ' LIMIT ' . $params['limit'];
        }
        $sql = "SELECT * FROM {$table}{$conditionString}{$order}{$limit}";
        if($this->query($sql, $bind,$class)) {
            if(!count($this->result)) return false;
            return true;
        }
        return false;
    }

    public function find($table, $params=[],$class=false) {
        if($this->read($table, $params, $class)) {
            return $this->get();
        }
        return false;
    }

    public function get_columns($table) {
        return $this->query("SHOW COLUMNS FROM {$table}")->get();
    }
}