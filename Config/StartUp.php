<?php

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "test");

// Http Url
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('HTTP_URL', '/'. substr_replace(trim($_SERVER['REQUEST_URI'], '/'), '', 0, strlen($scriptName)));

// Define Paths Application controller and database

define("CONTROLLER_PATH", ROOT_PATH . "Controller/");
define("MODEL_PATH", ROOT_PATH . "Model/");
define("VIEW_PATH", ROOT_PATH . "View/");
define("DATABASE_PATH", ROOT_PATH . "Database/");
