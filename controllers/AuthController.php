<?php
session_start();
require_once '../config/database.php';
require_once '../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $usuario = Usuario::buscarPorEmail($email);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            header("Location: ../index.php"); 
        } else {
            echo "Usuario o contraseña incorrectos.";
        }
    }
}
