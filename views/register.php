<?php
include('../includes/header.php');
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>


<div class="container mt-5 p-5 bg-light rounded shadow panel-informativo text-center">
    <h2 class="mb-4 text-center">Registro de usuario</h2>
    <form action="../controllers/registerController.php" method="POST" id="registerForm">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Registrarse</button>
    </form>
    
</div>

<?php
include('../includes/footer.php');
?>


