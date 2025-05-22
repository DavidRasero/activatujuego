<?php
session_start();
require_once('../config/database.php');
require_once('../models/Usuario.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["email"]);
    $contraseña = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($contraseña !== $confirm_password) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
        header("Location: ../views/register.php");
        exit;
    }
    $usuarioModel = new Usuario($connection);
    $usuarioExistente = $usuarioModel->buscarPorCorreo($correo);

    if ($usuarioExistente) {
        $_SESSION['error'] = "El correo ya está registrado.";
        header("Location: ../views/register.php");
        exit;
    }

    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
    if ($usuarioModel->registrar($nombre, $correo, $contraseña_hash)) {
        $_SESSION['success'] = "Usuario registrado correctamente.";
        header("Location: ../views/login.php");
        exit;
    } else {
        $_SESSION['error'] = "Error al registrar el usuario.";
        header("Location: ../views/register.php");
        exit;
    }
}
