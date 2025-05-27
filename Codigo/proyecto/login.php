<?php
        session_start();

        $servername = "localhost";
        $username = "root";
        $password = "Proyecto1.";
        $dbname = "db_users";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user = $_POST["username"];
            $pass = $_POST["password"];

            $stmt = $conn->prepare("SELECT password, rol, departamento FROM usuarios WHERE username = ?");
            if ($stmt === false) {
                die("Error al preparar la declaración: " . $conn->error);
            }

            $stmt->bind_param("s", $user);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($hashed_password, $rol, $departamento);

            if ($stmt->num_rows > 0) {
                $stmt->fetch();
                if (password_verify($pass, $hashed_password)) {
                    $_SESSION['usuario'] = $user; // ✅ corregido
                    $_SESSION['rol'] = $rol;
                    $_SESSION['departamento'] = $departamento;

                    if ($rol === 'admin') {
                        header("Location: administrador.php");
                    } else {
                        header("Location: inicio.php");
                    }
                    exit;
                } else {
                    echo "<script>alert('Contraseña incorrecta.');</script>";
                }
            } else {
                echo "<script>alert('Usuario no encontrado.');</script>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>
  <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureShare - Inicio de Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #d3d3d3;
        }
        .container {
            background-color: #ff8c00;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 10px;
        }
        .container h1 {
            color: white;
            margin-bottom: 25px;
            font-size: 2em;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: white;
            margin-bottom: 5px;
            font-size: 1.25em;
        }
        .form-group input {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 1.25em;
            border: none;
            border-radius: 5px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            flex-direction: column;
            margin-top: 25px;
        }
        .button-container button {
            color: white;
            background-color: #007bff;
            padding: 12.5px 25px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 1.25em;
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
        <h1>Inicio de Sesión en SecureShare</h1>
        
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="button-container">
                <button type="submit">Iniciar Sesión</button>
                <button type="button" onclick="window.location.href='index.html'">Volver al Inicio</button>
            </div>
        </form>
    </div>
</body>
</html>
