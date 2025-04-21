<?php
// controllers/registerController.php

require_once('../includes/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $usuario = $_POST["email"];
    $contraseña = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($contraseña !== $confirm_password) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
        mysqli_close($connection);
        header("Location: ../views/register.php");
        exit;
    }

    // Comprobar si el correo ya existe
    $check_sql = "SELECT id FROM usuario WHERE correo = ?";
    if ($check_stmt = mysqli_prepare($connection, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "s", $usuario);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $_SESSION['error'] = "Este correo ya está registrado.";
            mysqli_stmt_close($check_stmt);
            mysqli_close($connection);
            header("Location: ../views/register.php");
            exit;
        }

        mysqli_stmt_close($check_stmt);
    }

    // Si no existe, lo insertamos
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuario (nombre, correo, contraseña) VALUES (?, ?, ?)";
    $redireccion = "../views/register.php";

    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $usuario, $contraseña_hash);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Usuario registrado correctamente.";
            $redireccion = "../views/login.php";
        } else {
            $_SESSION['error'] = "Error al registrar: " . mysqli_error($connection);
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Error al preparar la consulta.";
    }

    mysqli_close($connection);
    header("Location: $redireccion");
    exit();
}
