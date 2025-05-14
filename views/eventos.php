<?php
session_start();
require_once('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo'];

$sql = "SELECT 
            e.id,
            e.fecha,
            e.estado,
            e.jugadores_aceptados,
            e.organizador_id,
            d.nombre AS deporte,
            d.numero_jugadores,
            d.imagen AS imagen_deporte,
            cd.nombre AS centro,
            cd.direccion AS direccion,
            u.nombre AS organizador
        FROM evento e
        JOIN deporte d ON e.deporte_id = d.id
        JOIN centrodeportivo cd ON e.centro_id = cd.id
        JOIN usuario u ON e.organizador_id = u.id
        WHERE e.estado = 'activo'
        AND e.organizador_id != $usuario_id
        ORDER BY e.fecha ASC";




$resultado = mysqli_query($connection, $sql);
?>

<div class="container mt-5">
    <h2 class="mb-4">Portal de eventos</h2>
    <h6 class="mb-4">Si eres organizador no podrás ver tus eventos aquí, ver en <a href="mis_eventos.php">MIS EVENTOS</a></h6>

    <div class="mb-3">
        <a href="historial.php" class="btn btn-success btn-sm">Ver historial</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'];
        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
            
            <?php
            $evento_id = $row['id'];
            $organizador_evento_id = $row['organizador_id'];
            $estadoInscripcion = null;

            $sqlInscripcion = "SELECT estado FROM inscripcion WHERE usuario_id = ? AND evento_id = ?";
            $stmt = mysqli_prepare($connection, $sqlInscripcion);
            mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $evento_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $estadoInscripcion);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            ?>

            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card h-100 shadow">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <?php if (!empty($row['imagen_deporte'])): ?>
                                    <img src="<?= $row['imagen_deporte'] ?>" class="img-fluid rounded-start p-2"
                                        style="max-height: 100%; object-fit: cover;" alt="Imagen del deporte">
                                <?php else: ?>
                                    <img src="../public/img/deportes/default.jpg" class="img-fluid rounded-start p-2"
                                        style="max-height: 100%; object-fit: cover;" alt="Imagen por defecto">
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title text-success fw-bold"><?= htmlspecialchars($row['deporte']) ?>
                                    </h5>
                                    <p class="card-text mb-1"><strong>Organizador:</strong>
                                        <?= htmlspecialchars($row['organizador']) ?></p>
                                    <p class="card-text mb-1"><strong>Centro:</strong>
                                        <?= htmlspecialchars($row['centro']) ?></p>
                                    <p class="card-text mb-1"><strong>Dirección:</strong>
                                        <?= htmlspecialchars($row['direccion']) ?></p>
                                    <p class="card-text mb-1"><strong>Fecha:</strong>
                                        <?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></p>
                                    <p class="card-text mb-1"><strong>Jugadores:</strong>
                                        <?= $row['jugadores_aceptados'] ?>/<?= $row['numero_jugadores'] ?></p>
                                    <p class="card-text mb-2">
                                        <strong>Estado:</strong>
                                        <?php if ($estadoInscripcion === 'aceptada'): ?>
                                            <span class="text-success">Aceptado</span>
                                        <?php elseif ($estadoInscripcion === 'rechazada'): ?>
                                            <span class="text-danger">Rechazado</span>
                                        <?php elseif ($estadoInscripcion === 'pendiente'): ?>
                                            <span class="text-warning">Pendiente</span>
                                        <?php else: ?>
                                            <span class="text-muted">No inscrito</span>
                                        <?php endif; ?>
                                    </p>

                                    <?php if ($tipo_usuario === 'admin'): ?>
                                    <span class="text-muted">Un admin no puede inscribirse</span>
                                        <?php elseif ($usuario_id === $organizador_evento_id): ?>
                                    <span class="text-muted">Eres el organizador</span>
                                    <?php else: ?>
                                        <?php if ($estadoInscripcion === 'pendiente'): ?>
                                            <button class="btn btn-warning btn-sm" disabled>Pendiente</button>
                                        <?php elseif ($estadoInscripcion === 'aceptada'): ?>
                                            <button class="btn btn-secondary btn-sm" disabled>Ya inscrito</button>
                                        <?php elseif ($estadoInscripcion === 'rechazada'): ?>
                                            <button class="btn btn-danger btn-sm" disabled>Rechazado</button>
                                            <?php else: ?>
                                                <a href="../controllers/inscribirse.php?evento_id=<?= $evento_id ?>" class="btn btn-success btn-sm">Inscribirse</a>
                                            <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>

<?php
include('../includes/footer.php');
mysqli_close($connection);
?>