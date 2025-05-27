<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    exit('No autorizado');
}

$usuario = $_SESSION['usuario'];

if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('Archivo no especificado');
}

$archivo = basename($_GET['file']); // Evita rutas peligrosas
$ruta = __DIR__ . "/uploads/$usuario/$archivo";

if (!file_exists($ruta)) {
    http_response_code(404);
    exit('Archivo no encontrado');
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($ruta) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($ruta));

readfile($ruta);
exit;
