<?php
namespace Core;
use Core\DB;

class Model {
    protected $db;
    protected $table;
    protected $modelName;
    protected $softDelete = false;
    protected array $columnNames = [];
    public $id;

    public function __construct($table){
        $this->db = DB::getInstance();
        $this->table = $table;
        $this->modelName = str_replace(' ','',ucwords(str_replace('_',' ',$this->table)));
    }

    public function get_columns() {
        return $this->db->get_columns($this->table);
    }

    public function find($params=[]) {
        $results = [];
        $resultsQuery = $this->db->find($this->table, $params);
        foreach ($resultsQuery as $result) {
            $obj = new $this->modelName($this->table);
            $obj->populateObjData($result);
            $results[] = $obj;
        }
        return $results;
    }


    public function save($params) {
        $fields = $params;
        //update or insert?
        if(property_exists($this, 'id') && $this->id != '') {
            $save = $this->update($this->id, $fields);
            return $save;
        } else {
            $save = $this->insert($fields);
            return $save;
        }
    }

    public function insert($fields) {
        if(empty($fields)) return false;
        return $this->db->insert($this->table, $fields);
    }

    public function update($id, $fields) {
        if(empty($fiels) or $id == '') return false;
        return $this->db->update($this->table, $id, $fields);
    }

    public function delete($id = '') {
        if($id == '' && $this->id == '') return false;
        $id = ($id == '')? $this->id : $id;
        if($this->softDelete) {
            return $this->update($id, ['deleted' => 1]);
        }
        return $this->db->delete($this->table, $id);
    }

    public function query($sql, $bind = []) {
        return $this->db->query($sql, $bind);
    }

    public function data() {
        $data = new stdClass();
        foreach($this->columnNames as $column) {
            $data->column = $this->column;
        }
        return $data;
    }

    public function assign($params) {
        if(!empty($params)) {
            foreach($params as $key=>$value) {
                if(in_array($key, $this->columnNames)) {
                    $this->$key = sanitize($value);
                }
            }
            return true;
        }
        return false;
    }

    protected function populateObjData($result) {
        foreach ($result as $key => $value) {
            $this->$key = $value;
        }
    }
}
