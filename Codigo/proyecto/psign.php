<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureShare - Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #d3d3d3; /* Fondo gris claro */
        }
        .container {
            background-color: #ff8c00; /* Naranja más oscuro */
            padding: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 10px; /* Bordes redondeados */
        }
        .container h1 {
            color: white;
            margin: 0;
            margin-bottom: 25px;
            font-size: 2em; /* Tamaño de fuente aumentado en un 25% */
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: white;
            margin-bottom: 5px;
            font-size: 1.25em; /* Tamaño de fuente aumentado en un 25% */
        }
        .form-group input {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 1.25em; /* Tamaño de fuente aumentado en un 25% */
            border: none;
            border-radius: 5px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            flex-direction: column;
            margin-top: 25px;
        }
.button-container {
            display: flex;
            justify-content: center;
            flex-direction: column;
            margin-top: 25px;
        }
        .button-container button {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 12.5px 25px; /* Padding aumentado en un 25% */
            margin: 10px 0; /* Margen aumentado en un 25% */
            border-radius: 5px;
            font-size: 1.25em; /* Tamaño de fuente aumentado en un 25% */
            border: none;
            cursor: pointer;
        }
        .button-container button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            font-size: 1.25em;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registro en SecureShare</h1>
<?php
// Datos conexión BD
$servername = "localhost";
$username = "root";
$password = "Proyecto1.";
$dbname = "db_users";

// Conectar a la BD
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
</head>
<body>

  <form action="registro_dep.php" method="post">
    <div class="form-group">
      <label for="username">Usuario</label>
      <input type="text" id="username" name="username" required>
    </div>

    <div class="form-group">
      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
      <label for="departamento">Departamento</label>
      <select name="id_departamento" required>
        <?php
        $resultado = $conn->query("SELECT id, nombre FROM departamentos");
        while ($row = $resultado->fetch_assoc()) {
          echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
        }
        ?>
      </select>
    </div>

    <div class="button-container">
      <button type="submit">Registrarse</button>
      <button type="button" onclick="window.location.href='index.html'">Volver al Inicio</button>
    </div>
  </form>

<?php $conn->close(); ?>
</body>
</html>
