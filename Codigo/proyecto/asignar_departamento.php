<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once "conexion.php"; // Asegúrate de tener la conexión a la BD aquí

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = intval($_POST['username']);
    $departamento = mysqli_real_escape_string($conn, $_POST['id_departamento']);

    $query = "UPDATE usuarios SET id_departamento = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $departamento, $usuario_id);

    if (mysqli_stmt_execute($stmt)) {	
        // Redirigir de nuevo al panel de administración con éxito
        header("Location: administrador.php?success=1");
        exit;
    } else {
        // Error en la consulta
        echo "Error al asignar departamento.";
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: administrador.php");
    exit;
}
?>
