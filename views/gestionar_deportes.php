<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Deporte.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$deporteModel = new Deporte($connection);
$deportes = $deporteModel->obtenerTodos();
?>

<div class="container mt-5 animado" id="anim-eventos">
    <div class="encabezado-eventos text-center mb-4">
        <h1 class="titulo-eventos">Gestión de Deportes</h1>
        <a href="crear_deporte.php" class="btn-historial">
            <i class="bi bi-pencil-square"></i>
            Agregar deporte
        </a>
        <a href="../index.php" class="btn-historial">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>>

    <div class="table-responsive">
        <table class="table table-bordered table-striped tabla-personalizada">
            <thead class="table-success">
                <tr>
                    <th>Nombre</th>
                    <th>Nº Jugadores</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deportes as $deporte): ?>
                    <tr>
                        <td><?= htmlspecialchars($deporte['nombre']) ?></td>
                        <td><?= $deporte['numero_jugadores'] ?></td>
                        <td>
                            <?php if (!empty($deporte['imagen'])): ?>
                                <img src="<?= $deporte['imagen'] ?>" style="height: 50px;">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="editar_deporte.php?id=<?= $deporte['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <button class="btn btn-danger btn-sm"
                                onclick="confirmarEliminacionDeporte(<?= $deporte['id'] ?>, '<?= htmlspecialchars($deporte['nombre']) ?>')">
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