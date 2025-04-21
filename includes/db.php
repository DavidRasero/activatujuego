<?php

// Datos de conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = ""; 
$db = "activatujuego"; 

// Conexión a la base de datos
$connection = mysqli_connect($host, $user, $pass, $db);

// Verificar la conexión
if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>

