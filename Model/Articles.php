<?php



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
            $temp->IdUser = $article['IdUser'];
            $temp->Title = $article['Title'];
            $temp->Content = $article['Content'];
            $temp->DateCreated = $article['DateCreated'];
            $temp->DateModified = $article['DateModified'];
            $this->listArticles[] = $temp;
        }
        return $this->listArticles;
    }

    public function getById($id)
    {
        return $this->dao->select($id);
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
            $this->dao->deleteArticle($this->IdArticle);
            return 1;
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