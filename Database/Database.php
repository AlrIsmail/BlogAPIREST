<?php

class Database
{

    private PDO $db;
    private static $instance = null;

    private function __construct()
    {
        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPDO(): PDO
    {
        return $this->db;
    }

    public function selectWhere($table, $data)
    {
        $sql = "SELECT * FROM $table WHERE ";
        foreach ($data as $key => $value) {
            $sql .= $key . ' = :' . $key . ' AND ';
        }
        $sql = substr($sql, 0, -5);
        $stmt = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     // insert
     public function insert($table, $data)
     {
         $columns = implode(', ', array_keys($data));
         $values = ':' . implode(', :', array_keys($data));
 
         $sql = "INSERT INTO {$table} ($columns) VALUES ($values)";
 
         $stmt = $this->db->prepare($sql);
         $stmt->execute($data);
 
         return $this->db->lastInsertId();
     }

    // update
    public function update($table, $data, $where)
    {
        $sql = "UPDATE {$table} SET ";
        foreach ($data as $key => $value) {
            $sql .= $key . ' = :' . $key . ', ';
        }
        $sql = substr($sql, 0, -2);
        $sql .= ' WHERE ' . $where;
        $stmt = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    // delete
    public function delete($table, $data)
    {
        $sql = "DELETE FROM {$table} WHERE ";
        foreach ($data as $key => $value) {
            $sql .= $key . ' = :' . $key . ' AND ';
        }
        $sql = substr($sql, 0, -5);
        $stmt = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }
}