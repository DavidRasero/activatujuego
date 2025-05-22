<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="encabezado-eventos text-center animado" id="anim-panel">
        <h1 class="titulo-eventos mb-4">
            <i class="bi bi-pencil-square me-2"></i> Registro de usuario
        </h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'];
            unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="../controllers/registerController.php" method="POST" id="registerForm" class="text-start mx-auto"
            style="max-width: 450px;">
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

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-pencil-square me-1"></i> Registrarse
            </button>
        </form>

        <div class="text-center mt-4">
            ¿Ya tienes cuenta?
            <a href="login.php" class="btn-historial ms-2">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
            </a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>