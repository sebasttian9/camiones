<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'nombre_base_datos'; // Cambia esto por el nombre de tu base de datos
$username = 'root'; // Cambia esto por tu usuario
$password = ''; // Cambia esto por tu contraseña

// Iniciar sesión para mensajes
session_start();

try {
    // Crear conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configurar PDO para que lance excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar que el formulario fue enviado por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Recibir y sanitizar datos del formulario
        $nombre_cliente = trim($_POST['nombre_cliente']);
        $rut = trim($_POST['rut']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $password_raw = $_POST['password'];
        $estado = $_POST['estado'];
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
        if (empty($estado) || !in_array($estado, ['activo', 'inactivo'])) {
            $errores[] = "Debe seleccionar un estado válido";
        }
        
        // Validar dirección
        if (empty($direccion)) {
            $errores[] = "La dirección es obligatoria";
        }
        
        // Si hay errores, redirigir con mensaje
        if (!empty($errores)) {
            $_SESSION['error'] = implode("<br>", $errores);
            header("Location: formulario.php");
            exit();
        }
        
        // Verificar si el RUT o email ya existen
        $stmt = $conn->prepare("SELECT id_cliente FROM tbl_clientes WHERE rut = :rut OR email = :email");
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "El RUT o email ya están registrados en el sistema";
            header("Location: formulario.php");
            exit();
        }
        
        // Encriptar la contraseña
        $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);
        
        // Preparar la consulta de inserción
        $sql = "INSERT INTO tbl_clientes (nombre_cliente, rut, email, telefono, password, estado, direccion) 
                VALUES (:nombre_cliente, :rut, :email, :telefono, :password, :estado, :direccion)";
        
        $stmt = $conn->prepare($sql);
        
        // Vincular parámetros
        $stmt->bindParam(':nombre_cliente', $nombre_cliente);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':direccion', $direccion);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            $_SESSION['success'] = "Cliente registrado exitosamente";
            header("Location: formulario.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al registrar el cliente";
            header("Location: formulario.php");
            exit();
        }
        
    } else {
        // Si no es POST, redirigir al formulario
        header("Location: formulario.php");
        exit();
    }
    
} catch(PDOException $e) {
    // Capturar errores de base de datos
    $_SESSION['error'] = "Error de conexión: " . $e->getMessage();
    header("Location: formulario.php");
    exit();
}

// Cerrar conexión
$conn = null;
?>