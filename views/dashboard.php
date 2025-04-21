<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h2>Bienvenido, <?= $_SESSION['nombre']; ?> 👋</h2>
    <p>Has iniciado sesión correctamente.</p>
    <a href="../controllers/logout.php" class="btn btn-danger">Cerrar sesión</a>
</div>

<?php include('../includes/footer.php'); ?>
