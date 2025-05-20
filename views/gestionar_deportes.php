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

<div class="container mt-5">
    <h2 class="mb-4">Gestión de Deportes</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <a href="crear_deporte.php" class="btn btn-success mb-3">Agregar deporte</a>

    <table class="table table-bordered table-striped">
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
                        <a href="editar_deporte.php?id=<?= $deporte['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm"
                            onclick="confirmarEliminacionDeporte(<?= $deporte['id'] ?>, '<?= htmlspecialchars($deporte['nombre']) ?>')">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>