<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Usuario.php');
include('../includes/header.php');

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Crear instancia del modelo
$usuarioModel = new Usuario($connection);
$usuarios = $usuarioModel->obtenerTodos();
?>

<div class="container mt-5">
    <h2 class="mb-4">Gestión de usuarios</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <table class="table table-striped table-bordered">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['correo']) ?></td>
                    <td><?= htmlspecialchars($usuario['tipo']) ?></td>
                    <td>
                        <?php if ($usuario['tipo'] === 'jugador'): ?>
                            <a href="../controllers/ascender.php?id=<?= $usuario['id'] ?>"
                               class="btn btn-warning btn-sm">Ascender</a>
                        <?php endif; ?>
                        <a href="../controllers/editar_usuario.php?id=<?= $usuario['id'] ?>"
                           class="btn btn-primary btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm"
                            onclick="confirmarEliminacion(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>')">
                            Eliminar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include('../includes/footer.php');
?>
