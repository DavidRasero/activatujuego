<?php
session_start();
require_once('../config/database.php');
require_once('../models/Deporte.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de deporte no válido.";
    header("Location: gestionar_deportes.php");
    exit;
}

$deporte_id = intval($_GET['id']);

$deporteModel = new Deporte($connection);
$deporte = $deporteModel->obtenerPorId($deporte_id);

if (!$deporte) {
    $_SESSION['error'] = "Deporte no encontrado.";
    header("Location: gestionar_deportes.php");
    exit;
}
?>

<div class="container mt-5">
    <div class="encabezado-eventos text-center animado" id="anim-panel">
        <h1 class="titulo-eventos mb-4">
            <i class="bi bi-pencil-fill me-2"></i> Editar Deporte
        </h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="../controllers/actualizar_deporte.php" method="POST" enctype="multipart/form-data"
            class="text-start mx-auto" style="max-width: 550px;">
            <input type="hidden" name="id" value="<?= $deporte['id'] ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del deporte</label>
                <input type="text" name="nombre" class="form-control" required
                    value="<?= htmlspecialchars($deporte['nombre']) ?>">
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"
                    required><?= htmlspecialchars($deporte['descripcion']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="numero_jugadores" class="form-label">Número de jugadores</label>
                <input type="number" name="numero_jugadores" class="form-control" required min="1"
                    value="<?= htmlspecialchars($deporte['numero_jugadores']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen actual:</label><br>
                <?php if (!empty($deporte['imagen'])): ?>
                    <img src="<?= $deporte['imagen'] ?>" alt="Imagen actual" class="img-thumbnail"
                        style="max-height: 150px;">
                <?php else: ?>
                    <p class="text-muted">No hay imagen</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Nueva imagen (opcional)</label>
                <input type="file" name="imagen" class="form-control">
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Guardar cambios
                </button>
                <a href="gestionar_deportes.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>