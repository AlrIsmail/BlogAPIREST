<?php
/*
include_once 'Database.php';
include_once '../Config/Register.php';
*/
class User extends Database {
    private $db = null;
    private $table = 'Users';
    public function __construct() {
        $this->db = parent::getInstance();
    }

    public function selectUser($id){
        $data = array(
            'IdUser' => $id
        );
        return $this->db->selectWhere($this->table, $data);
    }
    
    public function selectUserPass($username, $password){
        $data = array(
            'UserName' => $username,
            'Password' => $password
        );
        return $this->db->selectWhere($this->table, $data);

    }

    public function insertUser($username, $password, $role){
        $data = array(
            'UserName' => $username,
            'Password' => $password,
            'Role' => $role
        );
        return $this->db->insert($this->table, $data);
    }

    /*
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
    }*/
}