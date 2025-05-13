<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
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

$sql = "SELECT id FROM evento WHERE id = ? AND organizador_id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "ii", $evento_id, $organizador_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_fetch_assoc($resultado)) {

    $update = "UPDATE evento SET estado = 'finalizado' WHERE id = ?";
    $stmt2 = mysqli_prepare($connection, $update);
    mysqli_stmt_bind_param($stmt2, "i", $evento_id);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    $_SESSION['success'] = "El evento ha sido marcado como finalizado.";
} else {
    $_SESSION['error'] = "No tienes permiso para finalizar este evento.";
}

header("Location: ../views/mis_eventos.php");
exit;
