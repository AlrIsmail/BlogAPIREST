<?php
// index.php mvc
require_once "Config/Register.php";
require(CONTROLLER_PATH . "router.php");

$type = null;
$controller = null;
$action = null;

$uri_pattern = explode('/', ROUTE_PATTERN);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
// pattern verification
// if contains v1 in uri
if (in_array('v1', $uri)) {
    // remove anything before v1
    $uri = array_slice($uri, array_search('v1', $uri));
}else{
    // not found
    header('HTTP/1.1 404 Not Found');
    echo "The file you're looking for ~does not~ exist.";
    exit;
}
if (count($uri_pattern) > count($uri)) {
    header('HTTP/1.1 404 Not Found');
    echo "The file you're looking for ~does not~ exist.";
}else{
    // check if pattern is correct
    for ($i = 0; $i < count($uri_pattern); $i++) {
        if ($uri_pattern[$i] == '{type}') {
            $type = $uri[$i];
        } elseif ($uri_pattern[$i] == '{controller}') {
            $controller = $uri[$i];
        } elseif ($uri_pattern[$i] == '{action}') {
            $action = $uri[$i];
        }else{
           if ($uri_pattern[$i] != $uri[$i]) {
               header('HTTP/1.1 404 Not Found');
               echo "The file you're looking for ~does not~ exist.";
               exit;
           }
        }
    }
}
try {
    $controllerInstance = GetControllerInstance($type, $controller);
} catch (Exception $e) {
    header('HTTP/1.1 404 Not Found');
    exit();
}
if ($controllerInstance == null) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$method = $_SERVER["REQUEST_METHOD"];
try{
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
        case "GET":
            parse_str(file_get_contents("php://input"), $post_vars);
            $controllerInstance->{$action . 'GetAction'}($post_vars);
            break;
        default:
            $controllerInstance->{$action . 'Action'}($_GET);
            break;
    }
}catch(Exception $e){
    header('HTTP/1.1 404 Action Not Found');
    exit();
}