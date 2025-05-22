<?php
session_start();
require_once('../includes/db.php');
include('../includes/header.php');

// Solo admins pueden acceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>

<div class="container mt-5">
    <div class="encabezado-eventos text-center animado" id="anim-panel">
        <h1 class="titulo-eventos mb-4">
            <i class="bi bi-plus-circle me-2"></i> Crear nuevo deporte
        </h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="../controllers/crear_deporte.php" enctype="multipart/form-data"
            class="text-start mx-auto" style="max-width: 550px;">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del deporte</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="mb-3">
                <label for="numero_jugadores" class="form-label">Número máximo de jugadores</label>
                <input type="number" class="form-control" id="numero_jugadores" name="numero_jugadores" required
                    min="1">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen representativa</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Crear deporte
                </button>
                <a href="gestionar_deportes.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>