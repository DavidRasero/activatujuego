<?php
session_start();
require_once('../config/database.php');
require_once('../models/CentroDeportivo.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);

    if (empty($nombre) || empty($direccion)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../views/editar_centro.php?id=$id");
        exit;
    }

    $centroModel = new CentroDeportivo($connection);
    $centroExistente = $centroModel->obtenerPorId($id);

    if (!$centroExistente) {
        $_SESSION['error'] = "El centro no existe.";
    } elseif ($centroModel->actualizar($id, $nombre, $direccion)) {
        $_SESSION['success'] = "Centro actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el centro.";
    }

    header("Location: ../views/gestionar_centros.php");
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
