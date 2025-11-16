<?php
// index_crud.php - Listado de camiones

require_once 'db_config.php';
require_once 'camion_class.php';
require_once 'imagen_class.php';

$camionObj = new Camion();
$imagenObj = new Imagen();

// Paginación
$porPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $porPagina;

// Buscar
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if ($busqueda) {
    $camiones = $camionObj->buscar($busqueda);
    $totalCamiones = count($camiones);
} else {
    $camiones = $camionObj->obtenerTodos($porPagina, $offset);
    $totalCamiones = $camionObj->contarTotal();
}

$totalPaginas = ceil($totalCamiones / $porPagina);

// Mensajes
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'success';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Camiones</title>
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
            max-width: 1200px;
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
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        .search-form input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 250px;
        }
        .btn {
            padding: 10px 20px;
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
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 30px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #007bff;
            border-radius: 4px;
        }
        .pagination .active {
            background-color: #007bff;
            color: white;
        }
        .pagination a:hover {
            background-color: #e9ecef;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #6c757d;
            color: white;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Gestión de Camiones</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        
        <div class="top-bar">
            <form method="GET" class="search-form">
                <input type="text" name="buscar" placeholder="Buscar camiones..." 
                       value="<?php echo htmlspecialchars($busqueda); ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if ($busqueda): ?>
                    <a href="index_crud.php" class="btn btn-warning">Limpiar</a>
                <?php endif; ?>
            </form>
            <a href="crear_camion.php" class="btn btn-success">+ Nuevo Camión</a>
        </div>
        
        <?php if (count($camiones) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Año</th>
                        <th>Ciudad</th>
                        <th>Kilometraje</th>
                        <th>Imágenes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($camiones as $camion): ?>
                        <?php 
                            $imagenes = $imagenObj->obtenerPorCamion($camion['id_camion']);
                            $primeraImagen = count($imagenes) > 0 ? $imagenes[0] : null;
                        ?>
                        <tr>
                            <td><?php echo $camion['id_camion']; ?></td>
                            <td>
                                <?php if ($primeraImagen): ?>
                                    <img src="uploads/camiones/<?php echo htmlspecialchars($primeraImagen['url']); ?>" 
                                         alt="Camión" class="thumbnail">
                                <?php else: ?>
                                    <div class="thumbnail" style="background: #ddd; display: flex; align-items: center; justify-content: center;">📷</div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($camion['descripcion']); ?></td>
                            <td>$<?php echo number_format($camion['precio'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($camion['agno']); ?></td>
                            <td><?php echo htmlspecialchars($camion['ciudad']); ?></td>
                            <td><?php echo number_format($camion['kilometraje'], 0, ',', '.'); ?> km</td>
                            <td>
                                <span class="badge"><?php echo count($imagenes); ?> fotos</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="ver_camion.php?id=<?php echo $camion['id_camion']; ?>" 
                                       class="btn btn-primary btn-sm">Ver</a>
                                    <a href="editar_camion.php?id=<?php echo $camion['id_camion']; ?>" 
                                       class="btn btn-warning btn-sm">Editar</a>
                                    <a href="eliminar_camion.php?id=<?php echo $camion['id_camion']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('¿Estás seguro de eliminar este camión?')">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if ($totalPaginas > 1): ?>
                <div class="pagination">
                    <?php if ($pagina > 1): ?>
                        <a href="?pagina=<?php echo $pagina - 1; ?><?php echo $busqueda ? '&buscar=' . urlencode($busqueda) : ''; ?>">« Anterior</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <?php if ($i == $pagina): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?pagina=<?php echo $i; ?><?php echo $busqueda ? '&buscar=' . urlencode($busqueda) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($pagina < $totalPaginas): ?>
                        <a href="?pagina=<?php echo $pagina + 1; ?><?php echo $busqueda ? '&buscar=' . urlencode($busqueda) : ''; ?>">Siguiente »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-data">
                <p>No se encontraron camiones.</p>
                <?php if ($busqueda): ?>
                    <a href="index_crud.php" class="btn btn-primary">Ver todos</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>