<?php

require CONTROLLER_PATH . "UserController.php";
require CONTROLLER_PATH . "ArticleController.php";

/**
 * @throws Exception
 */
function getControllerInstance($controller)
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
