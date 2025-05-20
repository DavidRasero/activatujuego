<?php
class Inscripcion
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function crear($usuario_id, $evento_id)
    {
        $sql = "INSERT INTO inscripcion (usuario_id, evento_id, estado) VALUES (?, ?, 'pendiente')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $usuario_id, $evento_id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }


    public function obtenerEstado($usuario_id, $evento_id)
    {
        $sql = "SELECT estado FROM inscripcion WHERE usuario_id = ? AND evento_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $usuario_id, $evento_id);
        $stmt->execute();

        $estado = null;
        $stmt->bind_result($estado);
        $stmt->fetch();
        $stmt->close();
        return $estado ?? null;
    }


    public function obtenerHistorialFinalizados($usuario_id)
    {
        $sql = "SELECT e.*, d.nombre AS deporte, cd.nombre AS centro, cd.direccion
                FROM evento e
                JOIN inscripcion i ON e.id = i.evento_id
                JOIN deporte d ON e.deporte_id = d.id
                JOIN centrodeportivo cd ON e.centro_id = cd.id
                WHERE i.usuario_id = ? AND i.estado = 'aceptada' AND e.estado = 'finalizado'
                ORDER BY e.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerEventosFinalizadosPorUsuario($usuario_id)
    {
        $sql = "SELECT e.fecha, i.estado, d.nombre AS deporte, cd.nombre AS centro, cd.direccion
            FROM inscripcion i
            JOIN evento e ON i.evento_id = e.id
            JOIN deporte d ON e.deporte_id = d.id
            JOIN centrodeportivo cd ON e.centro_id = cd.id
            WHERE i.usuario_id = ? AND e.estado = 'finalizado'
            ORDER BY e.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

}
