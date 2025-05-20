<?php
session_start();
require_once('../includes/db.php');
require_once('../models/CentroDeportivo.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de centro no válido.";
    header("Location: ../views/gestionar_centros.php");
    exit;
}

$centro_id = intval($_GET['id']);
$centroModel = new CentroDeportivo($connection);

if ($centroModel->tieneEventos($centro_id)) {
    $_SESSION['error'] = "No se puede eliminar el centro porque tiene eventos asociados.";
    header("Location: ../views/gestionar_centros.php");
    exit;
}

if ($centroModel->eliminar($centro_id)) {
    $_SESSION['success'] = "Centro deportivo eliminado correctamente.";
} else {
    $_SESSION['error'] = "Error al eliminar el centro deportivo.";
}

header("Location: ../views/gestionar_centros.php");
exit;
