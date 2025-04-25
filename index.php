<?php include('includes/header.php'); ?>

<div class="carrusel-ajustado mt-4 mb-4">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="public/img/foto4.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="public/img/foto2.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="public/img/foto3.jpg" class="d-block w-100" alt="...">
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

<div class="container my-5 p-5 bg-light rounded shadow panel-informativo text-center">

    <h1 class="mb-4">Bienvenido a ActivaTuJuego</h1>
    <p class="mb-4">Organiza y participa en eventos deportivos fácilmente.</p>

    <?php if (!isset($_SESSION['nombre'])): ?>
        <a href="views/login.php" class="btn btn-success me-2">Iniciar sesión</a>
        <a href="views/register.php" class="btn btn-outline-success">Registrarse</a>
    <?php endif; ?>

    <div class="row mt-5">
        <div class="col-md-6 d-flex justify-content-center align-items-center mb-4 mb-md-0">
            <img src="public/img/logo.png" alt="Logo ActivaTuJuego" class="img-fluid logo-bonito" style="max-width: 250px;">
        </div>
        <div class="col-md-6 text-start">
            <h5 class="fw-bold">¿Quiénes somos?</h5>
            <p>
                Somos una plataforma que conecta personas que quieren organizar o unirse a eventos deportivos en su ciudad. 
                Fomentamos el deporte, el compañerismo y un estilo de vida saludable. ¡Únete y activa tu juego!
            </p>
        </div>
    </div>
</div>



<?php include('includes/footer.php'); ?>

