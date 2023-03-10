<?php

class User extends Database {
    public function __construct() {
        parent::getInstance('Users');
    }

    public function select($id) {
        return parent::select($id);
    }

    public function selectAll() {
        return parent::selectAll();
    }

    public function insert($data) {
        return parent::insert($data);
    }

    public function update($id, $data) {
        return parent::update($id, $data);
    }

    public function delete($id) {
        return parent::delete($id);
    }

    public function login($data)
    {
        $sql = "SELECT * FROM `Users` WHERE username = :username AND password = :password";
        $stmt = parent::getPDO()->prepare($sql);
        $stmt->execute(array(
            ':username' => $data['username'],
            ':password' => $data['password']
        ));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $_SESSION['user'] = $result;
            return true;
        } else {
            return false;
        }
    }
}