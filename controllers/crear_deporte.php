<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $numero_jugadores = intval($_POST['numero_jugadores']);
    $descripcion = trim($_POST['descripcion']);

    if (empty($nombre) || $numero_jugadores <= 0 || empty($descripcion) || !isset($_FILES['imagen'])) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../views/crear_deporte.php");
        exit;
    }

    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $extension = pathinfo($imagen_nombre, PATHINFO_EXTENSION);
    $nuevo_nombre = uniqid('deporte_') . "." . $extension;
    // Crear la ruta absoluta a la carpeta de imágenes
$carpeta_imagenes = realpath(__DIR__ . '/../public/img');
if ($carpeta_imagenes === false) {
    $_SESSION['error'] = "No se encontró la carpeta de imágenes.";
    header("Location: ../views/crear_deporte.php");
    exit;
}
$ruta_destino = $carpeta_imagenes . '/' . $nuevo_nombre;

if (!move_uploaded_file($imagen_tmp, $ruta_destino)) {
    $_SESSION['error'] = "Error al subir la imagen.";
    header("Location: ../views/crear_deporte.php");
    exit;
}

    $ruta_guardada = $nuevo_nombre;

    $stmt = $connection->prepare("INSERT INTO deporte (nombre, numero_jugadores, descripcion, imagen) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $nombre, $numero_jugadores, $descripcion, $ruta_guardada);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Deporte creado correctamente.";
        $redirect = "../views/gestionar_deportes.php";
    } else {
        $_SESSION['error'] = "Error al guardar el deporte.";
        $redirect = "../views/crear_deporte.php";
    }

    $stmt->close();
    $connection->close();

    header("Location: $redirect");
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
