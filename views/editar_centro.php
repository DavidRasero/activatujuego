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
    <h2 class="mb-4">Editar Centro Deportivo</h2>

    <form action="../controllers/actualizar_centro.php" method="POST">
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

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="gestionar_centros.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include('../includes/footer.php'); ?>