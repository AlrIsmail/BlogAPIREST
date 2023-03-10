<?php
require CONTROLLER_PATH . "Auth/AuthController.php";

/**
 * @throws Exception
 */

function getControllerInstance($controller){
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