<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="encabezado-eventos text-center animado" id="anim-panel">
        <h1 class="titulo-eventos mb-4">
            <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar sesión
        </h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
            unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="../controllers/loginController.php" method="POST" class="text-start mx-auto"
            style="max-width: 400px;">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
            </div>
            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
            </button>
        </form>

        <div class="text-center mt-4">
            ¿No tienes cuenta?
            <a href="register.php" class="btn-historial ms-2">
                <i class="bi bi-pencil-square"></i> Registrarse
            </a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>