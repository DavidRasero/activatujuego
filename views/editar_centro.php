<?php
session_start();
require_once('../includes/db.php');
require_once('../models/CentroDeportivo.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de centro no válido.";
    header("Location: gestionar_centros.php");
    exit;
}

$centro_id = intval($_GET['id']);
$centroModel = new CentroDeportivo($connection);
$centro = $centroModel->obtenerPorId($centro_id);

if (!$centro) {
    $_SESSION['error'] = "Centro no encontrado.";
    header("Location: gestionar_centros.php");
    exit;
}
?>

<div class="container mt-5">
    <div class="encabezado-eventos text-center animado" id="anim-panel">
        <h1 class="titulo-eventos mb-4">
            <i class="bi bi-geo-alt-fill me-2"></i> Editar Centro Deportivo
        </h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="../controllers/actualizar_centro.php" method="POST" class="text-start mx-auto"
            style="max-width: 450px;">
            <input type="hidden" name="id" value="<?= $centro['id'] ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required
                    value="<?= htmlspecialchars($centro['nombre']) ?>">
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" required
                    value="<?= htmlspecialchars($centro['direccion']) ?>">
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Guardar cambios
                </button>
                <a href="gestionar_centros.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>