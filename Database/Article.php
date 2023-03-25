<?php

include_once DATABASE_PATH . "Database.php";

class Article extends Database{
    private $db = null;
    public function __construct(){
        $this->db = self::getInstance();
    }

    public function select($id){
        $pdo = $this->db->getPDO();
        $sql = "SELECT * FROM Articles WHERE IdArticle = :Id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return end($result);
    }

    public function selectAll(){
        $pdo = $this->db->getPDO();
        return $pdo->query("SELECT * FROM Articles")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertArticle($data): void
    {
        $pdo = $this->db->getPDO();
        $sql = "INSERT INTO Articles (Title, Content, DateModif, DatePub, IdUser) VALUES (:Title, :Content, :DateModif, :DatePub, :IdUser)";
        $stmt = $this->bindParams($pdo, $sql, $data);
        $stmt->execute();
    }

    public function updateArticle($id, $data): void
    {
        $pdo = $this->db->getPDO();
        $sql = "UPDATE Articles SET Title = :Title, Content = :Content, DateModif = :DateModif, DatePub = :DatePub, IdUser = :IdUser WHERE IdArticle = :Id";
        $stmt = $this->bindParams($pdo, $sql, $data);
        $stmt->bindValue(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteArticle($id){
        $pdo = $this->db->getPDO();
        $sql = "DELETE FROM Articles WHERE IdUser = :Id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * @param PDO $pdo
     * @param string $sql
     * @param $data
     * @return false|PDOStatement
     */
    public function bindParams(PDO $pdo, string $sql, $data)
    {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':Title', $data['Title']);
        $stmt->bindValue(':Content', $data['Content']);
        $stmt->bindValue(':DateModif', $data['DateModif']);
        $stmt->bindValue(':DatePub', $data['DatePub']);
        $stmt->bindValue(':IdUser', $data['IdUser'], PDO::PARAM_INT);
        return $stmt;
    }
}