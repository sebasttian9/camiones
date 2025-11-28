<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirigir al login si no hay sesión
    header('Location: ../portal_automotriz.php');
    exit;
}

// Opcional: Verificar tiempo de inactividad (30 minutos)
$tiempoInactividad = 1800; // 30 minutos en segundos

if (isset($_SESSION['ultimo_acceso'])) {
    $tiempoTranscurrido = time() - $_SESSION['ultimo_acceso'];
    
    if ($tiempoTranscurrido > $tiempoInactividad) {
        // Sesión expirada por inactividad
        session_unset();
        session_destroy();
        header('Location: ../portal_automotriz.php?timeout=1');
        exit;
    }
}

// Actualizar tiempo de último acceso
$_SESSION['ultimo_acceso'] = time();
?>