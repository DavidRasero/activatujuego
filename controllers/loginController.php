<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT * FROM usuario WHERE correo = ?";
    if ($stmt = mysqli_prepare($connection, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($resultado);

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['tipo'] = $usuario['tipo']; 

            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['error'] = "Correo o contraseña incorrectos.";
            header("Location: ../views/login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Error en la consulta.";
        header("Location: ../views/login.php");
        exit;
    }
}
?>
