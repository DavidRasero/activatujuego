<?php include('includes/header.php'); ?>

<div class="carrusel-ajustado mt-4 mb-4 animado" id="anim-carrusel">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                class="active"></button>
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

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<div class="container mt-5">
    <div class="encabezado-eventos text-center animado" id="anim-panel">
        <h1 class="titulo-eventos mb-4">Bienvenido a ActivaTuJuego</h1>
        <p class="lead" id="subtitulo">Participa en eventos deportivos fácilmente.</p>

        <?php if (!isset($_SESSION['nombre'])): ?>
            <a href="views/login.php" class="btn-historial">
                <i class="bi bi-box-arrow-in-right"></i>
                Iniciar sesión
            </a>
            <a href="views/register.php" class="btn-historial">
                <i class="bi bi-pencil-square"></i>
                Registrarse
            </a>
        <?php endif; ?>

        <?php if (isset($_SESSION['nombre'])): ?>
            <a href="views/lista_deportes.php" class="btn-historial">
                <i class="bi bi-trophy-fill"></i>
                Lista de deportes
            </a>
            <a href="views/eventos.php" class="btn-historial">
                <i class="bi bi-calendar-event"></i>
                Eventos disponibles
            </a>

            <?php if (isset($_SESSION['usuario_id']) && $_SESSION['tipo'] === 'organizador'): ?>
                <a href="views/crear_evento.php" class="btn-historial">
                    <i class="bi bi-pencil-square"></i>
                    Crear evento
                </a>
                <a href="views/mis_eventos.php" class="btn-historial">
                    <i class="bi bi-clipboard-plus"></i>
                    Mis eventos
                </a>
            <?php endif; ?>

            <?php if ($_SESSION['tipo'] === 'admin'): ?>
                <a href="views/usuarios.php" class="btn-historial">
                    <i class="bi bi-people-fill"></i>
                    Gestionar usuarios
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['usuario_id']) && in_array($_SESSION['tipo'], ['jugador', 'organizador'])): ?>
                <a href="views/historial.php" class="btn-historial">
                    <i class="bi bi-clock-history"></i>
                    Ver historial
                </a>
            <?php endif; ?>

            <?php if ($_SESSION['tipo'] === 'admin'): ?>
                <a href="views/gestionar_deportes.php" class="btn-historial">
                    <i class="bi bi-dribbble"></i>
                    Gestionar deportes
                </a>
                <a href="views/gestionar_centros.php" class="btn-historial">
                    <i class="bi bi-building"></i>
                    Gestionar centros deportivos
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row mt-5">
            <div class="col-md-6 d-flex justify-content-center align-items-center mb-4 mb-md-0">
                <img src="public/img/logo.png" alt="Logo ActivaTuJuego" class="img-fluid logo-bonito"
                    style="max-width: 250px;">
            </div>
            <div class="col-md-6 text-start">
                <h5 class="fw-bold">¿Quiénes somos?</h5>
                <p>
                    Somos una plataforma que conecta personas que quieren organizar o unirse a eventos deportivos en su
                    ciudad.
                    Fomentamos el deporte, el compañerismo y un estilo de vida saludable. ¡Únete y activa tu juego!
                </p>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>