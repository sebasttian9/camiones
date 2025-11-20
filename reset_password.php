<?php
// require_once 'config.php';
require_once "./include/Clientes.php";

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
                            window.location.href = 'portal_automotriz.php';
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
        $clientesModel = new Clientes();
        $hashedToken = hash('sha256', $token);
        
        // Verificar token
        $usuario = $clientesModel->verificarToken($hashedToken);
        
        if ($usuario) {
            // Actualizar contraseña y limpiar token
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $clientesModel->actualizarContraseña($hashedPassword,$usuario['id_cliente']);
            // $stmt = $pdo->prepare("
            //     UPDATE clientes 
            //     SET password = ?, reset_token = NULL, reset_token_expiry = NULL
            //     WHERE id_cliente = ?
            // ");
            // $stmt->execute([$hashedPassword, $usuario['id_cliente']]);
            
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