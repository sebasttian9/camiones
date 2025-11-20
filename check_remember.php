<?php
session_start();
// require_once 'config.php';

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