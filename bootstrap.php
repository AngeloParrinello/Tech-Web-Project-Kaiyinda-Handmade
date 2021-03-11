<?php
session_start();
define("UPLOAD_DIR", "./upload/");
require_once("DB/database.php");
require_once("Utils/functions.php");
$dbh = new DatabaseHelper("localhost", "root", "", "kaiyindahandmade", 3306);

?>