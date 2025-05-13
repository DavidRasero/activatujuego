<?php
session_start();
require_once('../includes/db.php');
include('../includes/header.php');

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

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
        WHERE e.estado = 'activo'
        ORDER BY e.fecha ASC";

$resultado = mysqli_query($connection, $sql);
?>

<div class="container mt-5">
    <h2 class="mb-4">Eventos disponibles</h2>

    <div class="mb-3">
        <a href="historial.php" class="btn btn-success btn-sm">Ver historial</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

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
        <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?= htmlspecialchars($row['deporte']) ?></td>
                <td><?= htmlspecialchars($row['centro']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
                <td><?= $row['jugadores_aceptados'] ?>/<?= $row['numero_jugadores'] ?></td>

                <?php
                    $evento_id = $row['id'];
                    $estadoInscripcion = null;

                    $sqlInscripcion = "SELECT estado FROM inscripcion WHERE usuario_id = ? AND evento_id = ?";
                    $stmt = mysqli_prepare($connection, $sqlInscripcion);
                    mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $evento_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $estadoInscripcion);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);
                ?>

                <td>
                    <?php if ($estadoInscripcion === 'aceptada'): ?>
                        <span class="text-success fw-bold">Aceptado</span>
                    <?php elseif ($estadoInscripcion === 'rechazada'): ?>
                        <span class="text-danger fw-bold">Rechazado</span>
                    <?php elseif ($estadoInscripcion === 'pendiente'): ?>
                        <span class="text-warning fw-bold">Pendiente</span>
                    <?php else: ?>
                        <span class="text-muted">No inscrito</span>
                    <?php endif; ?>
                </td>

                <td>
                    <?php if ($estadoInscripcion === 'pendiente'): ?>
                        <button class="btn btn-warning btn-sm" disabled>Pendiente</button>
                    <?php elseif ($estadoInscripcion === 'aceptada'): ?>
                        <button class="btn btn-secondary btn-sm" disabled>Ya inscrito</button>
                    <?php elseif ($estadoInscripcion === 'rechazada'): ?>
                        <button class="btn btn-danger btn-sm" disabled>Rechazado</button>
                    <?php else: ?>
                        <a href="../controllers/inscribirse.php?evento_id=<?= $evento_id ?>" class="btn btn-success btn-sm">
                            Inscribirse
                        </a>
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
