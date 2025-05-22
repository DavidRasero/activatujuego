<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fecha = $_POST['fecha'];
    $fecha_evento = strtotime($fecha);
    $ahora = time();

    if ($fecha_evento <= $ahora) {
        $_SESSION['error'] = "La fecha del evento debe ser posterior a la actual.";
        header("Location: ../views/crear_evento.php");
        exit;
    }


    $organizador_id = $_SESSION['usuario_id'];
    $deporte_id = intval($_POST['deporte_id']);
    $centro_id = intval($_POST['centro_id']);
    $fecha = $_POST['fecha'];
    $participar = isset($_POST['participar']) ? true : false;

    if (!$deporte_id || !$centro_id || !$fecha) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../views/crear_evento.php");
        exit;
    }

    $sql = "INSERT INTO evento (organizador_id, deporte_id, centro_id, estado, fecha, jugadores_aceptados)
            VALUES (?, ?, ?, 'activo', ?, 0)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "iiis", $organizador_id, $deporte_id, $centro_id, $fecha);

    if (mysqli_stmt_execute($stmt)) {
        $evento_id = mysqli_insert_id($connection);

        if ($participar) {
            $sql_inscripcion = "INSERT INTO inscripcion (usuario_id, evento_id, estado) VALUES (?, ?, 'aceptada')";
            $stmt2 = mysqli_prepare($connection, $sql_inscripcion);
            mysqli_stmt_bind_param($stmt2, "ii", $organizador_id, $evento_id);
            if (mysqli_stmt_execute($stmt2)) {
                mysqli_query($connection, "UPDATE evento SET jugadores_aceptados = jugadores_aceptados + 1 WHERE id = $evento_id");
            }
            mysqli_stmt_close($stmt2);
        }

        $_SESSION['success'] = "Evento creado correctamente.";
    } else {
        $_SESSION['error'] = "Error al crear el evento: " . mysqli_error($connection);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    header("Location: ../views/crear_evento.php");
    exit;
}
