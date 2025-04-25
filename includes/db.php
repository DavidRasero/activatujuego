<?php

$host = "localhost";
$user = "root";
$pass = ""; 
$db = "activatujuego"; 

$connection = mysqli_connect($host, $user, $pass, $db);

if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>

