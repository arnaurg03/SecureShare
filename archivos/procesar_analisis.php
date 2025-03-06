<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
    $directorio_subida = "uploads/";
    $archivo_subido = $directorio_subida . basename($_FILES["archivo"]["name"]);

    // Mover el archivo al directorio de uploads
    if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $archivo_subido)) {
        // Ejecutar el script Python para analizar el archivo
        $comando = escapeshellcmd("python3 analizar.py " . escapeshellarg($archivo_subido));
        $salida = shell_exec($comando);

        // Guardar el resultado en sesión para mostrarlo en analisis.php
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
