<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require(MODEL_PATH . "Client.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

class ClientController
{
    public function __construct()
    {
    }

    public function loginAction()
    {
        $error = "";
        if (isset($_POST['login'])) {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $client = Client::getAuth($username, $password);
                if ($client) {
                    // if session is not started, start it
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['token'] = $client['data'][0];
                    // use window.location to redirect to another page
                    //echo "<script>window.location.href = '" . ROOT_PATH . "index.php/v1/Api/Client/articles/';</script>";
                    // current location ../articles
                    $currentLocation = explode('/', $_SERVER['REQUEST_URI']);
                    $currentLocation = array_slice($currentLocation, 0, count($currentLocation) - 2);
                    $currentLocation = implode('/', $currentLocation);
                    echo "<script>window.location.href = '" . $currentLocation . "/articles/';</script>";
                } else {
                    $error = "Username or password is incorrect";
                    require_once(VIEW_PATH . "login.php");
                }
            } else {
                require_once(VIEW_PATH . "login.php");
            }
        }else{
            require_once(VIEW_PATH . "login.php");
        }
    }

    public function articlesAction()
    {
        $articles = Client::getArticles();
        require_once(VIEW_PATH . "articles.php");
    }

    public function createAction()
    {
        // current location ../articles
        $currentLocation = explode('/', $_SERVER['REQUEST_URI']);
        $currentLocation = array_slice($currentLocation, 0, count($currentLocation) - 2);
        $currentLocation = implode('/', $currentLocation);
        if (isset($_POST['create'])) {
            if (!empty($_POST['title']) && !empty($_POST['content'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $article = Client::postArticle($title, $content);
                if ($article) {
                    echo "<script>window.location.href = '" . $currentLocation . "/Client/articles/';</script>";
                } else {
                    echo "<script>alert('Something went wrong!');</script>";
                }
            }
        }
    }
    
}