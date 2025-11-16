<?php
// crear_camion.php - Formulario para crear nuevo camión

require_once 'db_config.php';
require_once 'camion_class.php';
require_once 'imagen_class.php';

$errores = [];
$datos = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar datos
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

    // var_dump($datos);
    // exit;    
    // Validaciones
    if (empty($datos['descripcion'])) {
        $errores[] = 'La descripción es requerida';
    }
    if ($datos['precio'] <= 0) {
        $errores[] = 'El precio debe ser mayor a 0';
    }
    if (empty($datos['ciudad'])) {
        $errores[] = 'La ciudad es requerida';
    }
    if ($datos['agno'] < 1900 || $datos['agno'] > date('Y') + 1) {
        $errores[] = 'El año no es válido';
    }
    
    if (empty($errores)) {
        $camionObj = new Camion();
        $camion_id = $camionObj->crear($datos);
        
        if ($camion_id) {
            // Subir imágenes si hay
            if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {

                // var_dump($_FILES['imagenes']);
                // exit;

                $imagenObj = new Imagen();
                $resultados = $imagenObj->subirMultiples($_FILES['imagenes'], $camion_id);
                
                $exitosas = count(array_filter($resultados, fn($r) => $r['success']));
                $fallidas = count($resultados) - $exitosas;
            }
            
            $mensaje = "Camión creado exitosamente.";
            if (isset($exitosas)) {
                $mensaje .= " Se subieron {$exitosas} imagen(es).";
                if ($fallidas > 0) {
                    $mensaje .= " {$fallidas} imagen(es) no se pudieron subir.";
                }
            }
            
            header("Location: index_crud.php?mensaje=" . urlencode($mensaje) . "&tipo=success");
            exit;
        } else {
            $errores[] = 'Error al crear el camión';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Camión</title>
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-error ul {
            margin-left: 20px;
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
        <h1>🚛 Crear Nuevo Camión</h1>
        
        <?php if (!empty($errores)): ?>
            <div class="alert-error">
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
                <textarea name="descripcion" id="descripcion" required><?php echo htmlspecialchars($datos['descripcion'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="precio">Precio *</label>
                    <input type="number" name="precio" id="precio" step="0.01" required
                           value="<?php echo htmlspecialchars($datos['precio'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="agno">Año *</label>
                    <input type="number" name="agno" id="agno" min="1900" max="<?php echo date('Y') + 1; ?>" required
                           value="<?php echo htmlspecialchars($datos['agno'] ?? date('Y')); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="ciudad">Ciudad *</label>
                    <input type="text" name="ciudad" id="ciudad" required
                           value="<?php echo htmlspecialchars($datos['ciudad'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="kilometraje">Kilometraje</label>
                    <input type="number" name="kilometraje" id="kilometraje"
                           value="<?php echo htmlspecialchars($datos['kilometraje'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="combustible">Combustible</label>
                    <select name="combustible" id="combustible">
                        <option value="">Seleccionar...</option>
                        <option value="Diesel" <?php echo ($datos['combustible'] ?? '') === 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                        <option value="Gasolina" <?php echo ($datos['combustible'] ?? '') === 'Gasolina' ? 'selected' : ''; ?>>Gasolina</option>
                        <option value="Gas" <?php echo ($datos['combustible'] ?? '') === 'Gas' ? 'selected' : ''; ?>>Gas</option>
                        <option value="Eléctrico" <?php echo ($datos['combustible'] ?? '') === 'Eléctrico' ? 'selected' : ''; ?>>Eléctrico</option>
                        <option value="Híbrido" <?php echo ($datos['combustible'] ?? '') === 'Híbrido' ? 'selected' : ''; ?>>Híbrido</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="transmision">Transmisión</label>
                    <select name="transmision" id="transmision">
                        <option value="">Seleccionar...</option>
                        <option value="Manual" <?php echo ($datos['transmision'] ?? '') === 'Manual' ? 'selected' : ''; ?>>Manual</option>
                        <option value="Automática" <?php echo ($datos['transmision'] ?? '') === 'Automática' ? 'selected' : ''; ?>>Automática</option>
                        <option value="Semi-automática" <?php echo ($datos['transmision'] ?? '') === 'Semi-automática' ? 'selected' : ''; ?>>Semi-automática</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" name="color" id="color"
                           value="<?php echo htmlspecialchars($datos['color'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="cilindrada">Cilindrada</label>
                    <input type="text" name="cilindrada" id="cilindrada"
                           value="<?php echo htmlspecialchars($datos['cilindrada'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="marca_id">ID Marca</label>
                    <input type="number" name="marca_id" id="marca_id"
                           value="<?php echo htmlspecialchars($datos['marca_id'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="modelo_id">ID Modelo</label>
                    <input type="number" name="modelo_id" id="modelo_id"
                           value="<?php echo htmlspecialchars($datos['modelo_id'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Imágenes (máximo 20)</label>
                <div class="file-input-wrapper" onclick="document.getElementById('imagenes').click()">
                    <input type="file" name="imagenes[]" id="imagenes" multiple accept="image/*" onchange="mostrarArchivos()">
                    <p>📷 Haz clic aquí para seleccionar imágenes</p>
                    <p class="info-text">Puedes seleccionar múltiples archivos (máx. 5MB cada uno)</p>
                </div>
                <div id="file-list"></div>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Crear Camión</button>
                <a href="index_crud.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
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