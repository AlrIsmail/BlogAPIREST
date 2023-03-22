<?php
function GetControllerInstance($type, $controller)
{
    try {
        switch ($type) {
            case "Api":
                switch ($controller) {
                    case "Auth":
                        require_once CONTROLLER_PATH . "Api/Auth/AuthController.php";
                        return new AuthController();
                    case "Blog":
                        require CONTROLLER_PATH . "Api/Blog/ArticleController.php";
                        return new ArticleController();
                    default:
                        header("HTTP/1.1 404 Not Found");
                }
            default:
                header("HTTP/1.1 404 Not Found");
                exit();
        }
    } catch (Error $e) {
        deliver_response(404, "Not Found " . $e, null);
    } catch (Exception $e) {
    }
    return null;
}
