<?php

include_once DATABASE_PATH . "Article.php";
include_once DATABASE_PATH . "Evaluate.php";


class Articles{

    /**
     * @var int|mixed|null
     */
    public $IdArticle;

    /**
     * @var int|mixed|null
     */
    public $IdUser;
    /**
     * @var mixed
     */
    public $Title;
    /**
     * @var mixed
     */
    public $Content;
    /**
     * @var false|mixed|string
     */
    public $DateCreated;
    /**
     * @var false|mixed|string
     */
    public $DateModified;
    /**
     * @var int|mixed
     */
    public $Likes;
    /**
     * @var int|mixed
     */
    public $Dislikes;
    /**
     * @var array|mixed
     */
    public $ListLikes;
    /**
     * @var array|mixed
     */
    public $ListDislikes;
    private $dao = null;

    private $listArticles = array();

    private $errorMessage = null;

    public function __construct(){
        $this->dao = new Article();
    }

    public function getAll()
    {
        $liste = $this->dao->selectAll();
        foreach($liste as $article){
            $temp = new Articles();
            $temp->IdArticle = $article['IdArticle'];
            $temp->IdUser = $article['IdUser'];
            $temp->Title = $article['Title'];
            $temp->Content = $article['Content'];
            $temp->DateCreated = $article['DatePub'];
            $temp->DateModified = $article['DateModif'];
            $result = $this->getUsersLiked($temp->IdArticle);
            $temp->Likes = count($result);
            $temp->ListLikes = $result;
            $result = $this->getUsersDisliked($temp->IdArticle);
            $temp->Dislikes = count($result);
            $temp->ListDislikes = $result;
            $this->listArticles[] = $temp;
        }
        return $this->listArticles;
    }
    public function getUsersLiked($id)
    {
        $daoEvaluate = new Evaluate();
        $list = $daoEvaluate->getUsersLike($id);
        return $list;
    }
    public function getUsersDisliked($id)
    {
        $daoEvaluate = new Evaluate();
        $list = $daoEvaluate->getUsersDislike($id);
        return $list;
    }

    public function getById($id)
    {
        $liste = $this->dao->select($id);
        $temp = new Articles();
        $temp->IdArticle = $liste['IdArticle'];
        $temp->IdUser = $liste['IdUser'];
        $temp->Title = $liste['Title'];
        $temp->Content = $liste['Content'];
        $temp->DateCreated = $liste['DatePub'];
        $temp->DateModified = $liste['DateModif'];
        $result = $this->getUsersLiked($temp->IdArticle);
        $temp->Likes = count($result);
        $temp->ListLikes = $result;
        $result = $this->getUsersDisliked($temp->IdArticle);
        $temp->Dislikes = count($result);
        $temp->ListDislikes = $result;
        return $temp;

    }

    public function create()
    {
        // check the validity of the information before creating the article
        if(empty($this->Title) || empty($this->Content) || empty($this->IdUser) || !is_numeric($this->IdUser) || !is_string($this->Title) || !is_string($this->Content)){
            return -1;
        }
        if (empty($this->DateCreated)) {
            $this->DateCreated = date("Y-m-d H:i:s");
        }
        // sanitize the data
        $this->Title = htmlspecialchars($this->Title);
        $this->Content = htmlspecialchars($this->Content);
        $this->IdUser = (int)$this->IdUser;
        $this->DateModified = null;
        $this->Likes = 0;
        $this->Dislikes = 0;
        $this->ListLikes = array();
        $this->ListDislikes = array();
        $data = array(
            "IdUser" => $this->IdUser,
            "Title" => $this->Title,
            "Content" => $this->Content,
            "DateCreated" => $this->DateCreated,
            "DateModified" => $this->DateModified,
            // TODO : add the likes and dislikes ? or not ? because in the db it is not implemented
            /*"Likes" => $this->Likes,
            "Dislikes" => $this->Dislikes,
            "ListLikes" => $this->ListLikes,
            "ListDislikes" => $this->ListDislikes*/
        );
        try {
            $this->dao->insertArticle($data);
            return 1;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
    }

    public function update(){
        // Check the validity of the information before updating the article
        if(empty($this->Title) || empty($this->Content) || empty($this->IdUser) || !is_numeric($this->IdUser) || !is_string($this->Title) || !is_string($this->Content)){
            return -1;
        }
        if (empty($this->DateModified)) {
            $this->DateModified = date("Y-m-d H:i:s");
        }
        // sanitize the data
        $this->Title = htmlspecialchars($this->Title);
        $this->Content = htmlspecialchars($this->Content);
        $this->IdUser = (int)$this->IdUser;
        $data = array(
            "IdUser" => $this->IdUser,
            "Title" => $this->Title,
            "Content" => $this->Content,
            "DateModified" => $this->DateModified,
        );
        try {
            $this->dao->updateArticle($this->IdArticle,$data);
            return 1;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
    }

    public function delete(){
        try {
            $daoEvaluate = new Evaluate();
            $daoEvaluate->deleteAllVotes($this->IdArticle);
            $this->dao->deleteArticle($this->IdArticle);
            return 1;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
    }

    public function createVote(){
        $daoEvaluate = new Evaluate();
        try{
            if ($this->Likes == 1){
                $result = $daoEvaluate->createLike($this->IdArticle,$this->IdUser);
            } else {
                $result = $daoEvaluate->createDislike($this->IdArticle,$this->IdUser);
            }
            return $result;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
        
    }

    public function updateVote(){
        $daoEvaluate = new Evaluate();
        try{
            if ($this->Likes == 1){
                $result = $daoEvaluate->updateLike($this->IdArticle,$this->IdUser);
            } else {
                $result = $daoEvaluate->updateDislike($this->IdArticle,$this->IdUser);
            }
            return $result;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
    }

    public function deleteVote(){
        $daoEvaluate = new Evaluate();
        try{
            $result = $daoEvaluate->delete($this->IdArticle,$this->IdUser);
            return $result;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
    }



    public function getPostedArticle()
    {
        $idArticle = $this->dao->getPDO()->lastInsertId();
        return $this->dao->select($idArticle);
    }

    public function getModifiedArticle(){
        return $this->dao->select($this->IdArticle);
    }

    public function getErrorMessage(){
        // Retrieve the catched error from the create() function
        return $this->errorMessage;
    }
}