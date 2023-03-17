<?php
require MODEL_PATH . "Articles.php";

class ArticleController
{
    private $role = null;
    private $idUser = null;
    public function __construct()
    {
        $jwt = get_bearer_token();
        /// verify the validity of the token
        if(!is_jwt_valid($jwt)) {
            #todo : verify if we need to send a response
            //deliver_response(401, "Unauthorized", NULL);
            $this->role = 'guest';
        }else{
            //deliver_response(200, "Authorized", NULL);
            $this->role = get_jwt_payload($jwt)['role'];
            $this->idUser = get_jwt_payload($jwt)['user'];
        }   
    }
    public function GetAction()
    {
        # todo : make class Article
        $articles = new Articles();
        if(empty($id = $_GET['id'])){
            $articleList = $articles->GetAll();
        }else{
            if(!is_numeric($id)){
                deliver_response(400, "Bad request the id needs to be a number", NULL);
            }
            $articleList = $articles->GetById($id);
            if(empty($articleList)){
                deliver_response(404, "Not found", NULL);
            }
        }
        $data = array();
        # todo : make a function to do this for each role
        foreach($article as $articleList){
            $articl = array(
                "id" => $article->Id,
                "title" => $article->Title,
                "content" => $article->Content,
                "author" => $article->IdUser,
                "dateCreated" => $article->DateCreated,
                "dateModified" => $article->DateModified,
            );
            if($this->role == 'admin' || $this->role == 'editor'){
                $articl["nblikes"] = $article->Likes;
                $articl["nbdislikes"] = $article->Dislikes;
            }
            if($this->role == 'admin'){
                $articl["listlies"] = $article->ListLikes;
                $articl["listdislikes"] = $article->ListDislikes;
            }
            array_push($data, $articl);
        }
        deliver_response(200, "Article", $data);
    }
    public function PostAction()
    {
        if($this->role !== 'publisher'){
            deliver_response(401, "Unauthorized", NULL);
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if(empty($data['title']) || empty($data['content'] || empty(['author']))){
            $message = "Bad request";	
            $message .= empty($data['title']) ? " title is missing" : "";
            $message .= empty($data['content']) ? " content is missing" : "";
            $message .= empty($data['author']) ? " author is missing" : "";
            deliver_response(400, $message, NULL);
        }
        $articles = new Articles();
        // TODO : check the validity of the information sent and sql insertion if something is wrong create() will return -1
        $articles->IdUser = ($data['id'] == $this->idUser) ? $data['id'] : 0;
        $articles->Title = $data['title'];
        $articles->Content = $data['content'];
        $articles->DateCreated = empty($data['DateCreated']) ? date("Y-m-d H:i:s") : $data['DateCreated'];
        $articles->DateModified = empty($data['DateModified']) ? date("Y-m-d H:i:s") : $data['DateModified'];
        $articles->Likes = empty($data['Likes']) ? 0 : $data['Likes'];
        $articles->Dislikes = empty($data['Dislikes']) ? 0 : $data['Dislikes'];
        $articles->ListLikes = empty($data['ListLikes']) ? array() : $data['ListLikes'];
        $articles->ListDislikes = empty($data['ListDislikes']) ? array() : $data['ListDislikes'];
        switch ($articles->Create()){
            case 1:
                deliver_response(200, "Article created", $articles->getPostedArticle());
                break;
            case -1:
                deliver_response(400, $articles->getErrorMessage() , NULL);
                break;
            default:
                deliver_response(500, "Internal server error", NULL);
        }
    }

    public function PutAction(){
        if($this->role !== 'publisher'){
            deliver_response(401, "Unauthorized", NULL);
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if(empty($data['id']) || empty($data['title']) || empty($data['content'] ||
         empty(['author'])) || empty($data['dateCreated'])){
            $message = "Bad request";	
            $message .= empty($data['id']) ? " id is missing" : "";
            $message .= empty($data['title']) ? " title is missing" : "";
            $message .= empty($data['content']) ? " content is missing" : "";
            $message .= empty($data['author']) ? " author is missing" : "";
            $message .= empty($data['dateCreated']) ? " dateCreated is missing" : "";
            deliver_response(400, $message, NULL);
        }
        $articles = new Articles();
        // TODO : check the validity of the information sent and sql insertion if something is wrong create() will return -1
        $articles->IdUser = ($data['id'] == $this->idUser) ? $data['id'] : 0;
        $articles->Title = $data['title'];
        $articles->Content = $data['content'];
        $articles->DateCreated = $data['DateCreated'];
        $articles->DateModified = empty($data['DateModified']) ? date("Y-m-d H:i:s") : $data['DateModified'];
        $articles->Likes = empty($data['Likes']) ? 0 : $data['Likes'];
        $articles->Dislikes = empty($data['Dislikes']) ? 0 : $data['Dislikes'];
        $articles->ListLikes = empty($data['ListLikes']) ? array() : $data['ListLikes'];
        $articles->ListDislikes = empty($data['ListDislikes']) ? array() : $data['ListDislikes'];
        switch ($articles->Update()){
            case 1:
                deliver_response(200, "Article updated", $articles->getPostedArticle());
                break;
            case -1:
                deliver_response(400, $articles->getErrorMessage() , NULL);
                break;
            default:
                deliver_response(500, "Internal server error", NULL);
        }
    }

    public function DeleteAction(){
        if($this->role !== 'publisher' && $this->role !== 'admin'){
            deliver_response(401, "Unauthorized", NULL);
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if(empty($data['id'])){
            $message = "Bad request";	
            $message .= empty($data['id']) ? " id is missing" : "";
            deliver_response(400, $message, NULL);
        }
        $articles = new Articles();
        // TODO : check the validity of the information sent and sql insertion if something is wrong create() will return -1
        if ($this->role == 'publisher')
            $articles->IdUser = ($data['id'] == $this->idUser) ? $data['id'] : 0;
        else
            $articles->IdUser = $data['id'];
        switch ($articles->Delete()){
            case 1:
                deliver_response(200, "Article deleted", $articles->getPostedArticle());
                break;
            case -1:
                deliver_response(400, $articles->getErrorMessage() , NULL);
                break;
            default:
                deliver_response(500, "Internal server error", NULL);
        }
    }
}