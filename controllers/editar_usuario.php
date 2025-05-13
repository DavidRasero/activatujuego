<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);

    $sql = "UPDATE usuario SET nombre = ?, correo = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $correo, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Usuario actualizado correctamente.";
    } else {
        $_SESSION['error'] = "Error al actualizar el usuario.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    header("Location: ../views/usuarios.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT id, nombre, correo FROM usuario WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
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
