<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    $_SESSION['error'] = "No tienes permiso para realizar esta acción.";
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['evento_id'])) {
    $_SESSION['error'] = "Evento no válido.";
    header("Location: ../views/mis_eventos.php");
    exit;
}

$evento_id = intval($_GET['evento_id']);
$organizador_id = $_SESSION['usuario_id'];

$sql_check = "SELECT id FROM evento WHERE id = ? AND organizador_id = ?";
$stmt = mysqli_prepare($connection, $sql_check);
mysqli_stmt_bind_param($stmt, "ii", $evento_id, $organizador_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_fetch_assoc($resultado)) {
    $sql_del_inscripciones = "DELETE FROM inscripcion WHERE evento_id = ?";
    $stmt_del = mysqli_prepare($connection, $sql_del_inscripciones);
    mysqli_stmt_bind_param($stmt_del, "i", $evento_id);
    mysqli_stmt_execute($stmt_del);
    mysqli_stmt_close($stmt_del);

    $sql_del_evento = "DELETE FROM evento WHERE id = ?";
    $stmt_evt = mysqli_prepare($connection, $sql_del_evento);
    mysqli_stmt_bind_param($stmt_evt, "i", $evento_id);
    mysqli_stmt_execute($stmt_evt);
    mysqli_stmt_close($stmt_evt);

    $_SESSION['success'] = "Evento eliminado correctamente.";
} else {
    $_SESSION['error'] = "No tienes permiso para eliminar este evento.";
}

mysqli_close($connection);
header("Location: ../views/mis_eventos.php");
exit;
