<?php
// index.php mvc
require_once "Config/Register.php";
require(CONTROLLER_PATH . "index.php");

$type = null;
$controller = null;
$action = null;

$uri_pattern = explode('/', ROUTE_PATTERN);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
print_r($uri_pattern);
print_r($uri);
// pattern verification
if (count($uri_pattern) > count($uri)) {
    header("HTTP/1.1 404 Not Found");
}else{
    for ($x = 0; $x < count($uri); $x++) {
        if ($x < count($uri_pattern)) {
            if($uri_pattern[$x] == "{type}"){
                $type = $uri[$x];
            }
            else if ($uri_pattern[$x] == "{controller}") {
                $controller = $uri[$x];
            } else if ($uri_pattern[$x] == "{action}") {
                $action = $uri[$x];
            } else if ($uri_pattern[$x] != $uri[$x]) {
                header("HTTP/1.1 404 Not Found");
                exit();
            }
        }
    }
}
print_r($uri);
print_r($type, $controller, $action);
try {
    $controllerInstance = GetControllerInstance($type, $controller);
} catch (Exception $e) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
if ($controllerInstance == null) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$method = $_SERVER["REQUEST_METHOD"];
switch ($method) {
    case "POST":
        $controllerInstance->{$action . 'PostAction'}($_POST);
        break;
    case "PUT":
        parse_str(file_get_contents("php://input"), $post_vars);
        $controllerInstance->{$action . 'PutAction'}($post_vars);
        break;
    case "DELETE":
        parse_str(file_get_contents("php://input"), $post_vars);
        $controllerInstance->{$action . 'DeleteAction'}($post_vars);
        break;
    default:
        $controllerInstance->{$action . 'Action'}($_GET);
        break;
}