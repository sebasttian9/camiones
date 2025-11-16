<?php
// eliminar.php - Eliminar camión

require_once 'db_config.php';
require_once 'camion_class.php';
require_once 'imagen_class.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $camionObj = new Camion();
    
    if ($camionObj->eliminar($id)) {
        header("Location: index_crud.php?mensaje=Camión eliminado exitosamente&tipo=success");
    } else {
        header("Location: index_crud.php?mensaje=Error al eliminar el camión&tipo=error");
    }
} else {
    header("Location: index_crud.php?mensaje=ID de camión inválido&tipo=error");
}
exit;
?>