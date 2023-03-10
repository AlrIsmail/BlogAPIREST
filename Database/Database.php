<?php

class Database {
    
        private $db;
        private static array $instance = [];
        private $table;
        private function __construct($table) {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->table = $table;
        }
    
        public static function getInstance($table) {
            if (!isset(self::$instance[$table])) {
                self::$instance[$table] = new Database($table);
            }
            return self::$instance[$table];
        }

        public function getPDO() {
            return $this->db;
        }

        // select
        public function select($id) {
            $sql = "SELECT * FROM $this->table WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // selectAll
        public function selectAll() {
            $sql = "SELECT * FROM $this->table";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // insert
        public function insert($data) {
            $sql = "INSERT INTO $this->table (";
            foreach ($data as $key => $value) {
                $sql .= $key . ', ';
            }
            $sql = substr($sql, 0, -2);
            $sql .= ") VALUES (";
            foreach ($data as $key => $value) {
                $sql .= ':' . $key . ', ';
            }
            $sql = substr($sql, 0, -2);
            $sql .= ")";
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->execute();
            return $this->db->lastInsertId();
        }

        // update
        public function update($id, $data) {
            $sql = "UPDATE $this->table SET ";
            foreach ($data as $key => $value) {
                $sql .= $key . ' = :' . $key . ', ';
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        }

        // delete
        public function delete($id) {
            $sql = "DELETE FROM $this->table WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        }
    
}