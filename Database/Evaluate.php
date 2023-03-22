<?php 

class Evaluate extends Database{

    private $db = null;
    private $table = 'Evaluate';
    public function __construct(){
        $this->db= parent::getInstance();
    }

    public function like($idArticle, $idUser){
        $data = array(
            'IdArticle' => $idArticle,
            'IdUser' => $idUser,
            'Like' => 1,
            'Dislike' => 0
        );
        return $this->db->insert($this->table, $data);
    }

    public function dislike($idArticle, $idUser){
        $data = array(
            'IdArticle' => $idArticle,
            'IdUser' => $idUser,
            'Liked' => 0,
            'Disliked' => 1
        );
        return $this->db->insert($this->table, $data);
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