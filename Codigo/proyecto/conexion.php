<?php
$servername = "localhost";
$username = "web";
$password = "Proyecto1.";
$dbname = "db_users";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

?>
