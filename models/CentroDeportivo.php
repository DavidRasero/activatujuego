<?php
class CentroDeportivo
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function crear($nombre, $direccion)
    {
        $stmt = $this->conn->prepare("INSERT INTO centrodeportivo (nombre, direccion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $direccion);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function actualizar($id, $nombre, $direccion)
    {
        $stmt = $this->conn->prepare("UPDATE centrodeportivo SET nombre = ?, direccion = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nombre, $direccion, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function obtenerTodos()
    {
        $resultado = $this->conn->query("SELECT * FROM centrodeportivo ORDER BY nombre ASC");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM centrodeportivo WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }

    public function tieneEventos($centro_id)
    {
        $total = 0;
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM evento WHERE centro_id = ?");
        $stmt->bind_param("i", $centro_id);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        return $total > 0;
    }

    public function eliminar($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM centrodeportivo WHERE id = ?");
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}
