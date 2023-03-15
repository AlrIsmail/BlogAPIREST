<?php

const DB_HOST = "145.14.156.192";
const DB_USER = "u563109936_blogfiadmin";
const DB_PASS = "F7O:Om@#h~";
const DB_NAME = "u563109936_Blog_FI";

// Http Url
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('HTTP_URL', '/'. substr_replace(trim($_SERVER['REQUEST_URI'], '/'), '', 0, strlen($scriptName)));

// Define Paths Application controller and database

const CONTROLLER_PATH = ROOT_PATH . "Controller/";
const MODEL_PATH = ROOT_PATH . "Model/";
const VIEW_PATH = ROOT_PATH . "View/";
const DATABASE_PATH = ROOT_PATH . "Database/";
