<?php
session_start();
require_once('../config/database.php');
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
    } else {
        $centroModel = new CentroDeportivo($connection);
        $exito = $centroModel->crear($nombre, $direccion);

        if ($exito) {
            $_SESSION['success'] = "Centro deportivo creado correctamente.";
            header("Location: ../views/gestionar_centros.php");
        } else {
            $_SESSION['error'] = "Error al crear el centro deportivo.";
            header("Location: ../views/crear_centro.php");
        }
    }
} else {
    header("Location: ../index.php");
}
exit;
