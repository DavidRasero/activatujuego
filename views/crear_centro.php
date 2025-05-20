<?php
session_start();
require_once('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Crear Centro Deportivo</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="../controllers/crear_centro.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del centro</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" name="direccion" required>
        </div>

        <button type="submit" class="btn btn-success">Crear centro</button>
        <a href="gestionar_centros.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include('../includes/footer.php'); ?>