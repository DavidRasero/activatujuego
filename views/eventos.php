<?php
session_start();
require_once('../includes/db.php');
require_once('../models/Evento.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo'];

$eventoModel = new Evento($connection);
$eventos = $eventoModel->obtenerEventosActivosExcluyendoOrganizador($usuario_id);
?>

<div class="container mt-5 animado" id="anim-eventos">
    <div class="encabezado-eventos text-center">
        <h1 class="titulo-eventos">
            Portal de eventos
        </h1>
        <a href="historial.php" class="btn-historial">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                class="bi bi-clock-history icono-historial" viewBox="0 0 16 16">
                <path
                    d="M8.515 3.5a.5.5 0 0 1 .5.5v4l3 1.5a.5.5 0 0 1-.5.866l-3.5-1.75A.5.5 0 0 1 8 8V4a.5.5 0 0 1 .5-.5z" />
                <path d="M8 16A8 8 0 1 0 0 8a8 8 0 0 0 8 8zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z" />
            </svg>
            Ver historial
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

    <div class="row">
        <?php foreach ($eventos as $row): ?>
            <?php
            $evento_id = $row['id'];
            $organizador_evento_id = $row['organizador_id'];
            $estadoInscripcion = $eventoModel->obtenerEstadoInscripcion($usuario_id, $evento_id);
            ?>

            <div class="col-md-6 mb-4">
                <div class="card shadow h-100">
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
                                <h5 class="card-title text-success fw-bold"><?= htmlspecialchars($row['deporte']) ?></h5>
                                <p class="card-text mb-1"><strong>Organizador:</strong>
                                    <?= htmlspecialchars($row['organizador']) ?></p>
                                <p class="card-text mb-1"><strong>Centro:</strong> <?= htmlspecialchars($row['centro']) ?>
                                </p>
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
                                        <a href="../controllers/inscribirse.php?evento_id=<?= $evento_id ?>"
                                            class="btn btn-success btn-sm">Inscribirse</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
include('../includes/footer.php');
mysqli_close($connection);
?>