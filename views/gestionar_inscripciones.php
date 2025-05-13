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
$organizador_id = $_SESSION['usuario_id'];

$verificar = "SELECT id, deporte_id FROM evento WHERE id = ? AND organizador_id = ?";
$stmt_ver = mysqli_prepare($connection, $verificar);
mysqli_stmt_bind_param($stmt_ver, "ii", $evento_id, $organizador_id);
mysqli_stmt_execute($stmt_ver);
$resultado_ver = mysqli_stmt_get_result($stmt_ver);
$evento = mysqli_fetch_assoc($resultado_ver);
mysqli_stmt_close($stmt_ver);

if (!$evento) {
    $_SESSION['error'] = "No tienes permiso para gestionar este evento.";
    header("Location: mis_eventos.php");
    exit;
}

$maxJugadores = 0;
$sql_deporte = "SELECT numero_jugadores FROM deporte WHERE id = ?";
$stmt_dep = mysqli_prepare($connection, $sql_deporte);
mysqli_stmt_bind_param($stmt_dep, "i", $evento['deporte_id']);
mysqli_stmt_execute($stmt_dep);
mysqli_stmt_bind_result($stmt_dep, $maxJugadores);
mysqli_stmt_fetch($stmt_dep);
mysqli_stmt_close($stmt_dep);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inscripcion_id = intval($_POST['inscripcion_id']);
    $accion = $_POST['accion'];

    if ($accion === 'aceptar') {
        $sql_aceptados = "SELECT COUNT(*) FROM inscripcion WHERE evento_id = ? AND estado = 'aceptada'";
        $stmt = mysqli_prepare($connection, $sql_aceptados);
        mysqli_stmt_bind_param($stmt, "i", $evento_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $aceptados);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($aceptados >= $maxJugadores) {
            $_SESSION['error'] = "El evento ya está completo.";
        } else {
            $sql = "UPDATE inscripcion SET estado = 'aceptada' WHERE id = ?";
            $stmt_aceptar = mysqli_prepare($connection, $sql);

            if ($stmt_aceptar) {
                mysqli_stmt_bind_param($stmt_aceptar, "i", $inscripcion_id);
                mysqli_stmt_execute($stmt_aceptar);
                mysqli_stmt_close($stmt_aceptar);

                mysqli_query($connection, "UPDATE evento SET jugadores_aceptados = jugadores_aceptados + 1 WHERE id = $evento_id");

                $res = mysqli_query($connection, "SELECT jugadores_aceptados FROM evento WHERE id = $evento_id");
                $row = mysqli_fetch_assoc($res);

                if ($row['jugadores_aceptados'] >= $maxJugadores) {
                    mysqli_query($connection, "UPDATE evento SET estado = 'completo' WHERE id = $evento_id");
                }

                $_SESSION['success'] = "Inscripción aceptada.";
            } else {
                $_SESSION['error'] = "Error al preparar la aceptación.";
            }


            mysqli_query($connection, "UPDATE evento SET jugadores_aceptados = jugadores_aceptados + 1 WHERE id = $evento_id");

            $res = mysqli_query($connection, "SELECT jugadores_aceptados FROM evento WHERE id = $evento_id");
            $row = mysqli_fetch_assoc($res);
            if ($row['jugadores_aceptados'] >= $maxJugadores) {
                mysqli_query($connection, "UPDATE evento SET estado = 'completo' WHERE id = $evento_id");
            }

            $_SESSION['success'] = "Inscripción aceptada.";
        }

    } elseif ($accion === 'rechazar') {
        $sql = "UPDATE inscripcion SET estado = 'rechazada' WHERE id = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $inscripcion_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['success'] = "Inscripción rechazada.";
    }

    header("Location: gestionar_inscripciones.php?evento_id=$evento_id");
    exit;
}

$sql = "SELECT i.id, i.estado, u.nombre, u.correo
        FROM inscripcion i
        JOIN usuario u ON i.usuario_id = u.id
        WHERE i.evento_id = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $evento_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-5">
    <h2 class="mb-4">Inscripciones al evento #<?= $evento_id ?></h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'];
        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($insc = mysqli_fetch_assoc($resultado)): ?>
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
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="mis_eventos.php" class="btn btn-secondary mt-3">Volver</a>
</div>

<?php include('../includes/footer.php'); ?>