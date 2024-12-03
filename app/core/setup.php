<?php

//require our files, remember should be relative to index.php
require '../app/core/Router.php';
require '../app/models/Model.php';
require '../app/controllers/Controller.php';
require '../app/controllers/MainController.php';
require '../app/controllers/UserController.php';
require '../app/models/User.php';
require_once __DIR__ . '/../../vendor/autoload.php';


//set up env variables
$env = parse_ini_file('../.env');
define('DB_HOST', $env['DBHOST']);
define('DB_NAME', $env['DBNAME']);
define('DB_USER', $env['DBUSER']);
define('DB_PASS', $env['DBPASS']);

function connectDatabase() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
//set up other configs
define('DEBUG', true);