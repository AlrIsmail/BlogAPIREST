<?php 

class Evaluate extends Database{

    private $db = null;
    private $table = 'Evaluate';
    public function __construct(){
        $this->db= parent::getInstance();
    }

    public function getEvaluate($idArticle, $idUser){
        $data = array(
            'IdArticle' => $idArticle,
            'IdUser' => $idUser
        );
        return $this->db->selectWhere($this->table, $data);
    }

    public function createLike($idArticle, $idUser){
        $data = array(
            'IdArticle' => $idArticle,
            'IdUser' => $idUser,
            'Liked' => 1,
            'Disliked' => 0
        );
        return $this->db->insert($this->table, $data);
    }

    public function createDislike($idArticle, $idUser){
        $data = array(
            'IdArticle' => $idArticle,
            'IdUser' => $idUser,
            'Liked' => 0,
            'Disliked' => 1
        );
        return $this->db->insert($this->table, $data);
    }

    public function updateLike($idArticle, $idUser){
       $data = array(
            'Liked' => 1,
            'Disliked' => 0
        );
        $where = "IdArticle = $idArticle AND IdUser = $idUser";
        return $this->db->update($this->table, $data, $where);
    }

    public function updateDislike($idArticle, $idUser){
        $data = array(
            'Liked' => 0,
            'Disliked' => 1
        );
        $where = "IdArticle = $idArticle AND IdUser = $idUser";
        return $this->db->update($this->table, $data, $where);
    }

    public function deleteVote($idArticle, $idUser){
        $data = array(
            'IdArticle' => $idArticle,
            'IdUser' => $idUser
        );
        return $this->db->delete($this->table, $data);
    }

    public function deleteAllVotes($idArticle){
        $data = array(
            'IdArticle' => $idArticle
        );
        return $this->db->delete($this->table, $data);
    }

    public function getUsersLike($idArticle){
        $data = array(
            'IdArticle' => $idArticle,
            'Liked' => 1
        );
        return $this->db->selectWhere($this->table, $data);
    }

    public function getUsersDislike($idArticle){
        $data = array(
            'IdArticle' => $idArticle,
            'Disliked' => 1
        );
        return $this->db->selectWhere($this->table, $data);
    }
}