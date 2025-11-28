<?php
require_once 'valida_sesion.php';
// editar_camion.php - Formulario para editar camión

require_once 'db_config.php';
require_once 'camion_class.php';
require_once 'imagen_class.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$camionObj = new Camion();
$imagenObj = new Imagen();

$camion = $camionObj->obtenerPorId($id);
if (!$camion) {
    header("Location: index_crud.php?mensaje=Camión no encontrado&tipo=error");
    exit;
}

$imagenes = $imagenObj->obtenerPorCamion($id);
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Actualizar datos del camión
    $datos = [
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'precio' => floatval($_POST['precio'] ?? 0),
        'ciudad' => trim($_POST['ciudad'] ?? ''),
        'agno' => intval($_POST['agno'] ?? 0),
        'combustible' => trim($_POST['combustible'] ?? ''),
        'transmision' => trim($_POST['transmision'] ?? ''),
        'kilometraje' => intval($_POST['kilometraje'] ?? 0),
        'color' => trim($_POST['color'] ?? ''),
        'cilindrada' => trim($_POST['cilindrada'] ?? ''),
        'marca_id' => intval($_POST['marca_id'] ?? 0),
        'modelo_id' => intval($_POST['modelo_id'] ?? 0)
    ];
    
    // Validaciones
    if (empty($datos['descripcion'])) {
        $errores[] = 'La descripción es requerida';
    }
    if ($datos['precio'] <= 0) {
        $errores[] = 'El precio debe ser mayor a 0';
    }
    
    if (empty($errores)) {
        if ($camionObj->actualizar($id, $datos)) {
            // Subir nuevas imágenes si hay
            if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
                $resultados = $imagenObj->subirMultiples($_FILES['imagenes'], $id);
                $exitosas = count(array_filter($resultados, fn($r) => $r['success']));
            }
            
            $mensaje = "Camión actualizado exitosamente.";
            if (isset($exitosas) && $exitosas > 0) {
                $mensaje .= " Se agregaron {$exitosas} nueva(s) imagen(es).";
            }
            
            header("Location: editar_camion.php?id={$id}&mensaje=" . urlencode($mensaje) . "&tipo=success");
            exit;
        } else {
            $errores[] = 'Error al actualizar el camión';
        }
    }
    
    // Si hay errores, mantener los datos ingresados
    $camion = array_merge($camion, $datos);
}

// Manejar eliminación de imagen
if (isset($_GET['eliminar_imagen'])) {
    $imagen_id = intval($_GET['eliminar_imagen']);
    if ($imagenObj->eliminar($imagen_id)) {
        header("Location: editar_camion.php?id={$id}&mensaje=Imagen eliminada&tipo=success");
        exit;
    }
}

$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'success';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Camión</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .file-input-wrapper {
            border: 2px dashed #ddd;
            border-radius: 4px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .file-input-wrapper:hover {
            background-color: #f8f9fa;
        }
        .file-input-wrapper input[type="file"] {
            display: none;
        }
        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .images-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        .images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .image-item {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
        }
        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        .image-actions {
            position: absolute;
            top: 5px;
            right: 5px;
            display: flex;
            gap: 5px;
        }
        .image-order {
            position: absolute;
            bottom: 5px;
            left: 5px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .info-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        #file-list {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Camión #<?php echo $id; ?></h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errores)): ?>
            <div class="alert alert-error">
                <strong>Errores:</strong>
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="descripcion">Descripción *</label>
                <textarea name="descripcion" id="descripcion" required><?php echo htmlspecialchars($camion['descripcion']); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="precio">Precio *</label>
                    <input type="number" name="precio" id="precio" step="0.01" required
                           value="<?php echo htmlspecialchars($camion['precio']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="agno">Año *</label>
                    <input type="number" name="agno" id="agno" min="1900" max="<?php echo date('Y') + 1; ?>" required
                           value="<?php echo htmlspecialchars($camion['agno']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="ciudad">Ciudad *</label>
                    <input type="text" name="ciudad" id="ciudad" required
                           value="<?php echo htmlspecialchars($camion['ciudad']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="kilometraje">Kilometraje</label>
                    <input type="number" name="kilometraje" id="kilometraje"
                           value="<?php echo htmlspecialchars($camion['kilometraje']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="combustible">Combustible</label>
                    <select name="combustible" id="combustible">
                        <option value="">Seleccionar...</option>
                        <option value="Diesel" <?php echo $camion['combustible'] === 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                        <option value="Gasolina" <?php echo $camion['combustible'] === 'Gasolina' ? 'selected' : ''; ?>>Gasolina</option>
                        <option value="Gas" <?php echo $camion['combustible'] === 'Gas' ? 'selected' : ''; ?>>Gas</option>
                        <option value="Eléctrico" <?php echo $camion['combustible'] === 'Eléctrico' ? 'selected' : ''; ?>>Eléctrico</option>
                        <option value="Híbrido" <?php echo $camion['combustible'] === 'Híbrido' ? 'selected' : ''; ?>>Híbrido</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="transmision">Transmisión</label>
                    <select name="transmision" id="transmision">
                        <option value="">Seleccionar...</option>
                        <option value="Manual" <?php echo $camion['transmision'] === 'Manual' ? 'selected' : ''; ?>>Manual</option>
                        <option value="Automática" <?php echo $camion['transmision'] === 'Automática' ? 'selected' : ''; ?>>Automática</option>
                        <option value="Semi-automática" <?php echo $camion['transmision'] === 'Semi-automática' ? 'selected' : ''; ?>>Semi-automática</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" name="color" id="color"
                           value="<?php echo htmlspecialchars($camion['color']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="cilindrada">Cilindrada</label>
                    <input type="text" name="cilindrada" id="cilindrada"
                           value="<?php echo htmlspecialchars($camion['cilindrada']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="marca_id">ID Marca</label>
                    <input type="number" name="marca_id" id="marca_id"
                           value="<?php echo htmlspecialchars($camion['marca_id']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="modelo_id">ID Modelo</label>
                    <input type="number" name="modelo_id" id="modelo_id"
                           value="<?php echo htmlspecialchars($camion['modelo_id']); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Agregar nuevas imágenes (<?php echo count($imagenes); ?>/<?php echo MAX_IMAGES_PER_TRUCK; ?>)</label>
                <?php if (count($imagenes) < MAX_IMAGES_PER_TRUCK): ?>
                    <div class="file-input-wrapper" onclick="document.getElementById('imagenes').click()">
                        <input type="file" name="imagenes[]" id="imagenes" multiple accept="image/*" onchange="mostrarArchivos()">
                        <p>📷 Haz clic aquí para seleccionar imágenes</p>
                        <p class="info-text">Puedes agregar hasta <?php echo MAX_IMAGES_PER_TRUCK - count($imagenes); ?> imágenes más</p>
                    </div>
                    <div id="file-list"></div>
                <?php else: ?>
                    <p class="info-text">Has alcanzado el límite máximo de imágenes. Elimina algunas para agregar nuevas.</p>
                <?php endif; ?>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="index_crud.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
        
        <?php if (count($imagenes) > 0): ?>
            <div class="images-section">
                <h2>📷 Imágenes Actuales (<?php echo count($imagenes); ?>)</h2>
                <div class="images-grid">
                    <?php foreach ($imagenes as $imagen): ?>
                        <div class="image-item">
                            <img src="uploads/camiones/<?php echo htmlspecialchars($imagen['url']); ?>" 
                                 alt="Imagen del camión">
                            <div class="image-order">#<?php echo $imagen['orden']; ?></div>
                            <div class="image-actions">
                                <a href="editar_camion.php?id=<?php echo $id; ?>&eliminar_imagen=<?php echo $imagen['id_imagen']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('¿Eliminar esta imagen?')">✕</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function mostrarArchivos() {
            const input = document.getElementById('imagenes');
            const fileList = document.getElementById('file-list');
            const files = input.files;
            
            if (files.length > 0) {
                let html = '<strong>Archivos seleccionados:</strong><ul>';
                for (let i = 0; i < files.length; i++) {
                    html += `<li>${files[i].name} (${(files[i].size / 1024 / 1024).toFixed(2)} MB)</li>`;
                }
                html += '</ul>';
                fileList.innerHTML = html;
            } else {
                fileList.innerHTML = '';
            }
        }
    </script>
</body>
</html>