<?php
#TODO : remake
require CONTROLLER_PATH . "Api/Blog/UserController.php";
require CONTROLLER_PATH . "Api/Blog/ArticleController.php";

/**
 * @throws Exception
 */
function getApiControllerInstance($controller)
{
    try {
        switch($controller){
            case "user":
                return new UserController();
            case "article":
                return new ArticleController();
            default:
                throw new Exception("Controller not found");
        }
    }catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}
