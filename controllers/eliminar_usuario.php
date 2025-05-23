<?php
session_start();
require_once('../config/database.php');
require_once('../models/Usuario.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $usuario_id = intval($_GET['id']);

    if ($usuario_id === $_SESSION['usuario_id']) {
        $_SESSION['error'] = "No puedes eliminar tu propia cuenta de administrador.";
        header("Location: ../views/usuarios.php");
        exit;
    }

    $usuarioModel = new Usuario($connection);
    $usuario = $usuarioModel->obtenerPorId($usuario_id);

    if (!$usuario) {
        $_SESSION['error'] = "El usuario no existe.";
        header("Location: ../views/usuarios.php");
        exit;
    }

    if ($usuarioModel->eliminarPorId($usuario_id)) {
        $_SESSION['success'] = "Usuario eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el usuario.";
    }
} else {
    $_SESSION['error'] = "ID de usuario no válido.";
}

header("Location: ../views/usuarios.php");
exit;
