<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evento_id = intval($_POST['evento_id']);
    $organizador_id = $_SESSION['usuario_id'];
    $centro_id = intval($_POST['centro_id']);
    $deporte_id = intval($_POST['deporte_id']);
    $fecha = trim($_POST['fecha']);

    if (!$evento_id || !$centro_id || !$deporte_id || empty($fecha)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../views/editar_evento.php?evento_id=$evento_id");
        exit;
    }

    $fecha_evento = strtotime($fecha);
    if ($fecha_evento <= time()) {
        $_SESSION['error'] = "La fecha del evento debe ser posterior a la actual.";
        header("Location: ../views/editar_evento.php?evento_id=$evento_id");
        exit;
    }

    $stmt = $connection->prepare("SELECT id FROM evento WHERE id = ? AND organizador_id = ? AND estado = 'activo'");
    $stmt->bind_param("ii", $evento_id, $organizador_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();

    if ($resultado->num_rows === 0) {
        $_SESSION['error'] = "No tienes permiso para editar este evento.";
        header("Location: ../views/mis_eventos.php");
        exit;
    }

    $stmt = $connection->prepare("UPDATE evento SET centro_id = ?, fecha = ? WHERE id = ?");
    $stmt->bind_param("isi", $centro_id, $fecha, $evento_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Evento actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el evento.";
    }

    $stmt->close();
    $connection->close();
    header("Location: ../views/mis_eventos.php");
    exit;
}
?>