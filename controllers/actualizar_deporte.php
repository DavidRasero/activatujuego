<?php
session_start();
require_once('../config/database.php');
require_once('../models/Deporte.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $numero_jugadores = intval($_POST['numero_jugadores']);

    if (empty($nombre) || empty($descripcion) || $numero_jugadores < 1) {
        $_SESSION['error'] = "Todos los campos son obligatorios y válidos.";
        header("Location: ../views/editar_deporte.php?id=$id");
        exit;
    }

    $deporteModel = new Deporte($connection);
    $deporteExistente = $deporteModel->obtenerPorId($id);

    if (!$deporteExistente) {
        $_SESSION['error'] = "El deporte no existe.";
        header("Location: ../views/gestionar_deportes.php");
        exit;
    }

    $nuevaImagen = $deporteExistente['imagen'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreTmp = $_FILES['imagen']['tmp_name'];
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid('deporte_') . "." . $extension;
        $rutaDestino = "../public/img/" . $nombreArchivo;

        if (move_uploaded_file($nombreTmp, $rutaDestino)) {
            $nuevaImagen = "/hlc/activaTuJuego/public/img/" . $nombreArchivo;
        } else {
            $_SESSION['error'] = "Error al subir la nueva imagen.";
            header("Location: ../views/editar_deporte.php?id=$id");
            exit;
        }
    }

    if ($deporteModel->actualizar($id, $nombre, $descripcion, $numero_jugadores, $nuevaImagen)) {
        $_SESSION['success'] = "Deporte actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el deporte.";
    }

    header("Location: ../views/gestionar_deportes.php");
    exit;
}
?>
