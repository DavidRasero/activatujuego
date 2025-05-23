<?php
session_start();
require_once('../config/database.php');
require_once('../models/CentroDeportivo.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$centro_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$centro_id) {
    $_SESSION['error'] = "ID de centro no válido.";
} else {
    $centroModel = new CentroDeportivo($connection);

    if ($centroModel->tieneEventos($centro_id)) {
        $_SESSION['error'] = "No se puede eliminar el centro porque tiene eventos asociados.";
    } elseif ($centroModel->eliminar($centro_id)) {
        $_SESSION['success'] = "Centro deportivo eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el centro deportivo.";
    }
}

header("Location: ../views/gestionar_centros.php");
exit;
