<?php
require CONTROLLER_PATH . "Api/Auth/Controller.php";

/**
 * @throws Exception
 */

function getApiControllerInstance($controller)
{
    try {
        switch($controller){
            case "Auth":
                return new AuthController();
            default:
                throw new \RuntimeException("Controller not found");
        }
    }catch (Exception $e) {
        throw new \RuntimeException($e->getMessage());
    }
}