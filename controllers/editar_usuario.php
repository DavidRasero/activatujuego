<?php
session_start();
require_once('../config/database.php');
require_once('../models/Usuario.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);

    if (empty($nombre) || empty($correo)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../views/editar_usuario.php?id=$id");
        exit;
    }

    $usuarioModel = new Usuario($connection);
    $usuarioExistente = $usuarioModel->obtenerPorId($id);

    if (!$usuarioExistente) {
        $_SESSION['error'] = "El usuario no existe.";
        header("Location: ../views/usuarios.php");
        exit;
    }

    if ($usuarioModel->actualizarUsuario($id, $nombre, $correo)) {
        $_SESSION['success'] = "Usuario actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el usuario.";
    }

    header("Location: ../views/usuarios.php");
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
