<?php
session_start();
require_once('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT 
            e.fecha,
            e.estado,
            d.nombre AS deporte,
            cd.nombre AS centro
        FROM inscripcion i
        JOIN evento e ON i.evento_id = e.id
        JOIN deporte d ON e.deporte_id = d.id
        JOIN centrodeportivo cd ON e.centro_id = cd.id
        WHERE i.usuario_id = ?
          AND i.estado = 'aceptada'
          AND e.estado = 'finalizado'
        ORDER BY e.fecha DESC";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-5">
    <h2 class="mb-4">Historial de eventos finalizados</h2>

    <?php if (mysqli_num_rows($resultado) === 0): ?>
        <div class="alert alert-info">No has participado aún en eventos finalizados.</div>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <thead class="table-success">
                <tr>
                    <th>Deporte</th>
                    <th>Centro</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($evento = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?= htmlspecialchars($evento['deporte']) ?></td>
                        <td><?= htmlspecialchars($evento['centro']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($evento['fecha'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
include('../includes/footer.php');
mysqli_close($connection);
?>