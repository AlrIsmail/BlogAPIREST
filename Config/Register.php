<?php
define("ROOT_PATH", __DIR__ . "/../");
define("ROUTE_PATTERN", "/v1/{type}/{controller}/{action}");
require_once ROOT_PATH . "Config/StartUp.php";
require_once ROOT_PATH . "Config/ResponseHandler.php";
