<?php
session_start();
require_once('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

$organizador_id = $_SESSION['usuario_id'];

$sql = "SELECT 
            e.id,
            e.fecha,
            e.estado,
            e.jugadores_aceptados,
            d.nombre AS deporte,
            d.numero_jugadores,
            cd.nombre AS centro
        FROM evento e
        JOIN deporte d ON e.deporte_id = d.id
        JOIN centrodeportivo cd ON e.centro_id = cd.id
        WHERE e.organizador_id = ?
        ORDER BY e.fecha ASC";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $organizador_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-5">
    <h2 class="mb-4">Mis eventos creados</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'];
        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <table class="table table-striped table-bordered">
        <thead class="table-success">
            <tr>
                <th>Deporte</th>
                <th>Centro</th>
                <th>Fecha</th>
                <th>Jugadores</th>
                <th>Estado</th>
                <th>Inscripciones</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($evento = mysqli_fetch_assoc($resultado)): ?>
                <?php
                $evento_id = $evento['id'];

                $sql_pendientes = "SELECT COUNT(*) FROM inscripcion WHERE evento_id = ? AND estado = 'pendiente'";
                $stmt_p = mysqli_prepare($connection, $sql_pendientes);
                mysqli_stmt_bind_param($stmt_p, "i", $evento_id);
                mysqli_stmt_execute($stmt_p);
                mysqli_stmt_bind_result($stmt_p, $pendientes);
                mysqli_stmt_fetch($stmt_p);
                mysqli_stmt_close($stmt_p);
                ?>
                <tr>
                    <td><?= htmlspecialchars($evento['deporte']) ?></td>
                    <td><?= htmlspecialchars($evento['centro']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($evento['fecha'])) ?></td>
                    <td><?= $evento['jugadores_aceptados'] ?>/<?= $evento['numero_jugadores'] ?></td>
                    <td><?= ucfirst($evento['estado']) ?></td>
                    <td><?= $pendientes ?> pendientes</td>
                    <td>
                        <?php if ($evento['estado'] === 'finalizado'): ?>
                            <span class="text-muted">Evento finalizado</span>

                        <?php elseif ($evento['estado'] === 'completo'): ?>
                            <a href="../controllers/finalizar_evento.php?evento_id=<?= $evento_id ?>"
                                class="btn btn-warning btn-sm mb-1">
                                La partida ya ha finalizado
                            </a>
                            <br>
                            <a href="gestionar_inscripciones.php?evento_id=<?= $evento_id ?>"
                                class="btn btn-primary btn-sm">Gestionar</a>

                        <?php elseif ($evento['estado'] === 'activo'): ?>
                            <a href="gestionar_inscripciones.php?evento_id=<?= $evento_id ?>"
                                class="btn btn-primary btn-sm mb-1">Gestionar</a>
                            <br>
                            <a href="editar_evento.php?evento_id=<?= $evento_id ?>"
                                class="btn btn-warning btn-sm mb-1">Editar</a>
                            <br>
                            <button onclick="confirmarEliminacionEvento(<?= $evento_id ?>)"
                                class="btn btn-danger btn-sm">Eliminar</button>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
include('../includes/footer.php');
mysqli_close($connection);
?>