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

    public function publishPostDataControl($data){
        if (empty($data['title']) || empty($data['content']) || !isset($data['dateCreated']) || !isset($data['dateModified'])) {
            $this->errorMessage = "Bad request";
            $this->errorMessage .= empty($data['title']) ? " title is missing" : "";
            $this->errorMessage .= empty($data['content']) ? " content is missing" : "";
            $this->errorMessage .= !isset($data['dateCreated']) ? " dateCreated is missing" : "";
            $this->errorMessage .= !isset($data['dateModified']) ? " dateModified is missing" : "";
            return -1;
        }
        $this->Title = $data['title'];
        $this->Content = $data['content'];
        $this->DateCreated = empty($data['DateCreated']) ? date("Y-m-d H:i:s") : date($data['DateCreated']);
        $this->DateModified = empty($data['DateModified']) ? date("Y-m-d H:i:s") : date($data['DateModified']);
        return 1;
    }

    public function VotePostDataControl($data){
        if(!isset($data['like'])||!isset($data['dislike'])){
            $this->errorMessage = "Bad request";
            $this->errorMessage .= !isset($data['like']) ? " like is missing" : "";
            $this->errorMessage .= !isset($data['dislike']) ? " dislike is missing" : "";
            return -1;
        }
        //if the likes and dislikes are equal to 1 at the same time
        if($data['like'] == 1 && $data['dislike'] == 1){
            $this->errorMessage = "Bad request";
            $this->errorMessage .= "Cannot like and dislike an article at the same time";
            return -1;
        }
        $this->Likes = (int) $data['like'];
        $this->Dislikes = (int) $data['dislike'];
        return 1;
    }

    public function publishPutDataControl($data){
        if(empty($data['title']) || empty($data['content']) || !isset($data['dateCreated'])|| !isset($data['dateModified'])){
            $this->errorMessage = "Bad request";
            $this->errorMessage .= empty($data['title']) ? " title is missing" : "";
            $this->errorMessage .= empty($data['content']) ? " content is missing" : "";
            $this->errorMessage .= !isset($data['dateCreated']) ? " dateCreated is missing" : "";
            $this->errorMessage .= !isset($data['dateModified']) ? " dateModified is missing" : "";
            return -1;
        }
        $this->Title = htmlspecialchars($data['title']);
        $this->Content = htmlspecialchars($data['content']);
        $this->DateCreated = date($data['dateCreated']);
        if(empty($data['dateModified'])){
            $this->DateModified = date("Y-m-d H:i:s");
        }else{
            $this->DateModified = date($data['dateModified']);
        }
        return 1;
    }

    public function votePutDataControl($data,$idArticle){
        if(!isset($idArticle['id']) || !isset($data['like'])||!isset($data['dislike'])){
            $this->errorMessage = "Bad request";
            $this->errorMessage .= !isset($idArticle['id']) ? " idArticle is missing" : "";
            $this->errorMessage .= !isset($data['like']) ? " like is missing" : "";
            $this->errorMessage .= !isset($data['dislike']) ? " dislike is missing" : "";
            return -1;
        }
        //if the likes and dislikes are equal to 1 at the same time
        if($data['like'] == 1 && $data['dislike'] == 1){
            $this->errorMessage = "Bad request";
            $this->errorMessage .= "Cannot like and dislike an article at the same time";
            return -1;
        }
        $this->Likes = (int) $data['like'];
        $this->Dislikes = (int) $data['dislike'];
        $this->IdArticle = (int) $idArticle['id'];
        return 1;
    }

    public function create()
    {
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
            "DateCreated" => $this->DateCreated
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
                $daoEvaluate->createLike($this->IdArticle,$this->IdUser);
            } else {
                $daoEvaluate->createDislike($this->IdArticle,$this->IdUser);
            }
            return 1;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
        
    }

    public function updateVote(){
        $daoEvaluate = new Evaluate();
        try{
            if ($this->Likes == 1){
                $daoEvaluate->updateLike($this->IdArticle,$this->IdUser);
            } else {
                $daoEvaluate->updateDislike($this->IdArticle,$this->IdUser);
            }
            return 1;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
    }

    public function deleteVote(){
        $daoEvaluate = new Evaluate();
        try{
            $daoEvaluate->deleteVote($this->IdArticle,$this->IdUser);
            return 1;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return -1;
        }
    }

    public function getPostedArticle()
    {

        $idArticle = $this->dao->getlastInsertId();
        return array($this->dao->select($idArticle));
    }

    public function getModifiedArticle(){
        return array($this->dao->select($this->IdArticle));
    }

    public function getErrorMessage(){
        // Retrieve the catched error from the create() function
        return $this->errorMessage;
    }

    public function getAllData($role){
        $articleList = $this->getAll();
        return $this->verifyPermissions($role, $articleList);
    }

    public function getOneData($role, $id){
        $articleList = array($this->getById($id));
        return $this->verifyPermissions($role, $articleList);
    }

    private function verifyPermissions($role, $articleList){
        $data = array();
        foreach ($articleList as $art) {
            $article = array(
                "id" => $art->IdArticle,
                "title" => $art->Title,
                "content" => $art->Content,
                "author" => $art->IdUser,
                "dateCreated" => $art->DateCreated,
                "dateModified" => $art->DateModified,
            );
            if ($role == 'moderator' || $role == 'publisher') {
                $article["nblikes"] = $art->Likes;
                $article["nbdislikes"] = $art->Dislikes;
            }
            if ($role == 'moderator') {
                $article["listlikes"] = $art->ListLikes;
                $article["listdislikes"] = $art->ListDislikes;
            }
            $data[] = $article;
        }
        return $data;
    }

    public function getVotedArticle(){
        $daoEvaluate = new Evaluate();
        $data = $daoEvaluate->getEvaluate($this->IdArticle, $this->IdUser);
        return $data;
    }

    public function checkIfUserHasAlreadyVoted(){
        $daoEvaluate = new Evaluate();
        $data = $daoEvaluate->getEvaluate($this->IdArticle, $this->IdUser);
        if (empty($data)){
            return 0;
        } else {
            return 1;
        }
    }
}