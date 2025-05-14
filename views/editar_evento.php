<?php
session_start();
require_once('../includes/db.php');
include('../includes/header.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['evento_id'])) {
    $_SESSION['error'] = "Evento no válido.";
    header("Location: mis_eventos.php");
    exit;
}

$evento_id = intval($_GET['evento_id']);
$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT * FROM evento WHERE id = ? AND organizador_id = ? AND estado = 'activo'";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "ii", $evento_id, $usuario_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$evento = mysqli_fetch_assoc($resultado);

if (!$evento) {
    $_SESSION['error'] = "No puedes editar este evento.";
    header("Location: mis_eventos.php");
    exit;
}

$centros = mysqli_query($connection, "SELECT * FROM centrodeportivo");
$deportes = mysqli_query($connection, "SELECT * FROM deporte");
?>

<div class="container mt-5">
    <h2 class="mb-4">Editar evento</h2>

    <form method="POST" action="../controllers/actualizar_evento.php">
        <input type="hidden" name="evento_id" value="<?= $evento['id'] ?>">

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha y hora</label>
            <input type="datetime-local" class="form-control" name="fecha" id="fecha"
                value="<?= date('Y-m-d\TH:i', strtotime($evento['fecha'])) ?>"
                min="<?= (new DateTime())->format('Y-m-d\TH:i') ?>" required>
        </div>

        <div class="mb-3">
            <label for="centro_id" class="form-label">Centro deportivo</label>
            <select name="centro_id" class="form-select" required>
                <?php while ($centro = mysqli_fetch_assoc($centros)): ?>
                    <option value="<?= $centro['id'] ?>" <?= $centro['id'] == $evento['centro_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($centro['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Deporte</label>
            <input type="text" class="form-control" value="<?php
            $deporte_nombre = mysqli_fetch_assoc(mysqli_query($connection, "SELECT nombre FROM deporte WHERE id = " . $evento['deporte_id']));
            echo htmlspecialchars($deporte_nombre['nombre']);
            ?>" disabled>
            <input type="hidden" name="deporte_id" value="<?= $evento['deporte_id'] ?>">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="mis_eventos.php" class="btn btn-secondary">Cancelar</a>
        </div>

    </form>
</div>

<?php
include('../includes/footer.php');
?>