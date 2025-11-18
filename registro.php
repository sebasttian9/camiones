<?php

require_once "./include/Camiones.php";

$camionesModel = new Camiones();


// Configuración
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 6;

// Obtener datos
$vehiculos = $camionesModel->obtenerCamionesPaginados($pagina, $porPagina);
$total = $camionesModel->contarCamiones();

// Calcular total de páginas
$totalPaginas = ceil($total / $porPagina);

// $vehiculos = $camionesModel->obtenerCamiones();
$marcas = $camionesModel->obtenerMarcas();
$modelos = $camionesModel->obtenerModelos();
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
                <a href="#publicaciones">INICIAR SESION</a>
                <a href="#cobertura">REGISTRARSE</a>
            </div>
        </div>
    </nav>


<!-- Main Content -->
<div class="container my-5" id="catalogo">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            
            <?php
            // Mostrar mensaje de éxito
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                echo $_SESSION['success'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
                unset($_SESSION['success']);
            }
            
            // Mostrar mensaje de error
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                echo $_SESSION['error'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
                unset($_SESSION['error']);
            }
            ?>
            
            <div class="form-container">
                <h2 class="form-title text-center">Registro de Cliente</h2>
                
                <form id="formCliente" action="procesar_cliente.php" method="POST">
                    
                    <!-- Nombre Cliente -->
                    <div class="mb-3">
                        <label for="nombre_cliente" class="form-label">
                            Nombre del Cliente <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required>
                    </div>

                    <!-- RUT -->
                    <div class="mb-3">
                        <label for="rut" class="form-label">
                            RUT <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" id="rut" name="rut" placeholder="12345678-9" required>
                        <div class="form-text">Formato: 12345678-9</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="telefono" class="form-label">
                            Teléfono <span class="required">*</span>
                        </label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" 
                               pattern="[0-9]{9}" placeholder="912345678" required>
                        <div class="form-text">Ingrese 9 dígitos sin espacios ni guiones</div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Contraseña <span class="required">*</span>
                        </label>
                        <input type="password" class="form-control" id="password" name="password" 
                               pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$" required>
                        <div class="form-text">Mínimo 6 caracteres, debe contener letras y números</div>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">
                            Estado <span class="required">*</span>
                        </label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="">Seleccione un estado</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="direccion" class="form-label">
                            Dirección <span class="required">*</span>
                        </label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3" required></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="reset" class="btn btn-secondary me-md-2">
                            Limpiar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Registrar Cliente
                        </button>
                    </div>

                </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // alert('¡Hola desde JavaScript!');
        console.log('JS cargado');
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
                
                console.log('Carrusel inicializado correctamente');
            }
        });
// Validación adicional de contraseña con JavaScript
    document.getElementById('formCliente').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
        
        if (!regex.test(password)) {
            e.preventDefault();
            alert('La contraseña debe tener mínimo 6 caracteres y contener letras y números');
            return false;
        }
    });

    // Validación de RUT chileno (opcional)
    document.getElementById('rut').addEventListener('blur', function() {
        const rut = this.value.replace(/\./g, '');
        if (rut && !validarRUT(rut)) {
            this.setCustomValidity('RUT inválido');
        } else {
            this.setCustomValidity('');
        }
    });

    function validarRUT(rut) {
        if (!/^[0-9]+-[0-9kK]{1}$/.test(rut)) return false;
        
        const tmp = rut.split('-');
        let digv = tmp[1]; 
        const rutNum = tmp[0];
        
        if (digv == 'K') digv = 'k';
        
        return (dv(rutNum) == digv);
    }

    function dv(T) {
        let M = 0, S = 1;
        for (; T; T = Math.floor(T / 10))
            S = (S + T % 10 * (9 - M++ % 6)) % 11;
        return S ? S - 1 : 'k';
    }
</script>

</body>
</html>