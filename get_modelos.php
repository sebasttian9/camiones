<?php
header('Content-Type: text/html; charset=utf-8');
require_once "./include/Camiones.php";

// Verificar que se recibió el parámetro marca_id
if (!isset($_GET['marca_id']) || empty($_GET['marca_id'])) {
    echo '<option value="0">-- modelo --</option>';
    exit;
}

$marca_id = $_GET['marca_id'];

$camionesModel = new Camiones();

try {

    var_dump($marca_id);
    
    // Consultar modelos de la marca seleccionada
    $modelos = $camionesModel->obtenerModelos($marca_id);

    var_dump($modelos);
    // exit;
    
    // Generar HTML para el select
    echo '<option value="0">-- modelo --</option>';
    
    foreach ($modelos as $modelo) {
        echo '<option value="' . htmlspecialchars($modelo['id_modelo']) . '">';
        echo htmlspecialchars($modelo['nombre_modelo']);
        echo '</option>';
    }
    
    // Si no hay modelos
    if (count($modelos) == 0) {
        echo '<option value="0">No hay modelos disponibles</option>';
    }
    
} catch(PDOException $e) {
    echo '<option value="">Error al cargar modelos</option>';
    error_log("Error en get_modelos.php: " . $e->getMessage());
}
?>