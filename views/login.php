<?php include('../includes/header.php'); ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="container mt-5 p-5 bg-light rounded shadow panel-informativo text-center">
    <h2 class="mb-4 text-center">Iniciar sesión</h2>
    <form action="../controllers/loginController.php" method="POST">
        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
    <div class="text-center mt-3">
        ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
