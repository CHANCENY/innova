<?php
session_start();
require_once "vendor/autoload.php";

$app = new Innova\Routes\Routes($_SERVER['REQUEST_URI']);
$app->app();