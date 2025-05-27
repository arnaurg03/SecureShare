<?php
// Conexión BD
$servername = "localhost";
$username = "root";
$password = "Proyecto1.";
$dbname = "db_users";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recoger los datos del formulario
$user = $_POST["username"];
$pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
$id_departamento = $_POST["id_departamento"];
$rol = "usuario"; // Puedes cambiar esto si haces un registro de admin manual

// Insertar el usuario
$stmt = $conn->prepare("INSERT INTO usuarios (username, password, rol, id_departamento) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    die("Error al preparar la declaración: " . $conn->error);
}
$stmt->bind_param("sssi", $user, $pass, $rol, $id_departamento);

if ($stmt->execute()) {
    echo "<script>alert('Usuario registrado con éxito'); window.location.href='login.php';</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
