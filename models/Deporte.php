<?php
class Deporte
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM deporte ORDER BY nombre ASC";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM deporte WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $deporte = $res->fetch_assoc();
        $stmt->close();
        return $deporte;
    }

    public function crear($nombre, $numero_jugadores, $imagen)
    {
        $sql = "INSERT INTO deporte (nombre, numero_jugadores, imagen) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $nombre, $numero_jugadores, $imagen);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function actualizar($id, $nombre, $descripcion, $numero_jugadores, $imagen)
    {
        $sql = "UPDATE deporte SET nombre = ?, descripcion = ?, numero_jugadores = ?, imagen = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssisi", $nombre, $descripcion, $numero_jugadores, $imagen, $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM deporte WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}
