<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Inscripcion.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$inscripcionModel = new Inscripcion($connection);

// Obtener eventos finalizados del usuario
$eventos = $inscripcionModel->obtenerEventosFinalizadosPorUsuario($usuario_id);
?>

<div class="container mt-5 animado" id="anim-eventos">
    <div class="encabezado-eventos text-center">
        <h1 class="titulo-eventos">
            Historial de eventos
        </h1>
        <a href="eventos.php" class="btn-historial">
            <i class="bi bi-calendar-event"></i>
            Ver portal de eventos
        </a>
        <a href="../index.php" class="btn-historial">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>

    <?php if (empty($eventos)): ?>
        <div class="alert alert-info">No has participado en eventos finalizados.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($eventos as $evento): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-body">
                            <h5 class="card-title text-success"><?= htmlspecialchars($evento['deporte']) ?></h5>
                            <p class="card-text"><strong>Centro:</strong> <?= htmlspecialchars($evento['centro']) ?></p>
                            <p class="card-text"><strong>Dirección:</strong> <?= htmlspecialchars($evento['direccion']) ?></p>
                            <p class="card-text"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($evento['fecha'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>