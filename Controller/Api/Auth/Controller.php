<?php
require CONTROLLER_PATH . "Api/Auth/AuthController.php";

/**
 * @throws Exception
 */

function getApiControllerInstance($controller){
    try {
        switch($controller){
            case "Auth":
                return new AuthController();
            default:
                throw new Exception("Controller not found");
        }
    }catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}