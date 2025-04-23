<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("config.php");
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ActivaTuJuego</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <script src="https://kit.fontawesome.com/a2e8a5b7d6.js" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>index.php">ActivaTuJuego</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['nombre'])): ?>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>controllers/logout.php">Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>views/login.php">Iniciar Sesión</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>views/register.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>



