<?php
session_start();
// require_once 'config.php';
require_once "./include/Clientes.php";

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
        $clientesModel = new Clientes();


        // $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = ? AND estado = 1 LIMIT 1");
        // $stmt->execute([$email]);
        $usuario = $clientesModel->login($email);

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
                $clientesModel->guardarTokenCookies($hashedToken, $expiry, $usuario['id_cliente']);

                // $stmt = $pdo->prepare("
                //     UPDATE clientes 
                //     SET remember_token = ?, token_expiry = FROM_UNIXTIME(?)
                //     WHERE id_cliente = ?
                // ");
                // $stmt->execute([$hashedToken, $expiry, $usuario['id_cliente']]);
                
                // Establecer cookies seguras
                setcookie('remember_token', $token, [
                    'expires' => $expiry,
                    'path' => '/',
                    'domain' => 'localhost', // Tu dominio
                    'secure' => false, // Solo HTTPS en producción
                    'httponly' => true, // Protección contra XSS
                    'samesite' => 'Strict' // Protección contra CSRF
                ]);
                
                setcookie('remember_user', $usuario['id_cliente'], [
                    'expires' => $expiry,
                    'path' => '/',
                    'domain' => 'localhost',
                    'secure' => false,
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
                'redirect' => './admin/index_crud.php'
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