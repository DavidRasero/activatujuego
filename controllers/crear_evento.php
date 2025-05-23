<?php
session_start();
require_once('../config/database.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $organizador_id = $_SESSION['usuario_id'];
    $deporte_id = intval($_POST['deporte_id']);
    $centro_id = intval($_POST['centro_id']);
    $fecha = trim($_POST['fecha']);
    $participar = isset($_POST['participar']);

    if (empty($deporte_id) || empty($centro_id) || empty($fecha)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../views/crear_evento.php");
        exit;
    }

    if (strtotime($fecha) <= time()) {
        $_SESSION['error'] = "La fecha del evento debe ser posterior a la actual.";
        header("Location: ../views/crear_evento.php");
        exit;
    }

    $sql = "INSERT INTO evento (organizador_id, deporte_id, centro_id, estado, fecha, jugadores_aceptados)
            VALUES (?, ?, ?, 'activo', ?, 0)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iiis", $organizador_id, $deporte_id, $centro_id, $fecha);

    if ($stmt->execute()) {
        $evento_id = $connection->insert_id;

        if ($participar) {
            $sql_inscripcion = "INSERT INTO inscripcion (usuario_id, evento_id, estado) VALUES (?, ?, 'aceptada')";
            $stmt2 = $connection->prepare($sql_inscripcion);
            $stmt2->bind_param("ii", $organizador_id, $evento_id);
            if ($stmt2->execute()) {
                $connection->query("UPDATE evento SET jugadores_aceptados = jugadores_aceptados + 1 WHERE id = $evento_id");
            }
            $stmt2->close();
        }

        $_SESSION['success'] = "Evento creado correctamente.";
    } else {
        $_SESSION['error'] = "Error al crear el evento.";
    }

    $stmt->close();
    $connection->close();
    header("Location: ../views/crear_evento.php");
    exit;
}
