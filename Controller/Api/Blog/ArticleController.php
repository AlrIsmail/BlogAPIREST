<?php
require MODEL_PATH . "Articles.php";
require_once MODEL_PATH . "jwt_utils.php";

class ArticleController
{
    private $role = null;
    private $idUser = null;
    public function __construct()
    {
        $jwt = get_bearer_token();
        /// verify the validity of the token
        if (!is_jwt_valid($jwt)) {
            #todo : verify if we need to send a response
            //deliver_response(401, "Unauthorized", NULL);
            $this->role = 'guest';
        } else {
            //deliver_response(200, "Authorized", NULL);
            $this->role = strtolower(get_role($jwt));
            $this->idUser = get_user($jwt);
        }
    }

    /**
     * @throws Exception
     */
    // url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog//
    public function GetAction()
    {
        if(empty($_GET['id']))
            $this->ArticlesGetAction();
        else
            $this->ArticleGetAction();	
    }

    // example url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Articles/
    public function ArticlesGetAction()
    {
        $articles = new Articles();
        $data = $articles->getAllData($this->role);
        if (empty($data))
            deliver_response(200, "Success... No articles found at the moment", NULL);
        else
            deliver_response(200, "Success... Articles found :)", $data);
    }

    // example url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Article/1
    public function ArticleGetAction(){
        $articles = new Articles();
        if (empty($_GET['id'])) {
            deliver_response(400, "Bad request... missing the id of the article", NULL);
        }
        if (!is_numeric($_GET['id'])) {
            deliver_response(400, "Bad request... the id needs to be a number", NULL);
        }
        if ($_GET['id'] < 0) {
            deliver_response(400, "Bad request... the id needs to be a positive number", NULL);
        }
        if (strpos($_GET['id'], '.') !== false) {
            deliver_response(400, "Bad request... the id needs to be an integer", NULL);
        }
        $data = $articles->getOneData($this->role, $_GET['id']);
        if (empty($data)) {
            deliver_response(404, "Not found... the article with the id ".$_GET['id']."does not exists", NULL);
        }
        deliver_response(200, "Article found :)", $data);
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    // url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog//
    public function PostAction()
    {
        if ($this->role !== 'publisher') {
            deliver_response(401, "Unauthorized... only publishers can publish or vote", NULL);
        }
        if (empty($_GET['action'])) {
            deliver_response(400, "Bad request... missing action the add either Blog/Publish or Blog/Vote", NULL);
        }
    }

    // example url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Publish/
    public function PublishPostAction(){
        if ($this->role !== 'publisher') {
            deliver_response(401, "Unauthorized... only publishers can publish", NULL);
        }

        $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);

        if (empty($data['title']) || empty($data['content'] || empty(['author']))) {
            $message = "Bad request";
            $message .= empty($data['title']) ? " title is missing" : "";
            $message .= empty($data['content']) ? " content is missing" : "";
            $message .= empty($data['author']) ? " author is missing" : "";
            deliver_response(400, $message, NULL);
        }

        $articles = new Articles();

        // TODO : check the validity of the information sent and sql insertion if something is wrong create() will return -1
        $articles->IdUser = ($data['id'] === $this->idUser) ? $data['id'] : 0;
        $articles->Title = $data['title'];
        $articles->Content = $data['content'];
        $articles->DateCreated = empty($data['DateCreated']) ? date("Y-m-d H:i:s") : $data['DateCreated'];
        $articles->DateModified = empty($data['DateModified']) ? date("Y-m-d H:i:s") : $data['DateModified'];

        switch ($articles->create()) {
            case 1:
                deliver_response(200, "Success... Article created", $articles->getPostedArticle());
                break;
            case -1:
                deliver_response(400, "Bad Request... ".$articles->getErrorMessage(), NULL);
                break;
            default:
                deliver_response(500, "Erorr... Can't post article internal server error", NULL);
        }
        
    }

    // example url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Vote/
    public function VotePostAction(){
        if ($this->role !== 'publisher') {
            deliver_response(401, "Unauthorized... only publishers can publish or vote", NULL);
        }

        $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        
        if (empty($_GET['id'])) {
            deliver_response(400, "Bad request... messing the id of the article", NULL);
        }
        $articles = new Articles();
        $articles->IdArticle = $_GET['id'];
        $articles->IdUser = $this->idUser;
        $articles->Likes = isset($data['like']) ? $data['like'] : 0;
        $articles->Dislikes = isset($data['dislike']) ? $data['dislike'] : 0;
        if ($articles->Likes == 1 && $articles->Dislikes == 1) {
            deliver_response(400, "Bad request can't like and dislike an article at the same time", NULL);
        }
        $article = $articles->getById($_GET['id']);
        if (empty($article)) {
            deliver_response(404, "Not found... the article you would like to evaluate dosen't exist", NULL);
        }

        // check if the author is the same as the user
        if ($article->IdUser !== $this->idUser) {
            deliver_response(401, "Unauthorized... a publisher can't like or dislike his article", NULL);
        }

        // TODO : check if the user has already voted .. works?
        if ($article->ListLikes != null && $article->ListDislikes != null) {
            $listLikes = explode(",", $article->ListLikes);
            $listDislikes = explode(",", $article->ListDislikes);
            if (!in_array($this->idUser, $listLikes) && !in_array($this->idUser, $listDislikes)) {
                deliver_response(400, "Bad request... you have not voted yet use POST to vote", NULL);
            }
        }

        switch ($article->createVote()) {
            case 1:
                deliver_response(200, "Success... Vote added", $article->getPostedArticle());
                break;
            case -1:
                deliver_response(400, "Bad Request... ".$article->getErrorMessage(), NULL);
                break;
            case -2:
                deliver_response(404, "Error... Vote can't be added Internal Server Error", NULL);
                break;
        }
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    // url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/
    public function PutAction()
    {
        if ($this->role !== 'publisher') {
            deliver_response(401, "Unauthorized... Only publisher can modify a post or a vote", NULL);
        }
        if (empty($_GET['action'])) {
            deliver_response(400, "Bad request... missing action the add either Blog/Publish or Blog/Vote", NULL);
        }
    }

    public function PublishPutAction(){

        if ($this->role !== 'publisher') {
            deliver_response(401, "Unauthorized... Only publisher can modify a post or a vote", NULL);
        }
        $data = json_decode(file_get_contents('php://input'), true);


        if (empty($_GET['id']) || empty($data['title']) || empty($data['content'] ||
                empty(['author'])) || empty($data['dateCreated'])) {
                $message = "Bad request";
                $message .= empty($_GET['IdArticle']) ? " IdArticle is missing" : "";
                $message .= empty($data['title']) ? " title is missing" : "";
                $message .= empty($data['content']) ? " content is missing" : "";
                $message .= empty($data['author']) ? " author is missing" : "";
                $message .= empty($data['dateCreated']) ? " dateCreated is missing" : "";
                deliver_response(400, $message, NULL);
        }
        $articles = new Articles();

        $articles->IdUser = ($data['author'] == $this->idUser) ? $data['author'] : 0;
        $articles->IdArticle = $_GET['IdArticle'];
        $articles->Title = $data['title'];
        $articles->Content = $data['content'];
        $articles->DateCreated = $data['DateCreated'];
        $articles->DateModified = empty($data['DateModified']) ? date("Y-m-d H:i:s") : $data['DateModified'];

        switch ($articles->update()) {
            case 1:
                deliver_response(200, "Article updated", $articles->getModifiedArticle());
                break;
            case -1:
                deliver_response(400, "Bad Request... ".$articles->getErrorMessage(), NULL);
                break;
            default:
                deliver_response(500, "Internal server error", NULL);
        }
    }

    // url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Vote/
    public function VotePutAction(){

        if ($this->role !== 'publisher') {
            deliver_response(401, "Unauthorized... Only publisher can modify a post or a vote", NULL);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($_GET['id'])) {
            deliver_response(400, "Bad request messing the id of the article", NULL);
        }

        $articles = new Articles();
        $articles->IdArticle = $_GET['id'];
        $articles->IdUser = $this->idUser;
        $articles->Likes = isset($data['like']) ? $data['like'] : 0;
        $articles->Dislikes = isset($data['dislike']) ? $data['dislike'] : 0;

        if ($articles->Likes == 1 && $articles->Dislikes == 1) {
            deliver_response(400, "Bad request can't like and dislike an article at the same time", NULL);
        }

        $article = $articles->getById($_GET['id']);
        // check if the author is the same as the user
        if ($article->IdUser !== $this->idUser) {
            deliver_response(401, "Unauthorized a publisher can't like or dislike his article", NULL);
        }
        // TODO : check if the user has already voted .. works?
        if ($article->ListLikes != null && $article->ListDislikes != null) {
            $listLikes = explode(",", $article->ListLikes);
            $listDislikes = explode(",", $article->ListDislikes);
            if (in_array($this->idUser, $listLikes) || in_array($this->idUser, $listDislikes)) {
                deliver_response(400, "Bad request : you already voted on this article use PUT instead", NULL);
            }
        }

        switch ($articles->updateVote()) {
            case 1:
                deliver_response(200, "Success... Vote added", $articles->getPostedArticle());
                break;
            case -1:
                deliver_response(400, "Bad Request... ".$articles->getErrorMessage(), NULL);
                break;
            case -2:
                deliver_response(404, "Vote can't be added Internal Server Error", NULL);
                break;
        }

    }


    /**
     * @throws JsonException
     * @throws Exception
     */
    // url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog//
    public function DeleteAction()
    {
        if ($this->role !== 'publisher' && $this->role !== 'admin') {
            deliver_response(401, "Unauthorized... only the admin or the publisher can delete articles", NULL);
        }
        if (empty($_GET['action'])) {
            deliver_response(400, "Bad request... missing action the add either Blog/Publish or Blog/Vote", NULL);
        }
    }

    // url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Publish/
    public function PublishDeleteAction(){
        if ($this->role !== 'publisher' && $this->role !== 'admin') {
            deliver_response(401, "Unauthorized... only the admin or the publisher can delete articles", NULL);
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id'])) {
            $message = "Bad request...";
            $message .= empty($data['id']) ? " id is missing" : "";
            deliver_response(400, $message, NULL);
        }
        $articles = new Articles();
        $article = $articles->getById($data['id']);
        if($this->role == 'publisher' && $article->IdUser != $this->idUser){
            deliver_response(401, "Unauthorized... only the publisher of the article can delete articles", NULL);
        }
       
        // check if the article is published by the publisher
        if (($this->role == 'publisher' && $article->IdUser == $this->idUser) || $this->role == 'admin') {
            $articles->IdUser = $article->IdUser;
        }
        else
            deliver_response(401, "Unauthorized... only the publisher of the article can delete his articles or the admin", NULL);
        
        switch ($articles->delete()) {
            case 1:
                deliver_response(200, "Success... Article deleted", $articles->getPostedArticle());
                break;
            case -1:
                deliver_response(400, "Bad Request... ".$articles->getErrorMessage(), NULL);
                break;
            default:
                deliver_response(500, "Error... Internal server error", NULL);
        }
    }

    // url : http://localhost/BlogAPIREST/index.php/v1/Api/Blog/Vote/
    public function VoteDeleteAction(){
        if ($this->role !== 'publisher') {
            deliver_response(401, "Unauthorized... only the publisher can delete his vote", NULL);
        }
        if (empty($_GET['id'])) {
            $message = "Bad request...";
            $message .= empty($_GET['id']) ? " Article id is missing which must be passed in the url Vote/{id}" : "";
            deliver_response(400, $message, NULL);
        }
        $art = new Articles();
        $article = $art->getById($_GET['id']);
        if (empty($article->IdArticle)) {
            deliver_response(404, "Bad Request ... Article not found", NULL);
        }
        if($this->role == 'publisher' && $article->IdUser != $this->idUser){
            deliver_response(401, "Unauthorized... only the publisher of the article can delete articles", NULL);
        }
        // TODO : check the validity of the information sent and sql insertion if something is wrong create() will return -1
        if ($this->role == 'publisher' && $article->IdUser == $this->idUser)
            $art->IdUser = $this->idUser;
        else
            deliver_response(401, "Unauthorized... only the publisher who voted can delete his vote", NULL);
        switch ($art->deleteVote()) {
            case 1:
                deliver_response(200, "Success... vote deleted", $art->getPostedArticle());
                break;
            case -1:
                deliver_response(400, "Bad Request... ".$art->getErrorMessage(), NULL);
                break;
            default:
                deliver_response(500, "Error... Internal server error", NULL);
        }
    }
}
