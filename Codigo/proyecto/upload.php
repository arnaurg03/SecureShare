<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['username'])) {
    // Si no hay sesión activa, redirigir a la página de inicio
    header("Location: index.html");
    exit;
}

// Comprobar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $target_dir = "uploads/";

    // Iterar sobre cada archivo o carpeta seleccionada
    foreach ($_FILES['file']['name'] as $index => $filename) {
        $target_file = $target_dir . basename($filename);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Comprobar si el archivo ya existe
        if (file_exists($target_file)) {
            echo "<script>alert('El archivo o carpeta $filename ya existe.'); window.location.href='analisis.php';</script>";
            $uploadOk = 0;
        }

        // Si es una carpeta, mover su contenido
        if (is_dir($_FILES['file']['tmp_name'][$index])) {
            $dir = $_FILES['file']['tmp_name'][$index];
            $dir_target = $target_dir . basename($filename) . '/';
            if (!mkdir($dir_target) && !is_dir($dir_target)) {
                echo "<script>alert('Hubo un error al crear la carpeta $filename.'); window.location.href='analisis.php';</script>";
                $uploadOk = 0;
            } else {
                // Recorrer y mover los archivos dentro de la carpeta
                foreach (scandir($dir) as $file) {
                    if ($file != '.' && $file != '..') {
                        $file_target = $dir_target . $file;
                        if (!move_uploaded_file($dir . '/' . $file, $file_target)) {
                            echo "<script>alert('Hubo un error al mover el archivo $file dentro de la carpeta $filename.'); window.location.href='analisis.php';</script>";
                            $uploadOk = 0;
                        }
                    }
                }
            }
        } else { // Si es un archivo individual, moverlo
            if (!move_uploaded_file($_FILES['file']['tmp_name'][$index], $target_file)) {
                echo "<script>alert('Hubo un error al subir el archivo $filename.'); window.location.href='analisis.php';</script>";
                $uploadOk = 0;
            }
        }

        // Mostrar mensaje de éxito si todo salió bien
        if ($uploadOk == 1) {
            echo "<script>alert('El archivo o carpeta $filename ha sido subido correctamente.'); window.location.href='analisis.php';</script>";
        }
    }
} else {
    echo "<script>alert('No se ha seleccionado ningún archivo o carpeta.'); window.location.href='analisis.php';</script>";
}
?>
