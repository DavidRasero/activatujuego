<?php
session_start();
require_once('../config/database.php');
require_once('../models/Deporte.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$deporteModel = new Deporte($connection);
$deportes = $deporteModel->obtenerTodos();

$sql = "SELECT d.nombre, COUNT(e.id) AS eventos
        FROM evento e
        JOIN deporte d ON e.deporte_id = d.id
        GROUP BY d.id
        ORDER BY eventos DESC
        LIMIT 5";
$resultadoGrafico = mysqli_query($connection, $sql);

$labels = [];
$valores = [];

while ($fila = mysqli_fetch_assoc($resultadoGrafico)) {
    $labels[] = $fila['nombre'];
    $valores[] = $fila['eventos'];
}

?>

<div class="container mt-5 animado" id="anim-eventos">
    <div class="encabezado-eventos text-center mb-4">
        <h1 class="titulo-eventos">Gestión de Deportes</h1>
        <a href="crear_deporte.php" class="btn-historial">
            <i class="bi bi-pencil-square"></i>
            Agregar deporte
        </a>
        <a href="../index.php" class="btn-historial">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped tabla-personalizada">
            <thead class="table-success">
                <tr>
                    <th>Nombre</th>
                    <th>Nº Jugadores</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deportes as $deporte): ?>
                    <tr>
                        <td><?= htmlspecialchars($deporte['nombre']) ?></td>
                        <td><?= $deporte['numero_jugadores'] ?></td>
                        <td>
                            <?php if (!empty($deporte['imagen'])): ?>
                                <img src="/public/img/<?= htmlspecialchars($deporte['imagen']) ?>" style="height: 50px;">
                            <?php else: ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="editar_deporte.php?id=<?= $deporte['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <button class="btn btn-danger btn-sm"
                                onclick="confirmarEliminacionDeporte(<?= $deporte['id'] ?>, '<?= htmlspecialchars($deporte['nombre']) ?>')">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        <div class="encabezado-eventos text-center mb-4">
            <h3 class="titulo-eventos">Top 5 deportes con más eventos</h3>
            <canvas id="graficoDeportes" style="max-height: 400px;"></canvas>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('graficoDeportes').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Número de eventos',
                    data: <?= json_encode($valores) ?>,
                    backgroundColor: 'white',
                    borderColor: 'rgb(0, 0, 0)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/public/js/script.js"></script>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const ids = ['anim-carrusel', 'anim-panel', 'anim-eventos', 'anim-historial'];

        ids.forEach(id => {
            const el = document.getElementById(id);
            if (el !== null) {
                el.classList.add('visible');
            }
        });
    });
</script>


</body>
</html>