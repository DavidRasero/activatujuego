<?php
session_start();
require_once('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

$deportes = mysqli_query($connection, "SELECT id, nombre FROM deporte");
$centros = mysqli_query($connection, "SELECT id, nombre FROM centrodeportivo");
?>

<div class="container mt-5 animado" id="anim-panel">
    <div class="encabezado-eventos text-center p-4">
        <h2 class="titulo-eventos mb-3">
            <i class="bi bi-pencil-square me-2"></i> Crear nuevo evento
        </h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
            unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="../controllers/crear_evento.php" method="POST" class="text-start mx-auto"
            style="max-width: 600px;">
            <div class="mb-3">
                <label for="deporte" class="form-label">Deporte</label>
                <select name="deporte_id" id="deporte" class="form-select" required>
                    <option value="">Selecciona un deporte</option>
                    <?php while ($d = mysqli_fetch_assoc($deportes)): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="centro" class="form-label">Centro Deportivo</label>
                <select name="centro_id" id="centro" class="form-select" required>
                    <option value="">Selecciona un centro</option>
                    <?php while ($c = mysqli_fetch_assoc($centros)): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha y hora</label>
                <?php
                $ahora = new DateTime();
                $min_fecha = $ahora->format('Y-m-d\TH:i');
                ?>
                <input type="datetime-local" name="fecha" class="form-control" min="<?= $min_fecha ?>" required>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="participar" name="participar" value="1">
                <label class="form-check-label" for="participar">Deseo participar en este evento</label>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <button type="submit" class="btn-historial">
                    <i class="bi bi-pencil-square"></i> Crear evento
                </button>
                <a href="../index.php" class="btn-historial">
                    <i class="bi bi-arrow-left-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php
include('../includes/footer.php');
mysqli_close($connection);
?>