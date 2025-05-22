<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Evento.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

$eventoModel = new Evento($connection);

if (!isset($_GET['evento_id'])) {
    $_SESSION['error'] = "Evento no válido.";
    header("Location: mis_eventos.php");
    exit;
}

$evento_id = intval($_GET['evento_id']);
$usuario_id = $_SESSION['usuario_id'];

$eventoInfo = $eventoModel->obtenerEventoPorId($evento_id, $usuario_id);
if (!$eventoInfo) {
    $_SESSION['error'] = "No tienes permiso para gestionar este evento.";
    header("Location: mis_eventos.php");
    exit;
}

$maxJugadores = $eventoModel->obtenerMaxJugadoresPorDeporte($eventoInfo['deporte_id']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inscripcion_id = intval($_POST['inscripcion_id']);
    $accion = $_POST['accion'];

    if ($accion === 'aceptar') {
        $resultado = $eventoModel->aceptarInscripcion($evento_id, $inscripcion_id, $maxJugadores);
        $_SESSION[$resultado['tipo']] = $resultado['mensaje'];
    } elseif ($accion === 'rechazar') {
        $eventoModel->rechazarInscripcion($inscripcion_id);
        $_SESSION['success'] = "Inscripción rechazada.";
    }

    header("Location: gestionar_inscripciones.php?evento_id=$evento_id");
    exit;
}

$inscripciones = $eventoModel->obtenerInscripcionesEvento($evento_id);
?>

<div class="container mt-5 animado" id="anim-eventos">
    <div class="encabezado-eventos text-center mb-4">
        <h1 class="titulo-eventos">Inscripciones al evento</h1>
        <a href="mis_eventos.php" class="btn-historial">
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
            <thead class="table-success">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inscripciones as $insc): ?>
                    <tr>
                        <td><?= htmlspecialchars($insc['nombre']) ?></td>
                        <td><?= htmlspecialchars($insc['correo']) ?></td>
                        <td><?= ucfirst($insc['estado']) ?></td>
                        <td>
                            <?php if ($insc['estado'] === 'pendiente'): ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="inscripcion_id" value="<?= $insc['id'] ?>">
                                    <button type="submit" name="accion" value="aceptar"
                                        class="btn btn-success btn-sm">Aceptar</button>
                                    <button type="submit" name="accion" value="rechazar"
                                        class="btn btn-danger btn-sm">Rechazar</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">Sin acciones</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>