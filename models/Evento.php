<?php
class Evento
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function obtenerEventosActivosExcluyendoOrganizador($usuario_id)
    {
        $sql = "SELECT 
                    e.id, e.fecha, e.estado, e.jugadores_aceptados, e.organizador_id,
                    d.nombre AS deporte, d.numero_jugadores, d.imagen AS imagen_deporte,
                    cd.nombre AS centro, cd.direccion AS direccion,
                    u.nombre AS organizador
                FROM evento e
                JOIN deporte d ON e.deporte_id = d.id
                JOIN centrodeportivo cd ON e.centro_id = cd.id
                JOIN usuario u ON e.organizador_id = u.id
                WHERE e.estado = 'activo' AND e.organizador_id != ?
                ORDER BY e.fecha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerEstadoInscripcion($usuario_id, $evento_id)
    {
        $sql = "SELECT estado FROM inscripcion WHERE usuario_id = ? AND evento_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $usuario_id, $evento_id);
        $stmt->execute();

        $estado = null;
        $stmt->bind_result($estado);
        $stmt->fetch();
        return $estado;
    }

    public function obtenerEventosDelOrganizador($organizador_id)
    {
        $sql = "SELECT e.*, d.nombre AS deporte, d.numero_jugadores, cd.nombre AS centro
            FROM evento e
            JOIN deporte d ON e.deporte_id = d.id
            JOIN centrodeportivo cd ON e.centro_id = cd.id
            WHERE e.organizador_id = ?
            ORDER BY e.fecha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $organizador_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerEventoPorId($evento_id, $organizador_id)
    {
        $sql = "SELECT * FROM evento WHERE id = ? AND organizador_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $evento_id, $organizador_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerMaxJugadoresPorDeporte($deporte_id)
    {
        $sql = "SELECT numero_jugadores FROM deporte WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $deporte_id);
        $stmt->execute();

        $max = 0;
        $stmt->bind_result($max);
        $stmt->fetch();
        $stmt->close();

        return $max;
    }



    public function obtenerInscripcionesEvento($evento_id)
    {
        $sql = "SELECT i.id, i.estado, u.nombre, u.correo
            FROM inscripcion i
            JOIN usuario u ON i.usuario_id = u.id
            WHERE i.evento_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $evento_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function aceptarInscripcion($evento_id, $inscripcion_id, $maxJugadores)
    {
        $sql = "SELECT COUNT(*) FROM inscripcion WHERE evento_id = ? AND estado = 'aceptada'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $evento_id);
        $stmt->execute();

        $aceptados = 0;
        $stmt->bind_result($aceptados);
        if ($stmt->fetch()) {
            $stmt->close();
            if ($aceptados >= $maxJugadores) {
                return ['tipo' => 'error', 'mensaje' => 'El evento ya está completo.'];
            }
        } else {
            $stmt->close();
            return ['tipo' => 'error', 'mensaje' => 'Error al contar inscripciones.'];
        }

        $sql = "UPDATE inscripcion SET estado = 'aceptada' WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $inscripcion_id);
        $stmt->execute();
        $stmt->close();

        $this->conn->query("UPDATE evento SET jugadores_aceptados = jugadores_aceptados + 1 WHERE id = $evento_id");

        $res = $this->conn->query("SELECT jugadores_aceptados FROM evento WHERE id = $evento_id");
        $row = $res->fetch_assoc();
        if ($row && $row['jugadores_aceptados'] >= $maxJugadores) {
            $this->conn->query("UPDATE evento SET estado = 'completo' WHERE id = $evento_id");
        }

        return ['tipo' => 'success', 'mensaje' => 'Inscripción aceptada.'];
    }



    public function rechazarInscripcion($inscripcion_id)
    {
        $sql = "UPDATE inscripcion SET estado = 'rechazada' WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $inscripcion_id);
        $stmt->execute();
        $stmt->close();
    }

    public function contarInscripcionesPendientes($evento_id)
    {
        $pendientes = 0;
        $sql = "SELECT COUNT(*) FROM inscripcion WHERE evento_id = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $evento_id);
        $stmt->execute();
        $stmt->bind_result($pendientes);
        $stmt->fetch();
        $stmt->close();

        return $pendientes;
    }





}
