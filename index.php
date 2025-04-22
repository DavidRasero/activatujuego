<?php include('includes/header.php'); ?>

<div class="container mt-5 text-center">
    <h1 class="mb-4">Bienvenido a ActivaTuJuego</h1>
    <p>Organiza y participa en eventos deportivos fácilmente.</p>

    <?php if (!isset($_SESSION['nombre'])): ?>
        <a href="views/login.php" class="btn btn-success me-2">Iniciar sesión</a>
        <a href="views/register.php" class="btn btn-outline-success">Registrarse</a>
    <?php endif; ?>
</div>

<!-- Imagen de fondo con carrusel -->
<div class="container-fluid p-0">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="public/img/foto1.jpg" class="d-block w-100" alt="Deporte 1">
            </div>
            <div class="carousel-item">
                <img src="public/img/foto2.jpg" class="d-block w-100" alt="Deporte 2">
            </div>
            <div class="carousel-item">
                <img src="public/img/foto3.jpg" class="d-block w-100" alt="Deporte 3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<!-- Panel de logo + información -->
<div class="container my-5 p-5 bg-light rounded shadow d-flex align-items-center" style="background-image: url('public/img/fondo_cesped.jpg'); background-size: cover;">
    <div class="row w-100 panel-informativo">
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <img src="public/img/logo.png" alt="Logo ActivaTuJuego" class="img-fluid logo-bonito" style="max-width: 250px;">
        </div>
        <div class="col-md-6 text-start">
            <h5 class="fw-bold">¿Quiénes somos?</h5>
            <h6 class="text-muted">Subheading</h6>
            <p>
                Somos una plataforma que conecta personas que quieren organizar o unirse a eventos deportivos en su ciudad. 
                Fomentamos el deporte, el compañerismo y un estilo de vida saludable. ¡Únete y activa tu juego!
            </p>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>

