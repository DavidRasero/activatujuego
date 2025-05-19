<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Usuario.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$usuarioModel = new Usuario($connection);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);

    if ($usuarioModel->actualizarUsuario($id, $nombre, $correo)) {
        $_SESSION['success'] = "Usuario actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el usuario.";
    }

    header("Location: ../views/usuarios.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $usuario = $usuarioModel->obtenerPorId($id);

    if (!$usuario) {
        $_SESSION['error'] = "Usuario no encontrado.";
        header("Location: ../views/usuarios.php");
        exit;
    }
} else {
    $_SESSION['error'] = "ID de usuario no válido.";
    header("Location: ../views/usuarios.php");
    exit;
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2>Editar usuario</h2>
    <form method="POST" action="editar_usuario.php">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="../views/usuarios.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
