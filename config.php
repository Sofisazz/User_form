<?php
$host = 'localhost';
$dbname = 'user_form_db_gurskaya';
$username = 'root';     
$password = 'mysql';         

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Ошибка подключения к базе данных: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");
?>