<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['file'])) {
    $file = basename($_POST['file']);
    $filePath = '/var/www/proyecto/uploads/' . $file;

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "Archivo no encontrado.";
    }
}
?>
