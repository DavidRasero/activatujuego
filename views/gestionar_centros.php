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

<div class="container mt-5 animado" id="anim-eventos">
    <div class="encabezado-eventos text-center mb-4">
        <h1 class="titulo-eventos">Gestión de Centros Deportivos</h1>
        <a href="crear_centro.php" class="btn-historial">
            <i class="bi bi-pencil-square"></i>
            Agregar centro deportivo
        </a>
        <a href="../index.php" class="btn-historial">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped tabla-personalizada">
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
                            <a href="editar_centro.php?id=<?= $centro['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <button class="btn btn-danger btn-sm"
                                onclick="confirmarEliminacionCentro(<?= $centro['id'] ?>, '<?= htmlspecialchars($centro['nombre']) ?>')">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>