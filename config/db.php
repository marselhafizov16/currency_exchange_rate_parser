<?php
error_reporting(-1);

$host = 'localhost';
$name = 'root';
$pass = '';
$db = 'parser';

$link = mysqli_connect($host, $name, $pass, $db);
// $result = mysqli_query($link, 'SELECT * FROM users') or die(mysqli_error($link));

?>