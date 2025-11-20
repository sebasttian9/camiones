<?php
session_start();
// Iniciar sesión para mensajes


require_once "./include/Clientes.php";

    // Verificar que el formulario fue enviado por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $clientesModel = new Clientes();
        
        // Recibir y sanitizar datos del formulario
        $nombre_cliente = trim($_POST['nombre_cliente']);
        $rut = trim($_POST['rut']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $password_raw = $_POST['password'];
        $telefono2 = $_POST['telefono2'];
        $direccion = trim($_POST['direccion']);
        
        // Validaciones del lado del servidor
        $errores = [];
        
        // Validar nombre
        if (empty($nombre_cliente)) {
            $errores[] = "El nombre del cliente es obligatorio";
        }
        
        // Validar RUT
        if (empty($rut) || !preg_match('/^[0-9]+-[0-9kK]{1}$/', $rut)) {
            $errores[] = "El RUT debe tener formato válido (12345678-9)";
        }
        
        // Validar email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }
        
        // Validar teléfono
        if (empty($telefono) || !preg_match('/^[0-9]{9}$/', $telefono)) {
            $errores[] = "El teléfono debe tener 9 dígitos";
        }
        
        // Validar contraseña (mínimo 6 caracteres, alfanumérica)
        if (empty($password_raw) || !preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password_raw)) {
            $errores[] = "La contraseña debe tener mínimo 6 caracteres y contener letras y números";
        }
        
        // Validar estado
        if (empty($telefono2) || !preg_match('/^[0-9]{9}$/', $telefono2)) {
            $errores[] = "El teléfono debe tener 9 dígitos";
        }
        
        // Validar dirección
        if (empty($direccion)) {
            $errores[] = "La dirección es obligatoria";
        }
        
        // Si hay errores, redirigir con mensaje
        if (!empty($errores)) {
            $_SESSION['error'] = implode("<br>", $errores);
            header("Location: registro.php");
            exit();
        }
        

       $validaCliente =  $clientesModel->validarCliente($rut, $email);

        // Verificar si el RUT o email ya existen
        // $stmt = $conn->prepare("SELECT id_cliente FROM tbl_clientes WHERE rut = :rut OR email = :email");
        // $stmt->bindParam(':rut', $rut);
        // $stmt->bindParam(':email', $email);
        // $stmt->execute();
        
        if ($validaCliente > 0) {
            $_SESSION['error'] = "El RUT o email ya están registrados en el sistema";
            header("Location: registro.php");
            exit();
        }


        $insertCliente = $clientesModel->creacionClientes($nombre_cliente,$rut, $email, $telefono, $password_raw, $telefono2, $direccion);
        // Encriptar la contraseña
        // $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);
        
        // // Preparar la consulta de inserción
        // $sql = "INSERT INTO tbl_clientes (nombre_cliente, rut, email, telefono, password, estado, direccion) 
        //         VALUES (:nombre_cliente, :rut, :email, :telefono, :password, :estado, :direccion)";
        
        // $stmt = $conn->prepare($sql);
        
        // // Vincular parámetros
        // $stmt->bindParam(':nombre_cliente', $nombre_cliente);
        // $stmt->bindParam(':rut', $rut);
        // $stmt->bindParam(':email', $email);
        // $stmt->bindParam(':telefono', $telefono);
        // $stmt->bindParam(':password', $password_hash);
        // $stmt->bindParam(':estado', $estado);
        // $stmt->bindParam(':direccion', $direccion);
        
        // Ejecutar la consulta
        if ($insertCliente) {
            $_SESSION['success'] = "Cliente registrado exitosamente";
            header("Location: registro.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al registrar el cliente";
            header("Location: registro.php");
            exit();
        }
        
    }


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
            
            <div class="form-container" id="RegistroUsuario">
                <h2 class="form-title text-center">Registro de Cliente</h2>
                
                <form id="formCliente" action="#" method="POST">
                    
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

                    <!-- Telefono Whatsapp -->
                    <div class="mb-3">
                        <label for="telefono2" class="form-label">
                            WhatsApp <span class="required">*</span>
                        </label>
                        <input type="tel" class="form-control" id="telefono2" name="telefono2" 
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

                    // console.log(response);
                    
                    const data = await response.json();
                    console.log(data)
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
</script>
</body>
</html>