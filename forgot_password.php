<?php
require_once "./include/Clientes.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'El email es obligatorio']);
        exit;
    }
    
    try {
        $clientesModel = new Clientes();
        // Verificar si el email existe
        $usuario = $clientesModel->login($email);
        // $stmt = $pdo->prepare("SELECT id_cliente, nombre_cliente FROM tbl_clientes WHERE email = ? AND estado = 1 LIMIT 1");
        // $stmt->execute([$email]);
        // $usuario = $stmt->fetch();
        
        if ($usuario) {
            // Generar token de recuperación
            $resetToken = bin2hex(random_bytes(32));
            $hashedResetToken = hash('sha256', $resetToken);
            $expiry = time() + (60 * 60); // 1 hora
            
            // Guardar token en la base de datos
            $clientesModel->guardarToken($hashedResetToken, $expiry, $usuario['id_cliente']);
            // $stmt = $pdo->prepare("
            //     UPDATE clientes 
            //     SET reset_token = ?, reset_token_expiry = FROM_UNIXTIME(?)
            //     WHERE id_cliente = ?
            // ");
            // $stmt->execute([$hashedResetToken, $expiry, $usuario['id_cliente']]);
            
            // Crear enlace de recuperación
            
            $resetLink = "http://localhost/camiones/reset_password.php?token=" . $resetToken;
            
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
            
            // Enviar email  CONFIGURAR UNA VEZ DENTRO DEL SERVIDOR
            // if (mail($to, $subject, $message, $headers)) {
            //     echo json_encode([
            //         'success' => true,
            //         'message' => 'Se ha enviado un enlace de recuperación a tu correo'
            //     ]);
            // } else {
                // Para desarrollo, mostrar el link
                echo json_encode([
                    'success' => true,
                    'message' => 'Link de recuperación: ' . $resetLink,
                    'dev_link' => $resetLink // Eliminar en producción
                ]);
            // }
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