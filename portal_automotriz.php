<?php
session_start();

require_once "./include/Camiones.php";

var_dump($_SESSION);
var_dump($_POST);

$camionesModel = new Camiones();


// Configuración
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 10;

// Obtener datos
if($_POST){
$vehiculos = $camionesModel->obtenerCamionesPaginados($pagina, $porPagina, $_POST['tipo_auto'], $_POST['SelectMarca'], $_POST['agno_inicio'], $_POST['agno_fin'], $_POST['precio'], $_POST['transmision']);
}else{
$vehiculos = $camionesModel->obtenerCamionesPaginados($pagina, $porPagina);
}
$total = $camionesModel->contarCamiones();

// Calcular total de páginas
$totalPaginas = ceil($total / $porPagina);

// $vehiculos = $camionesModel->obtenerCamiones();
$marcas = $camionesModel->obtenerMarcas();
// $modelos = $camionesModel->obtenerModelos();
// print_r($vehiculos);

$uno = $camionesModel->obtenerCamionPorId(3);
// print_r($uno);
// Datos de ejemplo para los vehículos
// $vehiculos = [
//     [
//         'marca' => 'ASTRA',
//         'modelo' => 'HD964 52 8X4',
//         'precio' => 83000000,
//         'precio_iva' => 98770000,
//         'anio' => '2020',
//         'combustible' => 'Diésel',
//         'transmision' => 'Mecánico',
//         'kilometraje' => '28.475 Kms',
//         'imagen' => './assets/img/camion.webp'
//     ],
//     [
//         'marca' => 'CHEVROLET',
//         'modelo' => 'FTR 1524 4X2 MT',
//         'precio' => 36900000,
//         'precio_iva' => 43911000,
//         'anio' => '2018',
//         'combustible' => 'Diésel',
//         'transmision' => 'Mecánico',
//         'kilometraje' => '210.000 Kms',
//         'imagen' => './assets/img/camion.webp',
//         'en_movimiento' => false
//     ],
//     [
//         'marca' => 'CHEVROLET',
//         'modelo' => 'FTR 1524 4X2 MT',
//         'precio' => 36900000,
//         'precio_iva' => 43911000,
//         'anio' => '2018',
//         'combustible' => 'Diésel',
//         'transmision' => 'Mecánico',
//         'kilometraje' => '185.000 Kms',
//         'imagen' => './assets/img/camion.webp',
//         'en_movimiento' => false
//     ]
// ];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClicChile - Portal Camionero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/index.css">
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 div-logo">
                    <img src="./assets/img/logo_clichile.jpeg" class="logo_clic" alt="">
                    <!--div class="logo-section">
                        <h2 class="mb-0" style="color: var(--primary-color);">
                            <i class="fas fa-hand-pointer"></i> ClicChile
                        </h2>
                        <small>Toda la Industria en un portal</small>
                    </div-->
                </div>
                <div class="col-md-9">
                    <div class="main-banner">
                        <div class="text-center">
                            <div class="banner-title">
                                SOMOS TU PORTAL CAMIONERO
                            </div>
                            <div class="banner-subtitle">
                                CON SOLO UN CLIC ENCUENTRA LA SOLUCIÓN QUE BUSCAS
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <div class="d-flex justify-content-center flex-wrap">
                <a href="#quienes-somos">QUIENES SOMOS</a>
                <a href="#representaciones">REPRESENTACIONES</a>
                <a href="#publicaciones" class="demo-button" data-bs-toggle="modal" data-bs-target="#loginModal">INICIAR SESION</a>
                <a href="./registro.php#RegistroUsuario">REGISTRARSE</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section - Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active" data-bs-interval="5000">
                <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=1920&h=600&fit=crop');">
                    <div class="hero-content">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="hero-title">
                                        Mercedes-Benz renueva su gama de<br>
                                        camiones en Chile
                                    </h1>
                                    <p class="lead mb-4">Descubre la nueva generación de camiones con tecnología de punta</p>
                                    <button class="btn btn-conoce-mas">
                                        <i class="fas fa-arrow-right"></i> Conoce más
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" data-bs-interval="5000">
                <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=1920&h=600&fit=crop');">
                    <div class="hero-content">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="hero-title">
                                        La mejor selección de<br>
                                        vehículos comerciales
                                    </h1>
                                    <p class="lead mb-4">Encuentra el camión perfecto para tu negocio</p>
                                    <button class="btn btn-conoce-mas">
                                        <i class="fas fa-arrow-right"></i> Ver catálogo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" data-bs-interval="5000">
                <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1920&h=600&fit=crop');">
                    <div class="hero-content">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="hero-title">
                                        Financiamiento flexible<br>
                                        para tu inversión
                                    </h1>
                                    <p class="lead mb-4">Planes especiales para empresas y particulares</p>
                                    <button class="btn btn-conoce-mas">
                                        <i class="fas fa-arrow-right"></i> Solicitar información
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Search Filters -->
    <div class="container">
        <div class="search-filters">
            <form action="#" method="POST">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <select class="form-select filter-select" name="tipo_auto" id="tipo_auto">
                        <option selected>Camiones</option>
                        <option>Buses</option>
                        <option>Maquinaria</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <select class="form-select filter-select" id="SelectMarca" name="SelectMarca">
                        <option selected value="0">Marca</option>
                        <?php foreach($marcas as $marca): ?>
                            <option value="<?php echo $marca['id_marca']; ?>"><?php echo $marca['marca_nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select class="form-select filter-select" id="Selectmodelo" nombre="Selectmodelo">
                        <option selected value="0">Modelos</option>
                        <?php foreach($modelos as $modelo): ?>
                            <option value="<?php echo $modelo['id_modelo']; ?>"><?php echo $modelo['nombre_modelo']; ?></option>
                        <?php endforeach; ?>                        
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select class="form-select filter-select" id="agno_inicio" name="agno_inicio">
                        <option selected>Año inicio</option>
                    </select>
                </div>
                                <div class="col-md-2 col-6">
                    <select class="form-select filter-select" id="agno_fin" name="agno_fin">
                        <option selected>Año Fin</option>
                    </select>
                </div>
                <div class="col-md-4 col-6">
                    <select class="form-select filter-select" id="precio" name="precio">
                        <option value="20000000">Hasta $20.000.000</option>
                        <option value="30000000">Hasta $30.000.000</option>
                        <option value="40000000">Hasta $40.000.000</option>
                        <option value="50000000">hasta $50.000.000</option>
                        <option value="60000000">Hasta $60.000.000</option>
                        <option value="70000000">Hasta $70.000.000</option>
                        <option value="80000000">Hasta $80.000.000</option>
                        <option value="90000000">Hasta $90.000.000</option>
                        <option value="100000000">Hasta $100.000.000</option>
                    </select>
                </div>
                <div class="col-md-4 col-6">
                    <select class="form-select filter-select" id="transmision" name="transmision">
                        <option selected value="0">Transmisión</option>
                        <option value="1">Manual</option>
                        <option value="2">Automatico</option>
                    </select>
                </div>
                <div class="col-md-2 col-12 d-grid gap-2"> <!-- d-grid gap-2 ocupa todo el ancho -->
                            <input type="submit" class="btn btn-primary btn-lg" value="Buscar" />
                </div>
            </div>
            <!-- <div class="results-count mt-3">
                Mostrando 178 resultados.
            </div> -->
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5" id="catalogo">
        <div class="row">
            <!-- Vehicle Listings -->
            <div class="col-lg-9">
                <div class="row">
                    <?php foreach($vehiculos as $vehiculo): ?>
                    <div class="col-md-4">
                        <div class="vehicle-card">
                            <div style="position: relative;">
                                <img src="./admin/uploads/camiones/<?php echo $vehiculo['img_camion']; ?>" alt="<?php echo $vehiculo['modelo']; ?>" class="vehicle-image">
                                <?php if(isset($vehiculo['en_movimiento']) && $vehiculo['en_movimiento']): ?>
                                <span class="badge-movimiento">
                                    <i class="fas fa-sync-alt"></i> COMUNICACIÓN EN MOVIMIENTO
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="vehicle-info">
                                <div class="vehicle-brand">
                                    <?php echo $vehiculo['marca'] . ' ' . $vehiculo['modelo']; ?>
                                </div>
                                <div class="vehicle-price">
                                    $ <?php echo number_format($vehiculo['precio'], 0, ',', '.'); ?>
                                </div>
                                <div class="vehicle-price-iva">
                                    $<?php echo number_format($vehiculo['precio']*1.19, 0, ',', '.'); ?> IVA INC.<br>
                                    (<?php echo $vehiculo['ciudad']; ?>)
                                </div>
                                <div class="vehicle-specs">
                                    <span class="spec-item"><?php echo $vehiculo['agno']; ?></span>
                                    <span class="spec-item"><?php echo $vehiculo['combustible']; ?></span>
                                    <span class="spec-item"><?php echo $vehiculo['transmision']; ?></span>
                                    <span class="spec-item"><?php echo $vehiculo['kilometraje']; ?></span>
                                </div>
                                <a class="btn btn-ver-mas" href="ficha_producto.php?cam=<?php echo $vehiculo['id_camion']; ?>">VER MÁS</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginacion -->

                        <nav aria-label="Page navigation" class="d-flex justify-content-end">
                            <ul class="pagination justify-content-center">

                                <!-- Botón Anterior -->
                                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?pagina=<?= $pagina - 1 ?>#catalogo" tabindex="-1">Anterior</a>
                                </li>

                                <!-- Números -->
                                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                    <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                                        <a class="page-link" href="?pagina=<?= $i ?>#catalogo"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Botón Siguiente -->
                                <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?pagina=<?= $pagina + 1 ?>#catalogo">Siguiente</a>
                                </li>

                            </ul>
                        </nav>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="sidebar-ad">
                    <img src="./assets/img/Portada-207.jpg" alt="Revista RTT">
                </div>
                <div class="sidebar-ad">
                    <img src="./assets/img/Volvo-septiembre-2025.jpg" alt="Publicidad">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <!-- Sobre Nosotros -->
                <div class="col-lg-4 col-md-6 footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-hand-pointer"></i> ClicChile
                    </div>
                    <p style="color: #ccc; line-height: 1.8;">
                        Tu portal automotriz líder en Chile. Conectamos a compradores y vendedores de vehículos comerciales con la mejor tecnología y servicio del mercado.
                    </p>
                    <div class="footer-social">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="col-lg-4 col-md-6 footer-section">
                    <h3 class="footer-title">Contáctanos</h3>
                    
                    <div class="footer-contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <div class="footer-contact-info">
                            <strong>Teléfonos:</strong><br>
                            <a href="tel:+56222345678">+56 2 2234 5678</a><br>
                            <a href="tel:+56998765432">+56 9 9876 5432</a>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <div class="footer-contact-info">
                            <strong>Email:</strong><br>
                            <a href="mailto:contacto@clicchile.cl">contacto@clicchile.cl</a><br>
                            <a href="mailto:ventas@clicchile.cl">ventas@clicchile.cl</a>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="footer-contact-info">
                            <strong>Dirección:</strong><br>
                            Av. Providencia 1234, Oficina 567<br>
                            Santiago, Chile
                        </div>
                    </div>
                </div>

                <!-- Enlaces Rápidos -->
                <div class="col-lg-4 col-md-6 footer-section">
                    <h3 class="footer-title">Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="#quienes-somos"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Quiénes Somos</a></li>
                        <li><a href="#representaciones"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Representaciones</a></li>
                        <li><a href="#publicaciones"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Publicaciones</a></li>
                        <li><a href="#cobertura"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Cobertura</a></li>
                        <li><a href="#catalogo"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Catálogo de Vehículos</a></li>
                        <li><a href="#financiamiento"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Financiamiento</a></li>
                        <li><a href="#terminos"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Términos y Condiciones</a></li>
                        <li><a href="#privacidad"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Política de Privacidad</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p>&copy; <?php echo date('Y'); ?> <strong>ClicChile</strong> - Todos los derechos reservados.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p>
                            Desarrollado con <i class="fas fa-heart" style="color: var(--primary-color);"></i> por 
                            <a href="#">ClicChile Team</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/56912345678" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Social Media Buttons -->
    <div style="position: fixed; right: 20px; top: 50%; transform: translateY(-50%); z-index: 100;">
        <div class="d-flex flex-column gap-2">
            <a href="#" class="btn btn-primary rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="btn" style="background: #00acee; color: white; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="btn" style="background: #0077b5; color: white; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                <i class="fab fa-linkedin-in"></i>
            </a>
        </div>
    </div>

        <!-- Modal de Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">
                        <i class="fas fa-user-circle me-2"></i>Cuenta de Ingreso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Alerta de ejemplo -->
                    <div class="alert alert-info d-none" id="alertMessage" role="alert">
                        <i class="fas fa-info-circle me-2"></i><span id="alertText"></span>
                    </div>

                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Correo Electrónico
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="correo@ejemplo.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Contraseña
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Ingrese su contraseña" required>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Recuérdame
                                </label>
                            </div>
                            <a href="#" class="link-text">¿Olvidó su contraseña?</a>
                        </div>

                        <button type="submit" class="btn btn-login w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </button>

                        <hr>

                        <div class="divider">
                            ¿No tiene una cuenta?
                        </div>

                        <a type="button" href="registro.php" class="btn btn-register w-100">
                            <i class="fas fa-user-plus me-2"></i>Registrarse
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        const selectModelo = document.getElementById('Selectmodelo');
        const selectMarca = document.getElementById('SelectMarca');
        // alert('¡Hola desde JavaScript!');
        // console.log('JS cargado');
        // Asegurar que el carrusel se inicialice correctamente
        window.addEventListener('load', function() {
            console.log('JS cargado');
            const carouselElement = document.querySelector('#heroCarousel');
            if (carouselElement) {
                const carousel = new bootstrap.Carousel(carouselElement, {
                    interval: 5000,
                    ride: 'carousel',
                    pause: 'hover',
                    wrap: true,
                    touch: true
                });
                
                // Forzar el inicio del carrusel
                carousel.cycle();
                
                // console.log('Carrusel inicializado correctamente');
            }
        });

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const rememberMe = document.getElementById('rememberMe').checked;
            formData.append('remember', rememberMe);
            
            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    showAlert(data.message, 'danger');
                }
            } catch (error) {
                showAlert('Error de conexión', 'danger');
            }
        });

        // Agregar evento para "Olvidé mi contraseña"
        document.querySelector('.link-text').addEventListener('click', async function(e) {
            e.preventDefault();
            
            const email = prompt('Ingrese su correo electrónico:');
            
            if (email) {
                const formData = new FormData();
                formData.append('email', email);
                
                try {
                    const response = await fetch('forgot_password.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    showAlert(data.message, data.success ? 'success' : 'danger');
                    
                    // Para desarrollo, mostrar link si existe
                    if (data.dev_link) {
                        console.log('Link de recuperación:', data.dev_link);
                    }
                } catch (error) {
                    showAlert('Error al enviar el correo', 'danger');
                }
            }
        });

        function showAlert(message, type) {
            const alert = document.getElementById('alertMessage');
            const alertText = document.getElementById('alertText');
            
            alert.className = `alert alert-${type}`;
            alertText.textContent = message;
            alert.classList.remove('d-none');
            
            setTimeout(() => {
                alert.classList.add('d-none');
            }, 3000);
        }

              // Función para cargar modelos según la marca seleccionada
        function cargarModelos(marcaId) {
            // loadingModelo.classList.add('show');
            // selectModelo.disabled = true;
            // resultDiv.classList.remove('show');
            
            // Crear objeto XMLHttpRequest
            const xhr = new XMLHttpRequest();
            
            // Configurar la petición con el parámetro marca_id
            xhr.open('GET', 'get_modelos.php?marca_id=' + marcaId, true);
            
            // Manejar la respuesta
            xhr.onload = function() {
                // loadingModelo.classList.remove('show');
                
                if (xhr.status === 200) {
                    // Insertar el HTML recibido directamente en el select
                    selectModelo.innerHTML = xhr.responseText;
                    selectModelo.disabled = false;
                } else {
                    console.error('Error al cargar modelos:', xhr.status);
                    selectModelo.innerHTML = '<option value="">Error al cargar modelos</option>';
                    selectModelo.disabled = false;
                }
            };
            
            // Manejar errores
            xhr.onerror = function() {
                // loadingModelo.classList.remove('show');
                console.error('Error de conexión');
                selectModelo.innerHTML = '<option value="">Error de conexión</option>';
                // selectModelo.disabled = false;
            };
            
            // Enviar la petición
            xhr.send();
        }

                // Event listener para cambio de marca
        selectMarca.addEventListener('change', function() {
            const marcaId = this.value;
            
            if (marcaId) {
                cargarModelos(marcaId);
            } else {
                // selectModelo.disabled = true;
                selectModelo.innerHTML = '<option value="">-- Primero seleccione una marca --</option>';
                // resultDiv.classList.remove('show');
            }
        });
    </script>


</body>
</html>