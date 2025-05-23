<?php
session_start();
require_once('../config/database.php');
require_once('../models/Deporte.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$deporte_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$deporte_id) {
    $_SESSION['error'] = "ID de deporte no válido.";
} else {
    $stmt = $connection->prepare("SELECT COUNT(*) FROM evento WHERE deporte_id = ?");
    $stmt->bind_param("i", $deporte_id);
    $stmt->execute();
    $stmt->bind_result($cuenta);
    $stmt->fetch();
    $stmt->close();

    if ($cuenta > 0) {
        $_SESSION['error'] = "No se puede eliminar este deporte porque hay eventos asociados.";
    } else {
        $deporteModel = new Deporte($connection);
        if ($deporteModel->eliminar($deporte_id)) {
            $_SESSION['success'] = "Deporte eliminado correctamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el deporte.";
        }
    }
}

header("Location: ../views/gestionar_deportes.php");
exit;
