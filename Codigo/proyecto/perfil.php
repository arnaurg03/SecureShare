<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$directory = __DIR__ . "/uploads/" . $usuario;

if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

$fileList = array_diff(scandir($directory), array('..', '.'));

// Obtener el departamento del usuario actual
$conn = new mysqli("localhost", "root", "Proyecto1.", "db_users");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT departamento FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();
$departamento = '';
if ($row = $result->fetch_assoc()) {
    $departamento = $row['departamento'];
}

// Obtener otros usuarios del mismo departamento
$stmt2 = $conn->prepare("SELECT username FROM usuarios WHERE departamento = ? AND username != ?");
$stmt2->bind_param("ss", $departamento, $usuario);
$stmt2->execute();
$usuariosMismoDepartamento = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$stmt2->close();
$conn->close();

// Transferencia de archivos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transferir_archivo'])) {
    $archivo = basename($_POST['archivo_a_transferir']);
    $destinatario = $_POST['usuario_destino'];
    $origen = $directory . '/' . $archivo;
    $destino = __DIR__ . "/uploads/" . $destinatario . '/' . $archivo;

    if (file_exists($origen)) {
        if (!file_exists(dirname($destino))) {
            mkdir(dirname($destino), 0755, true);
        }
        if (copy($origen, $destino)) {
            echo "<script>alert('Archivo transferido correctamente a $destinatario.');</script>";
        } else {
            echo "<script>alert('Error al transferir el archivo.');</script>";
        }
    } else {
        echo "<script>alert('El archivo no existe.');</script>";
    }
}

// Eliminar archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_archivo'])) {
    $archivo = basename($_POST['archivo_eliminar']);
    $ruta = $directory . '/' . $archivo;

    if (file_exists($ruta)) {
        unlink($ruta);
        echo "<script>alert('Archivo eliminado correctamente.'); window.location.href='perfil.php';</script>";
        exit();
    } else {
        echo "<script>alert('El archivo no existe.');</script>";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $nueva_contrasena = $_POST['nueva_contrasena'];

    if (strlen($nueva_contrasena) < 6) {
        echo "<script>alert('La contraseña debe tener al menos 6 caracteres.');</script>";
    } else {
        $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $conn = new mysqli("localhost", "root", "Proyecto1.", "db_users");

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hash, $usuario);

        if ($stmt->execute()) {
            echo "<script>alert('Contraseña actualizada correctamente.');</script>";
        } else {
            echo "<script>alert('Error al actualizar la contraseña.');</script>";
        }

        $stmt->close();
        $conn->close();
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureShare - Mi Perfil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #d3d3d3;
        }
        .header {
            background-color: #ff8c00;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 2.5em;
        }
        .nav-bar {
            background-color: #ff8c00;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .nav-bar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.25em;
        }
        .nav-bar a:hover {
            text-decoration: underline;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        main {
            padding: 20px;
            text-align: center;
        }
        .file-list, .reset-password, .transfer-form {
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border: 2px solid #ff8c00;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            text-align: left;
        }
        .file-list ul {
            list-style-type: none;
            padding: 0;
        }
        .file-list li {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-list li:last-child {
            border-bottom: none;
        }
        .file-actions form {
            display: inline;
            margin-left: 10px;
        }
        .file-actions button {
            background-color: #ff8c00;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .file-actions button:hover {
            background-color: #ff7a00;
        }
        .transfer-form h2 {
            margin-bottom: 10px;
        }
        .transfer-form select,
        .transfer-form button {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .transfer-form button {
            background-color: #ff8c00;
            color: white;
            border: none;
            cursor: pointer;
        }
        .transfer-form button:hover {
            background-color: #ff7a00;
        }
        .reset-password {
            margin-top: 30px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        SecureShare
    </div>
    <div class="nav-bar">
        <a href="analisis.php">Analizar archivo</a>
        <a href="perfil.php">Mi perfil</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
        <a href="administrador.php">Administrador</a>
    </div>
    <div class="content">
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?>!</h1>
    </div>

    <div class="file-list">
        <h2>Tus archivos</h2>
        <ul>
            <?php foreach ($fileList as $file): ?>
                <li>
                    <?php echo htmlspecialchars($file); ?>
                    <div class="file-actions">
                        <form method="get" action="descargar.php" style="display: inline;">
    				<input type="hidden" name="file" value="<?php echo htmlspecialchars($file); ?>">
    				<button type="submit">Descargar</button>
			</form>

                        <form method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este archivo?');">
                            <input type="hidden" name="archivo_eliminar" value="<?php echo htmlspecialchars($file); ?>">
                            <button type="submit" name="eliminar_archivo">Eliminar</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="transfer-form">
        <h2>Transferir archivo a otro usuario</h2>
        <form method="post">
            <label for="archivo_a_transferir">Selecciona un archivo:</label>
            <select name="archivo_a_transferir" required>
                <?php foreach ($fileList as $file): ?>
                    <option value="<?php echo htmlspecialchars($file); ?>"><?php echo htmlspecialchars($file); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="usuario_destino">Selecciona un usuario del mismo departamento:</label>
            <select name="usuario_destino" required>
                <?php foreach ($usuariosMismoDepartamento as $usuarioDestino): ?>
                    <option value="<?php echo htmlspecialchars($usuarioDestino['username']); ?>">
                        <?php echo htmlspecialchars($usuarioDestino['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="transferir_archivo">Transferir</button>
        </form>
    </div>

    <div class="reset-password">
        <h2>Restablecer contraseña</h2>
        <form method="post">
            <label for="nueva_contrasena">Nueva contraseña:</label>
            <input type="password" name="nueva_contrasena" id="nueva_contrasena" required>
            <button type="submit" name="reset_password">Restablecer contraseña</button>
        </form>
    </div>
</body>
</html>
