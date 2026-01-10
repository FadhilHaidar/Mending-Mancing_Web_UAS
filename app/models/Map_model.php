<?php

class Map_model {
    private $table = 'maps';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllMaps() {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getMapById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }
}