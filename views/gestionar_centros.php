<?php
session_start();
require_once('../includes/db.php');
require_once('../models/CentroDeportivo.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$centroModel = new CentroDeportivo($connection);
$centros = $centroModel->obtenerTodos();
?>

<div class="container mt-5">
    <h2 class="mb-4">Gestión de Centros Deportivos</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <a href="crear_centro.php" class="btn btn-success mb-3">Agregar centro deportivo</a>

    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($centros as $centro): ?>
                <tr>
                    <td><?= htmlspecialchars($centro['nombre']) ?></td>
                    <td><?= htmlspecialchars($centro['direccion']) ?></td>
                    <td>
                        <a href="editar_centro.php?id=<?= $centro['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm"
                            onclick="confirmarEliminacionCentro(<?= $centro['id'] ?>, '<?= htmlspecialchars($centro['nombre']) ?>')">
                            Eliminar
                        </button>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>