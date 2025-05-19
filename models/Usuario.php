<?php
class Usuario
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function buscarPorCorreo($correo)
    {
        $stmt = mysqli_prepare($this->connection, "SELECT * FROM usuario WHERE correo = ?");
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT id, nombre, correo FROM usuario WHERE id = ?";
        $stmt = mysqli_prepare($this->connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }

    public function obtenerTodos()
    {
        $sql = "SELECT id, nombre, correo, tipo FROM usuario ORDER BY id ASC";
        $resultado = mysqli_query($this->connection, $sql);
        $usuarios = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $usuarios[] = $fila;
        }

        return $usuarios;
    }

    public function registrar($nombre, $correo, $contraseña_hash)
    {
        $stmt = mysqli_prepare($this->connection, "INSERT INTO usuario (nombre, correo, contraseña, tipo) VALUES (?, ?, ?, 'jugador')");
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $correo, $contraseña_hash);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }



    public function actualizarUsuario($id, $nombre, $correo)
    {
        $stmt = mysqli_prepare($this->connection, "UPDATE usuario SET nombre = ?, correo = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $nombre, $correo, $id);
        return mysqli_stmt_execute($stmt);
    }

    public function eliminarPorId($id)
    {
        $stmt = mysqli_prepare($this->connection, "DELETE FROM usuario WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }

    public function actualizarTipo($id, $nuevoTipo)
    {
        $stmt = $this->connection->prepare("UPDATE usuario SET tipo = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevoTipo, $id);
        return $stmt->execute();
    }

    public function ascenderJugador($id)
    {
        $stmt = mysqli_prepare($this->connection, "UPDATE usuario SET tipo = 'organizador' WHERE id = ? AND tipo = 'jugador'");
        mysqli_stmt_bind_param($stmt, "i", $id);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }
}
