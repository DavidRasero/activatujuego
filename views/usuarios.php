<?php
session_start();
require_once('../config/database.php');
require_once('../models/Usuario.php');
include('../includes/header.php');

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$usuarioModel = new Usuario($connection);
$usuarios = $usuarioModel->obtenerTodos();
?>

<div class="container mt-5 animado" id="anim-eventos">
    <div class="encabezado-eventos text-center mb-4">
        <h1 class="titulo-eventos">
            <i class="bi bi-people-fill me-2"></i> Gestión de Usuarios
        </h1>
        <a href="../index.php" class="btn-historial">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'];
        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped tabla-personalizada">
            <thead class="table-success text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['correo']) ?></td>
                        <td><?= ucfirst($usuario['tipo']) ?></td>
                        <td class="text-center">
                            <?php if ($usuario['tipo'] === 'jugador'): ?>
                                <a href="../controllers/ascender.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-arrow-up-square"></i> Ascender
                                </a>
                            <?php endif; ?>
                            <a href="../views/editar_usuario.php?id=<?= $usuario['id'] ?>"
                                class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <button class="btn btn-danger btn-sm"
                                onclick="confirmarEliminacion(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>')">
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