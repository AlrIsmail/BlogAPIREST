<?php
function GetControllerInstance($type, $controller)
{
    try {
        switch ($type) {
            case "Api":
                require CONTROLLER_PATH . "Api/Auth/Controller.php";
                return GetApiControllerInstance($controller);
            case "Blog":
                require CONTROLLER_PATH . "Blog/BlogController.php";
                return GetControllerInstance($controller); //TODO : Ask Ismail if this is intended
            default:
                header("HTTP/1.1 404 Not Found");
                exit();
        }
    } catch (Error $e) {
        deliver_response(404, "Not Found ".$e, null);
    } catch (Exception $e) {
    }
    return null;
}
