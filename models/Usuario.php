<?php
class Usuario {
    public static function buscarPorEmail($email) {
        include '../config/database.php';

        $stmt = $conn->prepare("SELECT * FROM Usuario WHERE correo_electronico = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
}
