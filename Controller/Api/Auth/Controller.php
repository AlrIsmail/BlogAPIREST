<?php
require_once CONTROLLER_PATH . "Api/Auth/AuthController.php";

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