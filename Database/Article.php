<?php

class Article extends Database{

    public function __construct(){
        self::getInstance('Articles');
    }

    public function select($id){
        $pdo = $this->getPDO();
        $sql = "SELECT * FROM Articles WHERE Id = :Id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function selectAll(){
        $pdo = $this->getPDO();
        return $pdo->query("SELECT * FROM Articles")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data): void
    {
        $pdo = $this->getPDO();
        $sql = "INSERT INTO Articles (Title, Content, DateModif, DatePub, IdUser) VALUES (:Title, :Content, :DateModif, :DatePub, :IdUser)";
        $stmt = $this->bindParams($pdo, $sql, $data);
        $stmt->execute();
    }

    public function update($id, $data): void
    {
        $pdo = $this->getPDO();
        $sql = "UPDATE Articles SET Title = :Title, Content = :Content, DateModif = :DateModif, DatePub = :DatePub, IdUser = :IdUser WHERE IdUser = :Id";
        $stmt = $this->bindParams($pdo, $sql, $data);
        $stmt->bindValue(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete($id){
        $pdo = $this->getPDO();
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