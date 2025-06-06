<?php
session_start();
require_once('../config/database.php');
require_once('../models/Usuario.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $usuario_id = intval($_GET['id']);
    $usuarioModel = new Usuario($connection);

    if ($usuarioModel->ascenderJugador($usuario_id)) {
        $_SESSION['success'] = "Usuario ascendido a organizador correctamente.";
    } else {
        $_SESSION['error'] = "No se pudo ascender al usuario o ya es organizador.";
    }
} else {
    $_SESSION['error'] = "ID de usuario no válido.";
}

header("Location: ../views/usuarios.php");
exit;
