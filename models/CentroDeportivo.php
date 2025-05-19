<?php
class CentroDeportivo {
    public static function obtenerTodos() {
        include '../config/database.php';
        return $conn->query("SELECT * FROM centrodeportivo ORDER BY nombre ASC");
    }

    public static function obtenerPorId($id) {
        include '../config/database.php';
        $stmt = $conn->prepare("SELECT * FROM centrodeportivo WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
