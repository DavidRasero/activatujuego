<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para inscribirte.";
    header("Location: ../views/login.php");
    exit;
}

if (!isset($_GET['evento_id'])) {
    $_SESSION['error'] = "Evento no especificado.";
    header("Location: ../views/eventos.php");
    exit;
}

$evento_id = intval($_GET['evento_id']);
$usuario_id = $_SESSION['usuario_id'];

$sql_verificar = "SELECT id FROM inscripcion WHERE usuario_id = ? AND evento_id = ?";
$stmt = mysqli_prepare($connection, $sql_verificar);
mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $evento_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    $_SESSION['error'] = "Ya te has inscrito a este evento.";
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    header("Location: ../views/eventos.php");
    exit;
}
mysqli_stmt_close($stmt);

$sql_insert = "INSERT INTO inscripcion (usuario_id, evento_id, estado) VALUES (?, ?, 'pendiente')";
$stmt = mysqli_prepare($connection, $sql_insert);
mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $evento_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Te has inscrito correctamente. Tu solicitud está pendiente de aprobación.";
} else {
    $_SESSION['error'] = "Error al inscribirse: " . mysqli_error($connection);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
header("Location: ../views/eventos.php");
exit;
