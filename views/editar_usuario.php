<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Usuario.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de usuario no válido.";
    header("Location: usuarios.php");
    exit;
}

$id = intval($_GET['id']);
$usuarioModel = new Usuario($connection);
$usuario = $usuarioModel->obtenerPorId($id);

if (!$usuario) {
    $_SESSION['error'] = "Usuario no encontrado.";
    header("Location: usuarios.php");
    exit;
}
?>

<div class="container mt-5">
    <div class="encabezado-eventos text-center animado" id="anim-panel">
        <h1 class="titulo-eventos mb-4">
            <i class="bi bi-person-lines-fill me-2"></i> Editar Usuario
        </h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="../controllers/editar_usuario.php" class="text-start mx-auto"
            style="max-width: 500px;">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre"
                    value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo"
                    value="<?= htmlspecialchars($usuario['correo']) ?>" required>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i> Guardar cambios
                </button>
                <a href="usuarios.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>