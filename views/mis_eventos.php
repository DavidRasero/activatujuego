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

// Instancia del modelo
$eventoModel = new Evento($connection);
$eventos = $eventoModel->obtenerEventosDelOrganizador($usuario_id);
?>

<div class="container mt-5">
    <h2 class="mb-4">Mis eventos</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'];
        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (count($eventos) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
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
                                <?php
                                $pendientes = $eventoModel->contarInscripcionesPendientes($evento['id']);
                                ?>
                                <a href="editar_evento.php?evento_id=<?= $evento['id'] ?>"
                                    class="btn btn-sm btn-primary me-1">Editar</a>
                                <button
                                    onclick="confirmarEliminacionEvento(<?= $evento['id'] ?>, '<?= htmlspecialchars($evento['deporte']) ?>')"
                                    class="btn btn-sm btn-danger me-1">Eliminar</button>
                                <a href="gestionar_inscripciones.php?evento_id=<?= $evento['id'] ?>" class="btn btn-sm btn-success">
                                    Gestionar inscripciones <?= $pendientes >= 0 ? "($pendientes)" : "" ?>
                                </a>
                            <?php elseif ($evento['estado'] === 'completo'): ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="evento_id" value="<?= $evento['id'] ?>">
                                    <button type="button" class="btn btn-dark btn-sm"
                                        onclick="confirmarFinalizacionEvento(<?= $evento['id'] ?>)">
                                        La partida ya ha finalizado
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
    <?php else: ?>
        <div class="alert alert-info">No has creado ningún evento aún.</div>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>