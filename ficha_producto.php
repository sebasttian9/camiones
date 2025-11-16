<?php

require_once "./include/Camiones.php";

$camionesModel = new Camiones();

// $vehiculos = $camionesModel->obtenerCamiones();
// $marcas = $camionesModel->obtenerMarcas();
// $modelos = $camionesModel->obtenerModelos();
// print_r($vehiculos);

$vehiculo = $camionesModel->obtenerCamionPorId($_GET['cam']);
$imagenes = $camionesModel->obtenerImabgenesCamion($_GET['cam']);
// print_r($uno);
// Datos del vehículo
// $vehiculo = [
//     'marca' => 'FREIGHTLINER',
//     'modelo' => 'CASCADIA 113 450HP 6X4',
//     'anio' => '2019',
//     'precio' => 47000000,
//     'precio_iva' => 55930000,
//     'color' => 'BLANCO',
//     'transmision' => 'Automatizada (AMT)',
//     'kilometraje' => '892.563 KM',
//     'combustible' => 'Diésel',
//     'cilindrada' => '-',
//     'imagenes' => [
//         'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1581540222194-0def2dda95b8?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1622819584099-e04ccb14e8a5?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1553440569-bcc63803a83d?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1590362891991-f776e747a588?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1619767886558-efdc259cde1a?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1611651149784-83ff96c7e9f1?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop',
//         'https://images.unsplash.com/photo-1534650238397-1c46c2a9a036?w=800&h=600&fit=crop'
//     ]
// ];

// Vehículos relacionados
$relacionados = [
    [
        'marca' => 'FREIGHTLINER',
        'modelo' => 'CASCADIA',
        'precio' => 47000000,
        'anio' => '2018',
        'combustible' => 'Diésel',
        'transmision' => 'Automatizada (AMT)',
        'imagen' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=400&h=300&fit=crop'
    ],
    [
        'marca' => 'FOTON',
        'modelo' => 'AUMAN',
        'precio' => 49000000,
        'anio' => '2022',
        'combustible' => 'Diésel',
        'transmision' => 'Automatizada (AMT)',
        'imagen' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=400&h=300&fit=crop'
    ],
    [
        'marca' => 'MERCEDES-BENZ',
        'modelo' => 'E 200',
        'precio' => 46500000,
        'anio' => '2023',
        'combustible' => 'Diésel',
        'transmision' => 'Automática',
        'imagen' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=400&h=300&fit=crop'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $vehiculo['marca'] . ' ' . $vehiculo['modelo']; ?> - ClicChile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #e63946;
            --secondary-color: #5e35b1;
            --dark-bg: #1a1a1a;
            --success-color: #25d366;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Styles */
        .top-header {
            background: var(--dark-bg);
            padding: 20px 0;
        }

        .logo-section {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 10px;
            display: inline-block;
        }

        .main-banner {
            background: var(--dark-bg);
            color: white;
            padding: 30px 0;
        }

        .banner-title {
            border: 3px solid var(--secondary-color);
            padding: 20px 40px;
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .banner-subtitle {
            margin-top: 15px;
            letter-spacing: 3px;
            font-size: 0.9rem;
        }

        /* Navigation */
        .main-nav {
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px 0;
        }

        .main-nav a {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            transition: color 0.3s;
        }

        .main-nav a:hover {
            color: var(--primary-color);
        }

        /* Breadcrumb */
        .breadcrumb-section {
            background: #f8f9fa;
            padding: 15px 0;
            margin-bottom: 30px;
        }

        .breadcrumb {
            background: transparent;
            margin: 0;
        }

        .breadcrumb-item a {
            color: #666;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
        }

        /* Vehicle Detail Section */
        .vehicle-detail-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .vehicle-icon {
            background: var(--primary-color);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }

        .vehicle-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .vehicle-subtitle {
            color: #666;
            font-size: 1rem;
        }

        .back-button {
            background: var(--primary-color);
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .back-button:hover {
            background: #d62828;
            color: white;
            transform: translateX(-5px);
        }

        /* Specs Grid */
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .spec-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
        }

        .spec-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
        }

        .spec-value {
            font-size: 1.1rem;
            color: #333;
            font-weight: bold;
            margin-top: 5px;
        }

        /* Image Gallery */
        .gallery-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            height: 100%;
        }

        .main-image-container {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .main-carousel-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }

        .carousel-control-prev {
            left: 20px;
        }

        .carousel-control-next {
            right: 20px;
        }

        .thumbnail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            max-height: 470px;
            overflow-y: auto;
            padding-right: 5px;
            margin-top: 1rem;
            
        }

        .thumbnail-grid::-webkit-scrollbar {
            width: 8px;
        }

        .thumbnail-grid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .thumbnail-grid::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .thumbnail-grid::-webkit-scrollbar-thumb:hover {
            background: #d62828;
        }

        .thumbnail {
            cursor: pointer;
            border-radius: 8px;
            overflow: hidden;
            border: 3px solid transparent;
            transition: all 0.3s;
        }

        .thumbnail:hover {
            border-color: var(--primary-color);
        }

        .thumbnail.active {
            border-color: var(--primary-color);
        }

        .thumbnail img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        /* Price Section */
        .price-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .price-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .price-label {
            font-size: 1.2rem;
            color: #666;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .price-amount {
            font-size: 2.5rem;
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 5px;
        }

        .price-iva {
            font-size: 1rem;
            color: #666;
            margin-bottom: 20px;
        }

        /* Contact Form */
        .contact-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .contact-form h4 {
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .form-control {
            border: 2px solid #ddd;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .btn-submit {
            background: var(--primary-color);
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #d62828;
        }

        .btn-whatsapp {
            background: var(--success-color);
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn-whatsapp:hover {
            background: #128c7e;
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #666;
        }

        /* Related Vehicles */
        .related-section {
            margin-top: 50px;
            padding: 40px 0;
            background: #f8f9fa;
        }

        .section-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
        }

        .related-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .related-card:hover {
            transform: translateY(-5px);
        }

        .related-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .related-info {
            padding: 20px;
        }

        .related-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .related-price {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .related-specs {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .btn-details {
            background: var(--primary-color);
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
        }

        /* WhatsApp Float */
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
            text-decoration: none;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            color: #FFF;
            text-decoration: none;
        }

        /* Footer */
        .main-footer {
            background: var(--dark-bg);
            color: white;
            padding: 60px 0 0 0;
            margin-top: 0;
        }

        .footer-section {
            margin-bottom: 30px;
        }

        .footer-title {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }

        .footer-logo {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(255,255,255,0.05);
            border-radius: 5px;
            transition: background 0.3s;
            
        }

        .footer-contact-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-right: 15px;
            min-width: 25px;
            margin-top: 3px;
        }

        .footer-contact-item:hover {
            background: rgba(255,255,255,0.1);
        }

        .footer-contact-info a {
            color: white;
            text-decoration: none;
        }

        .footer-social {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .footer-social a {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .footer-social a:hover {
            background: var(--primary-color);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .footer-bottom {
            background: #000;
            padding: 25px 0;
            margin-top: 40px;
            text-align: center;
        }

        .footer-bottom p {
            margin: 0;
            color: #999;
        }

        .logo_clic {
                width: 100%;
                height: auto;
                display: inline-block; /* elimina espacios debajo de la imagen */
                border-radius: 10%;        
        }        

        @media (max-width: 768px) {

            .logo_clic {
                width: 50%;
                height: auto;
                display: inline-block; /* elimina espacios debajo de la imagen */
                border-radius: 10%;        
            }

            .div-logo {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
            }


            .banner-title {
                font-size: 1.5rem;
                padding: 15px 20px;
            }

            .vehicle-title {
                font-size: 1.5rem;
            }

            .price-amount {
                font-size: 2rem;
            }

            .main-carousel-image {
                height: 300px;
            }

            .thumbnail img {
                height: 80px;
            }

            .specs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 div-logo">
                    <img src="./assets/img/logo_clichile.jpeg" class="logo_clic" alt="">
                    <!--div class="logo-section">
                        <h2 class="mb-0" style="color: var(--primary-color);">
                            <i class="fas fa-hand-pointer"></i> ClicChile
                        </h2>
                        <small>Toda la Industria en un portal</small>
                    </div-->
                </div>
                <div class="col-md-9">
                    <div class="main-banner">
                        <div class="text-center">
                            <div class="banner-title">
                                SOMOS TU PORTAL CAMIONERO
                            </div>
                            <div class="banner-subtitle">
                                CON SOLO UN CLIC ENCUENTRA LA SOLUCIÓN QUE BUSCAS
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <div class="d-flex justify-content-center flex-wrap">
                <a href="index_crud.php">INICIO</a>
                <a href="#quienes-somos">QUIENES SOMOS</a>
                <a href="#representaciones">REPRESENTACIONES</a>
                <a href="#publicaciones">PUBLICACIONES</a>
                <a href="#cobertura">COBERTURA</a>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="portal_automotriz.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="portal_automotriz.php#catalogo">Catálogo</a></li>
                    <li class="breadcrumb-item active"><?php echo $vehiculo['marca'] . ' ' . $vehiculo['modelo']; ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Vehicle Detail -->
    <div class="container mb-5">
        <!-- Header -->
        <div class="vehicle-detail-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="vehicle-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <h1 class="vehicle-title"><?php echo $vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ' ' . $vehiculo['agno']; ?></h1>
                            <p class="vehicle-subtitle">Detalles del vehículo</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="portal_automotriz.php#catalogo" class="back-button">
                        <i class="fas fa-arrow-left"></i> Volver al catálogo
                    </a>
                </div>
            </div>

            <!-- Specs Grid -->
            <div class="specs-grid">
                <div class="spec-box">
                    <div class="spec-label">Color</div>
                    <div class="spec-value"><?php echo $vehiculo['color']; ?></div>
                </div>
                <div class="spec-box">
                    <div class="spec-label">Transmisión</div>
                    <div class="spec-value"><?php echo $vehiculo['transmision']; ?></div>
                </div>
                <div class="spec-box">
                    <div class="spec-label">Año</div>
                    <div class="spec-value"><?php echo $vehiculo['agno']; ?></div>
                </div>
                <div class="spec-box">
                    <div class="spec-label">Kms</div>
                    <div class="spec-value"><?php echo $vehiculo['kilometraje']; ?></div>
                </div>
                <div class="spec-box">
                    <div class="spec-label">Combustible</div>
                    <div class="spec-value"><?php echo $vehiculo['combustible']; ?></div>
                </div>
                <div class="spec-box">
                    <div class="spec-label">Cilindrada</div>
                    <div class="spec-value"><?php echo $vehiculo['cilindrada']; ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gallery -->
            <div class="col-lg-8">
                <div class="gallery-section">
                    <!-- Main Carousel -->
                    <div id="vehicleCarousel" class="carousel slide">
                        <div class="carousel-inner">
                            <?php
                                $index = 0;
                             foreach($imagenes as $imagen): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="./admin/uploads/camiones/<?php echo $imagen['url']; ?>" class="main-carousel-image" alt="<?php echo $vehiculo['marca']; ?>">
                            </div>
                            <?php 
                                $index++;
                            endforeach; 
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                    <!-- Thumbnails -->
                    <div class="thumbnail-grid">
                        <?php 
                            $index = 0;
                        foreach($imagenes as $imagen): ?>
                        <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeSlide(<?php echo $index; ?>)">
                            <img src="./admin/uploads/camiones/<?php echo $imagen['url']; ?>" alt="Miniatura <?php echo $index + 1; ?>">
                        </div>
                        <?php 
                            $index++;
                        endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Price and Contact -->
            <div class="col-lg-4">
                <div class="price-section">
                    <div class="price-content">
                        <div class="price-label">PRECIO</div>
                        <div class="price-amount">$<?php echo number_format($vehiculo['precio'], 0, ',', '.'); ?></div>
                        <div class="price-iva">$<?php echo number_format($vehiculo['precio']*1.19, 0, ',', '.'); ?> IVA INC.</div>

                        <!-- Contact Form -->
                        <div class="contact-form">
                            <h4>COTIZA</h4>
                            <form>
                                <input type="text" class="form-control" placeholder="Nombre" required>
                                <input type="text" class="form-control" placeholder="Apellido" required>
                                <input type="text" class="form-control" placeholder="Rut" required>
                                <input type="tel" class="form-control" placeholder="Teléfono" required>
                                <input type="email" class="form-control" placeholder="E-mail" required>
                                <textarea class="form-control" rows="3" placeholder="Mensaje"></textarea>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="cotizaFinanciamiento">
                                    <label class="form-check-label" for="cotizaFinanciamiento">
                                        COTIZA TU FINANCIAMIENTO
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="dejaVehiculo">
                                    <label class="form-check-label" for="dejaVehiculo">
                                        DEJA TU VEHÍCULO EN PARTE DE PAGO
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-submit">ENVIAR</button>
                                <button type="button" class="btn btn-whatsapp">
                                    <i class="fab fa-whatsapp"></i> Contactar por WhatsApp
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Vehicles -->
    <div class="related-section">
        <div class="container">
            <h2 class="section-title">Vehículos Relacionados</h2>
            <div class="row">
                <?php foreach($relacionados as $relacionado): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="related-card">
                        <img src="<?php echo $relacionado['imagen']; ?>" alt="<?php echo $relacionado['marca']; ?>" class="related-image">
                        <div class="related-info">
                            <h5 class="related-title"><?php echo $relacionado['marca'] . ' ' . $relacionado['modelo']; ?></h5>
                            <div class="related-price">$ <?php echo number_format($relacionado['precio'], 0, ',', '.'); ?></div>
                            <div class="related-specs">
                                <?php echo $relacionado['anio']; ?> • <?php echo $relacionado['combustible']; ?> • <?php echo $relacionado['transmision']; ?>
                            </div>
                            <button class="btn btn-details">DETALLES</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <!-- Sobre Nosotros -->
                <div class="col-lg-4 col-md-6 footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-hand-pointer"></i> ClicChile
                    </div>
                    <p style="color: #ccc; line-height: 1.8;">
                        Tu portal automotriz líder en Chile. Conectamos a compradores y vendedores de vehículos comerciales con la mejor tecnología y servicio del mercado.
                    </p>
                    <div class="footer-social">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="col-lg-4 col-md-6 footer-section">
                    <h3 class="footer-title">Contáctanos</h3>
                    
                    <div class="footer-contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <div class="footer-contact-info">
                            <strong>Teléfonos:</strong><br>
                            <a href="tel:+56222345678">+56 2 2234 5678</a><br>
                            <a href="tel:+56998765432">+56 9 9876 5432</a>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <i class="fas fa-envelope"></i>
                        <div class="footer-contact-info">
                            <strong>Email:</strong><br>
                            <a href="mailto:contacto@clicchile.cl">contacto@clicchile.cl</a><br>
                            <a href="mailto:ventas@clicchile.cl">ventas@clicchile.cl</a>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="footer-contact-info">
                            <strong>Dirección:</strong><br>
                            Av. Providencia 1234, Oficina 567<br>
                            Santiago, Chile
                        </div>
                    </div>
                </div>

                <!-- Enlaces Rápidos -->
                <div class="col-lg-4 col-md-6 footer-section">
                    <h3 class="footer-title">Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="#quienes-somos"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Quiénes Somos</a></li>
                        <li><a href="#representaciones"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Representaciones</a></li>
                        <li><a href="#publicaciones"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Publicaciones</a></li>
                        <li><a href="#cobertura"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Cobertura</a></li>
                        <li><a href="#catalogo"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Catálogo de Vehículos</a></li>
                        <li><a href="#financiamiento"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Financiamiento</a></li>
                        <li><a href="#terminos"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Términos y Condiciones</a></li>
                        <li><a href="#privacidad"><i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-right: 8px;"></i> Política de Privacidad</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p>&copy; <?php echo date('Y'); ?> <strong>ClicChile</strong> - Todos los derechos reservados.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p>
                            Desarrollado con <i class="fas fa-heart" style="color: var(--primary-color);"></i> por 
                            <a href="#">ClicChile Team</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/56912345678" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Social Media Buttons -->
    <div style="position: fixed; right: 20px; top: 50%; transform: translateY(-50%); z-index: 100;">
        <div class="d-flex flex-column gap-2">
            <a href="#" class="btn btn-primary rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="btn" style="background: #00acee; color: white; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="btn" style="background: #0077b5; color: white; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                <i class="fab fa-linkedin-in"></i>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para cambiar el slide del carrusel al hacer clic en las miniaturas
        function changeSlide(index) {
            const carousel = bootstrap.Carousel.getInstance(document.getElementById('vehicleCarousel'));
            carousel.to(index);
            
            // Actualizar la clase active en las miniaturas
            document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        }

        // Actualizar las miniaturas cuando el carrusel cambia
        document.getElementById('vehicleCarousel').addEventListener('slide.bs.carousel', function (e) {
            const index = e.to;
            document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        });

        // Inicializar el carrusel
        window.addEventListener('load', function() {
            const carouselElement = document.querySelector('#vehicleCarousel');
            if (carouselElement) {
                new bootstrap.Carousel(carouselElement, {
                    interval: false, // No cambiar automáticamente
                    wrap: true,
                    touch: true
                });
            }
        });
    </script>
</body>
</html>