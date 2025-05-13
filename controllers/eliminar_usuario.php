<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $usuario_id = intval($_GET['id']);

    if ($usuario_id == $_SESSION['usuario_id']) {
        $_SESSION['error'] = "No puedes eliminar a un admin.";
        header("Location: ../views/usuarios.php");
        exit;
    }

    $sql = "DELETE FROM usuario WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Usuario eliminado correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar el usuario.";
    }

    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error'] = "ID de usuario no válido.";
}

mysqli_close($connection);
header("Location: ../views/usuarios.php");
exit;
