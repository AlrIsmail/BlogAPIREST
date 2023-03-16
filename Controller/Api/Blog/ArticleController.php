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
            // also the editor can see the list of likes and dislikes for his articles
            if($this->role == 'editor' && $article->IdUser == get_jwt_payload($jwt)['username']){
                $articl["listlies"] = $article->ListLikes;
                $articl["listdislikes"] = $article->ListDislikes;
            }
            array_push($data, $articl);
        }
        deliver_response(200, "Article", $data);
    }
}