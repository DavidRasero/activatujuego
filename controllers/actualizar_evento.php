<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $evento_id = intval($_POST['evento_id']);
    $organizador_id = $_SESSION['usuario_id'];
    $centro_id = intval($_POST['centro_id']);
    $fecha = $_POST['fecha'];

    if (!$evento_id || !$centro_id || !$deporte_id || !$fecha) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../views/editar_evento.php?evento_id=$evento_id");
        exit;
    }

    $fecha_evento = strtotime($fecha);
    $ahora = time();

    if ($fecha_evento <= $ahora) {
        $_SESSION['error'] = "La fecha del evento debe ser posterior a la actual.";
        header("Location: ../views/editar_evento.php?evento_id=$evento_id");
        exit;
    }

    $check = "SELECT id FROM evento WHERE id = ? AND organizador_id = ? AND estado = 'activo'";
    $stmt_check = mysqli_prepare($connection, $check);
    mysqli_stmt_bind_param($stmt_check, "ii", $evento_id, $organizador_id);
    mysqli_stmt_execute($stmt_check);
    $res_check = mysqli_stmt_get_result($stmt_check);
    mysqli_stmt_close($stmt_check);

    if (!mysqli_fetch_assoc($res_check)) {
        $_SESSION['error'] = "No tienes permiso para editar este evento.";
        header("Location: ../views/mis_eventos.php");
        exit;
    }

    $sql = "UPDATE evento SET centro_id = ?, fecha = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "isi", $centro_id, $fecha, $evento_id);


    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Evento actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el evento.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    header("Location: ../views/mis_eventos.php");
    exit;
}
