<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Inscripcion.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] === 'admin') {
    $_SESSION['error'] = "Debes iniciar sesión como jugador u organizador para inscribirte.";
    header("Location: ../views/login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if (!isset($_GET['evento_id'])) {
    $_SESSION['error'] = "Evento no válido.";
    header("Location: ../views/eventos.php");
    exit;
}

$evento_id = intval($_GET['evento_id']);

$sql = "SELECT organizador_id FROM evento WHERE id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $evento_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $organizador_id);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($organizador_id == $usuario_id) {
    $_SESSION['error'] = "No puedes inscribirte a tu propio evento.";
    header("Location: ../views/eventos.php");
    exit;
}

$inscripcionModel = new Inscripcion($connection);
$estado = $inscripcionModel->obtenerEstado($usuario_id, $evento_id);

if ($estado !== null) {
    $_SESSION['error'] = "Ya estás inscrito a este evento.";
    header("Location: ../views/eventos.php");
    exit;
}

if ($inscripcionModel->crear($usuario_id, $evento_id)) {
    $_SESSION['success'] = "Inscripción realizada con éxito.";
} else {
    $_SESSION['error'] = "Error al inscribirse al evento.";
}

header("Location: ../views/eventos.php");
exit;
