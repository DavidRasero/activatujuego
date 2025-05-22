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
    <div class="encabezado-eventos text-center animado" id="anim-panel">
        <h1 class="titulo-eventos mb-4">
            <i class="bi bi-geo-alt-fill me-2"></i> Crear Centro Deportivo
        </h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="../controllers/crear_centro.php" method="POST" class="text-start mx-auto" style="max-width: 450px;">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del centro</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" required>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Crear centro
                </button>
                <a href="gestionar_centros.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
