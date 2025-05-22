<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Evento.php');
include('../includes/header.php');

// Comprobación de sesión
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$eventoModel = new Evento($connection);
$eventos = $eventoModel->obtenerEventosDelOrganizador($usuario_id);
?>

<div class="container mt-5 animado" id="anim-panel">
    <div class="encabezado-eventos text-center p-4">
        <h2 class="titulo-eventos mb-3">
            <i class="bi bi-clipboard-plus me-2"></i> Mis eventos
        </h2>
        <a href="../index.php" class="btn-historial">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
            unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (count($eventos) > 0): ?>
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-success">
                        <tr class="text-center">
                            <th>Deporte</th>
                            <th>Centro</th>
                            <th>Fecha</th>
                            <th>Jugadores</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eventos as $evento): ?>
                            <tr>
                                <td><?= htmlspecialchars($evento['deporte']) ?></td>
                                <td><?= htmlspecialchars($evento['centro']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($evento['fecha'])) ?></td>
                                <td><?= $evento['jugadores_aceptados'] ?>/<?= $evento['numero_jugadores'] ?></td>
                                <td><?= ucfirst($evento['estado']) ?></td>
                                <td>
                                    <?php if ($evento['estado'] === 'activo'): ?>
                                        <?php $pendientes = $eventoModel->contarInscripcionesPendientes($evento['id']); ?>
                                        <a href="editar_evento.php?evento_id=<?= $evento['id'] ?>"
                                            class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <button
                                            onclick="confirmarEliminacionEvento(<?= $evento['id'] ?>, '<?= htmlspecialchars($evento['deporte']) ?>')"
                                            class="btn btn-sm btn-outline-danger me-1">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                        <a href="gestionar_inscripciones.php?evento_id=<?= $evento['id'] ?>"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-people-fill"></i> Inscripciones
                                            <?= $pendientes >= 0 ? "($pendientes)" : "" ?>
                                        </a>
                                    <?php elseif ($evento['estado'] === 'completo'): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="evento_id" value="<?= $evento['id'] ?>">
                                            <button type="button" class="btn btn-sm btn-dark"
                                                onclick="confirmarFinalizacionEvento(<?= $evento['id'] ?>)">
                                                <i class="bi bi-flag-fill"></i> Finalizar evento
                                            </button>
                                        </form>
                                    <?php elseif ($evento['estado'] === 'finalizado'): ?>
                                        <span class="badge bg-secondary">Finalizado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-4">No has creado ningún evento aún.</div>
        <?php endif; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>