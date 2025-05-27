<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión activa, redirigir a la página de inicio de sesión
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureShare - Inicio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #d3d3d3; /* Fondo gris claro */
        }
        .header {
            background-color: #ff8c00; /* Naranja más oscuro */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 2.5em; /* Tamaño de fuente aumentado */
        }
        .nav-bar {
            background-color: #ff8c00; /* Naranja más oscuro */
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .nav-bar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.25em; /* Tamaño de fuente aumentado en un 25% */
        }
        .nav-bar a:hover {
            text-decoration: underline;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        SecureShare
    </div>
    <div class="nav-bar">
        <a href="analisis.php">Analizar archivo</a>
        <a href="perfil.php">Mi perfil</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
	<a href="administrador.php">Administrador</a>
    </div>
    <div class="content">
        <h1>Bienvenido a SecureShare</h1>
        <p>Elija una opción del menú para comenzar.</p>
    </div>
</body>
</html>
