<?php



class Articles{


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

    public function __construct(){
        $this->dao = new Article();
    }

    public function GetAll()
    {
        return $this->dao->selectAll();
    }

    public function GetById($id)
    {
        return $this->dao->select($id);
    }

    public function Create()
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
            return -1;
        }
    }

    public function getPostedArticle()
    {
        $idArticle = $this->dao->getPDO()->lastInsertId();
        return $this->dao->select($idArticle);
    }
}