<?php
session_start();
require_once('../includes/db.php');
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
    <h2 class="mb-4">Editar Deporte</h2>

    <form action="../controllers/actualizar_deporte.php" method="POST" enctype="multipart/form-data">
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
            <?php if (!empty($deporte['imagen']) && file_exists($deporte['imagen'])): ?>
                <img src="<?= $deporte['imagen'] ?>" alt="Imagen actual" class="img-thumbnail" style="max-height: 150px;">
            <?php else: ?>
                <p class="text-muted">No hay imagen</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Nueva imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="gestionar_deportes.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include('../includes/footer.php'); ?>