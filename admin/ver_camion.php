<?php
require_once 'valida_sesion.php';
// ver_camion.php - Ver detalles del camión

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Camión #<?php echo $id; ?></title>
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
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 18px;
            color: #333;
        }
        .description-box {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .gallery {
            margin-top: 30px;
        }
        .gallery h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .main-image {
            width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #f8f9fa;
        }
        .thumbnails {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }
        .thumbnail {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 2px solid transparent;
        }
        .thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .thumbnail.active {
            border-color: #007bff;
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
        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .no-images {
            text-align: center;
            padding: 40px;
            color: #666;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #007bff;
            color: white;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚛 Detalles del Camión #<?php echo $id; ?></h1>
        
        <div class="description-box">
            <div class="info-label">Descripción</div>
            <div class="info-value"><?php echo nl2br(htmlspecialchars($camion['descripcion'])); ?></div>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Precio</div>
                <div class="info-value">$<?php echo number_format($camion['precio'], 0, ',', '.'); ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Año</div>
                <div class="info-value"><?php echo htmlspecialchars($camion['agno']); ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Ciudad</div>
                <div class="info-value"><?php echo htmlspecialchars($camion['ciudad']); ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Kilometraje</div>
                <div class="info-value"><?php echo number_format($camion['kilometraje'], 0, ',', '.'); ?> km</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Combustible</div>
                <div class="info-value"><?php echo htmlspecialchars($camion['combustible']); ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Transmisión</div>
                <div class="info-value"><?php echo htmlspecialchars($camion['transmision']); ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Color</div>
                <div class="info-value"><?php echo htmlspecialchars($camion['color']); ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Cilindrada</div>
                <div class="info-value"><?php echo htmlspecialchars($camion['cilindrada']); ?></div>
            </div>
        </div>
        
        <div class="gallery">
            <h2>📷 Galería de Imágenes <span class="badge"><?php echo count($imagenes); ?> fotos</span></h2>
            
            <?php if (count($imagenes) > 0): ?>
                <img id="mainImage" src="uploads/camiones/<?php echo htmlspecialchars($imagenes[0]['url']); ?>" 
                     alt="Imagen principal" class="main-image">
                
                <?php if (count($imagenes) > 1): ?>
                    <div class="thumbnails">
                        <?php foreach ($imagenes as $index => $imagen): ?>
                            <img src="uploads/camiones/<?php echo htmlspecialchars($imagen['url']); ?>" 
                                 alt="Miniatura <?php echo $index + 1; ?>" 
                                 class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>"
                                 onclick="cambiarImagen('uploads/camiones/<?php echo htmlspecialchars($imagen['url']); ?>', this)">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-images">
                    <p>📷 Este camión no tiene imágenes</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="btn-container">
            <a href="editar_camion.php?id=<?php echo $id; ?>" class="btn btn-warning">Editar</a>
            <a href="index_crud.php" class="btn btn-secondary">Volver al Listado</a>
        </div>
    </div>
    
    <script>
        function cambiarImagen(url, elemento) {
            // Cambiar imagen principal
            document.getElementById('mainImage').src = url;
            
            // Actualizar thumbnail activo
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            elemento.classList.add('active');
        }
    </script>
</body>
</html>