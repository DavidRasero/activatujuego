<?php
session_start();
require_once('../config/database.php');
require_once('../models/Evento.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    $_SESSION['error'] = "No tienes permiso para realizar esta acción.";
    header("Location: ../index.php");
    exit;
}

$evento_id = filter_input(INPUT_GET, 'evento_id', FILTER_VALIDATE_INT);
$organizador_id = $_SESSION['usuario_id'];

if (!$evento_id) {
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

if ($eventoModel->finalizarEvento($evento_id)) {
    $_SESSION['success'] = "Evento marcado como finalizado.";
} else {
    $_SESSION['error'] = "No se pudo finalizar el evento.";
}

header("Location: ../views/mis_eventos.php");
exit;
