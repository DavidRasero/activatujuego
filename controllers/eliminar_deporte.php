<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Deporte.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de deporte no válido.";
    header("Location: ../views/gestionar_deportes.php");
    exit;
}

$deporte_id = intval($_GET['id']);

$stmt = $connection->prepare("SELECT COUNT(*) FROM evento WHERE deporte_id = ?");
$stmt->bind_param("i", $deporte_id);
$stmt->execute();
$stmt->bind_result($cuenta);
$stmt->fetch();
$stmt->close();

if ($cuenta > 0) {
    $_SESSION['error'] = "No se puede eliminar este deporte porque hay eventos asociados.";
    header("Location: ../views/gestionar_deportes.php");
    exit;
}


$deporteModel = new Deporte($connection);
if ($deporteModel->eliminar($deporte_id)) {
    $_SESSION['success'] = "Deporte eliminado correctamente.";
} else {
    $_SESSION['error'] = "Error al eliminar el deporte.";
}

header("Location: ../views/gestionar_deportes.php");
exit;
