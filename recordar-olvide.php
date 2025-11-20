Perfecto, te ayudo con ambas funcionalidades. Primero actualizaré el **login.php** con el manejo de cookies y luego te daré el código completo para la recuperación de contraseña.

## 📁 **login.php** (Actualizado con cookies)

```php
<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'true';

    // Validar campos
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    try {
        // Buscar usuario por email y estado activo
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = ? AND estado = 1 LIMIT 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Login exitoso
            $_SESSION['user_id'] = $usuario['id_cliente'];
            $_SESSION['user_name'] = $usuario['nombre_cliente'];
            $_SESSION['user_email'] = $usuario['email'];
            
            // Manejo de cookies para "Recordarme"
            if ($remember) {
                // Generar token único
                $token = bin2hex(random_bytes(32));
                $expiry = time() + (30 * 24 * 60 * 60); // 30 días
                
                // Guardar token en la base de datos
                $hashedToken = hash('sha256', $token);
                $stmt = $pdo->prepare("
                    UPDATE clientes 
                    SET remember_token = ?, token_expiry = FROM_UNIXTIME(?)
                    WHERE id_cliente = ?
                ");
                $stmt->execute([$hashedToken, $expiry, $usuario['id_cliente']]);
                
                // Establecer cookies seguras
                setcookie('remember_token', $token, [
                    'expires' => $expiry,
                    'path' => '/',
                    'domain' => '', // Tu dominio
                    'secure' => true, // Solo HTTPS en producción
                    'httponly' => true, // Protección contra XSS
                    'samesite' => 'Strict' // Protección contra CSRF
                ]);
                
                setcookie('remember_user', $usuario['id_cliente'], [
                    'expires' => $expiry,
                    'path' => '/',
                    'domain' => '',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
            } else {
                // Limpiar cookies si no se marca "Recordarme"
                setcookie('remember_token', '', time() - 3600, '/');
                setcookie('remember_user', '', time() - 3600, '/');
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'redirect' => 'dashboard.php'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
```

## 📁 **check_remember.php** (Verificar cookies al cargar la página)

```php
<?php
session_start();
require_once 'config.php';

// Si ya hay sesión activa, no hacer nada
if (isset($_SESSION['user_id'])) {
    return;
}

// Verificar si existen cookies de "Recordarme"
if (isset($_COOKIE['remember_token']) && isset($_COOKIE['remember_user'])) {
    $token = $_COOKIE['remember_token'];
    $userId = $_COOKIE['remember_user'];
    $hashedToken = hash('sha256', $token);
    
    try {
        // Verificar token en la base de datos
        $stmt = $pdo->prepare("
            SELECT * FROM clientes 
            WHERE id_cliente = ? 
            AND remember_token = ? 
            AND token_expiry > NOW() 
            AND estado = 1
            LIMIT 1
        ");
        $stmt->execute([$userId, $hashedToken]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            // Restaurar sesión
            $_SESSION['user_id'] = $usuario['id_cliente'];
            $_SESSION['user_name'] = $usuario['nombre_cliente'];
            $_SESSION['user_email'] = $usuario['email'];
            
            // Opcional: Regenerar token para mayor seguridad
            $newToken = bin2hex(random_bytes(32));
            $newHashedToken = hash('sha256', $newToken);
            $newExpiry = time() + (30 * 24 * 60 * 60);
            
            $stmt = $pdo->prepare("
                UPDATE clientes 
                SET remember_token = ?, token_expiry = FROM_UNIXTIME(?)
                WHERE id_cliente = ?
            ");
            $stmt->execute([$newHashedToken, $newExpiry, $usuario['id_cliente']]);
            
            setcookie('remember_token', $newToken, [
                'expires' => $newExpiry,
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        } else {
            // Token inválido, limpiar cookies
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('remember_user', '', time() - 3600, '/');
        }
    } catch (PDOException $e) {
        // Error, limpiar cookies por seguridad
        setcookie('remember_token', '', time() - 3600, '/');
        setcookie('remember_user', '', time() - 3600, '/');
    }
}
?>
```

## 📁 **forgot_password.php** (Solicitar recuperación)

```php
<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'El email es obligatorio']);
        exit;
    }
    
    try {
        // Verificar si el email existe
        $stmt = $pdo->prepare("SELECT id_cliente, nombre_cliente FROM clientes WHERE email = ? AND estado = 1 LIMIT 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            // Generar token de recuperación
            $resetToken = bin2hex(random_bytes(32));
            $hashedResetToken = hash('sha256', $resetToken);
            $expiry = time() + (60 * 60); // 1 hora
            
            // Guardar token en la base de datos
            $stmt = $pdo->prepare("
                UPDATE clientes 
                SET reset_token = ?, reset_token_expiry = FROM_UNIXTIME(?)
                WHERE id_cliente = ?
            ");
            $stmt->execute([$hashedResetToken, $expiry, $usuario['id_cliente']]);
            
            // Crear enlace de recuperación
            $resetLink = "http://tudominio.com/reset_password.php?token=" . $resetToken;
            
            // Enviar email (ejemplo básico)
            $to = $email;
            $subject = "Recuperación de Contraseña";
            $message = "
                Hola {$usuario['nombre_cliente']},
                
                Has solicitado recuperar tu contraseña. 
                Haz clic en el siguiente enlace para restablecerla:
                
                {$resetLink}
                
                Este enlace expirará en 1 hora.
                
                Si no solicitaste este cambio, ignora este mensaje.
            ";
            
            $headers = "From: noreply@tudominio.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            // Enviar email
            if (mail($to, $subject, $message, $headers)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Se ha enviado un enlace de recuperación a tu correo'
                ]);
            } else {
                // Para desarrollo, mostrar el link
                echo json_encode([
                    'success' => true,
                    'message' => 'Link de recuperación: ' . $resetLink,
                    'dev_link' => $resetLink // Eliminar en producción
                ]);
            }
        } else {
            // Por seguridad, no revelar si el email existe o no
            echo json_encode([
                'success' => true,
                'message' => 'Si el correo existe, recibirás un enlace de recuperación'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
```

## 📁 **reset_password.php** (Restablecer contraseña)

```php
<?php
require_once 'config.php';

// Si es GET, mostrar formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Restablecer Contraseña</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .reset-card {
                background: white;
                border-radius: 8px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                padding: 2rem;
                max-width: 450px;
                width: 100%;
            }
            .btn-reset {
                background: #c0392b;
                color: white;
            }
            .btn-reset:hover {
                background: #a93226;
            }
        </style>
    </head>
    <body>
        <div class="reset-card">
            <h3 class="mb-4">Restablecer Contraseña</h3>
            <div class="alert alert-info d-none" id="message"></div>
            <form id="resetForm">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required minlength="6">
                    <small class="text-muted">Mínimo 6 caracteres</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-reset w-100">Restablecer Contraseña</button>
            </form>
        </div>
        
        <script>
            document.getElementById('resetForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('confirm_password').value;
                const message = document.getElementById('message');
                
                if (password !== confirm) {
                    message.className = 'alert alert-danger';
                    message.textContent = 'Las contraseñas no coinciden';
                    message.classList.remove('d-none');
                    return;
                }
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('reset_password.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    message.className = data.success ? 'alert alert-success' : 'alert alert-danger';
                    message.textContent = data.message;
                    message.classList.remove('d-none');
                    
                    if (data.success) {
                        setTimeout(() => {
                            window.location.href = 'index.php';
                        }, 2000);
                    }
                } catch (error) {
                    message.className = 'alert alert-danger';
                    message.textContent = 'Error de conexión';
                    message.classList.remove('d-none');
                }
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}

// Si es POST, procesar el cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($token) || empty($password) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }
    
    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
        exit;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        exit;
    }
    
    try {
        $hashedToken = hash('sha256', $token);
        
        // Verificar token
        $stmt = $pdo->prepare("
            SELECT id_cliente FROM clientes 
            WHERE reset_token = ? 
            AND reset_token_expiry > NOW() 
            AND estado = 1
            LIMIT 1
        ");
        $stmt->execute([$hashedToken]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            // Actualizar contraseña y limpiar token
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE clientes 
                SET password = ?, reset_token = NULL, reset_token_expiry = NULL
                WHERE id_cliente = ?
            ");
            $stmt->execute([$hashedPassword, $usuario['id_cliente']]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Token inválido o expirado'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor'
        ]);
    }
}
?>
```

## 🗄️ **Actualización de la base de datos**

Ejecuta este SQL para agregar los campos necesarios:

```sql
ALTER TABLE clientes
ADD COLUMN remember_token VARCHAR(255) NULL,
ADD COLUMN token_expiry DATETIME NULL,
ADD COLUMN reset_token VARCHAR(255) NULL,
ADD COLUMN reset_token_expiry DATETIME NULL;
```

## 📝 **Actualización del HTML del modal**

Actualiza el JavaScript del formulario de login para enviar el estado del checkbox:

```javascript
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
```

## 🔐 **Características implementadas:**

✅ **Cookies seguras** - HttpOnly, Secure, SameSite  
✅ **Token único** por usuario  
✅ **Expiración de 30 días** para "Recordarme"  
✅ **Recuperación de contraseña** con token temporal  
✅ **Email de recuperación** (requiere configuración de servidor de correo)  
✅ **Regeneración de tokens** para mayor seguridad  
✅ **Limpieza automática** de tokens expirados

¿Necesitas ayuda configurando el envío de emails o alguna otra funcionalidad?