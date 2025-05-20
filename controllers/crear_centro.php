<?php
session_start();
require_once('../includes/db.php');
require_once('../models/CentroDeportivo.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);

    if (empty($nombre) || empty($direccion)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../views/crear_centro.php");
        exit;
    }

    $centroModel = new CentroDeportivo($connection);
    if ($centroModel->crear($nombre, $direccion)) {
        $_SESSION['success'] = "Centro deportivo creado correctamente.";
        header("Location: ../views/gestionar_centros.php");
        exit;
    } else {
        $_SESSION['error'] = "Error al crear el centro deportivo.";
        header("Location: ../views/crear_centro.php");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
