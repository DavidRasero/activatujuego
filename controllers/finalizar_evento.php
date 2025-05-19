<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Evento.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    $_SESSION['error'] = "No tienes permiso para realizar esta acción.";
    header("Location: ../index.php");
    exit;
}

$evento_id = isset($_GET['evento_id']) ? intval($_GET['evento_id']) : 0;
$organizador_id = $_SESSION['usuario_id'];

if ($evento_id <= 0) {
    $_SESSION['error'] = "Evento no válido.";
    header("Location: ../views/mis_eventos.php");
    exit;
}

$eventoModel = new Evento($connection);

$evento = $eventoModel->obtenerEventoPorId($evento_id, $organizador_id);

if (!$evento) {
    $_SESSION['error'] = "No tienes permiso para finalizar este evento.";
    header("Location: ../views/mis_eventos.php");
    exit;
}

$sql = "UPDATE evento SET estado = 'finalizado' WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $evento_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Evento marcado como finalizado.";
} else {
    $_SESSION['error'] = "No se pudo finalizar el evento.";
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
header("Location: ../views/mis_eventos.php");
exit;
