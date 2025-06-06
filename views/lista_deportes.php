<?php
session_start();
require_once('../config/database.php');
require_once('../models/Deporte.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$deporteModel = new Deporte($connection);
$deportes = $deporteModel->obtenerTodos();
?>

<div class="container mt-5 animado" id="anim-eventos">
    <div class="encabezado-eventos text-center">
        <h1 class="titulo-eventos">Lista de Deportes</h1>
        <a href="../index.php" class="btn-historial">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>

    <?php if (empty($deportes)): ?>
        <div class="alert alert-info">No hay deportes registrados.</div>
    <?php else: ?>
        <div class="row justify-content-center">
            <?php foreach ($deportes as $deporte): ?>
                <div class="col-md-8 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <?php if (!empty($deporte['imagen'])): ?>
                                    <img src="/public/img/<?= htmlspecialchars($deporte['imagen']) ?>"
                                        class="img-fluid rounded-start p-2" alt="Imagen del deporte">
                                <?php else: ?>
                                    <img src="/public/img/default.jpg" class="img-fluid rounded-start p-2"
                                        alt="Imagen por defecto">
                                <?php endif; ?>

                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title text-success"><?= htmlspecialchars($deporte['nombre']) ?></h5>
                                    <p class="card-text"><strong>Descripción:</strong>
                                        <?= htmlspecialchars($deporte['descripcion']) ?></p>
                                    <p class="card-text"><strong>Número de jugadores:</strong>
                                        <?= $deporte['numero_jugadores'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include('../includes/footer.php'); ?>