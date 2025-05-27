<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario']; // El nombre de usuario
$directorio_usuario = "uploads/" . $usuario;

// Crear carpeta si no existe
if (!file_exists($directorio_usuario)) {
    mkdir($directorio_usuario, 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
    $archivo_subido = $directorio_usuario . "/" . basename($_FILES["archivo"]["name"]);

    if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $archivo_subido)) {
        $comando = escapeshellcmd("python3 analizar.py " . escapeshellarg($archivo_subido));
        $salida = shell_exec($comando);

        $_SESSION["resultado_analisis"] = nl2br(htmlspecialchars($salida));
        header("Location: analisis.php");
        exit;
    } else {
        echo "Error al subir el archivo.";
    }
} else {
    echo "No se recibió ningún archivo.";
}
?>
