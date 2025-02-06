<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}

$directory = '/var/www/proyecto/uploads/';

function getFilesInDirectory($dir) {
    $files = [];
    if (is_dir($dir)) {
        $dirContents = scandir($dir);
        foreach ($dirContents as $file) {
            if ($file != '.' && $file != '..') {
                $files[] = $file;
            }
        }
    }
    return $files;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $fileToDelete = $directory . basename($_POST['file']);
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $newPassword = $_POST['new_password'];
    $username = $_SESSION['username'];

    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "Proyecto1.";
    $dbname = "db_users";

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET password='$newPasswordHashed' WHERE username='$username'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Contraseña actualizada correctamente.');</script>";
    } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

$fileList = getFilesInDirectory($directory);
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
        .file-list, .reset-password-form {
            margin: 20px auto;
            padding: 10px;
            background-color: #ffffff;
            border: 2px solid #ff8c00;
            border-radius: 10px;
            display: block;
            width: 80%;
            max-width: 600px;
        }
        .file-list ul {
            list-style-type: none;
            padding: 0;
            text-align: left;
        }
        .file-list ul li {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-list form {
            display: inline;
        }
        .file-list button {
            background-color: #ff8c00;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 5px;
        }
        .file-list button:hover {
            background-color: #ff7a00;
        }
        .reset-password-form h2 {
            margin-bottom: 10px;
        }
        .reset-password-form input {
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        .reset-password-form button {
            background-color: #ff8c00;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            width: 100%;
 }
        .reset-password-form button:hover {
            background-color: #ff7a00;
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
    </div>
    <div class="content">
        <h1>Archivos en la carpeta 'uploads/'</h1>
        <div class="file-list">
            <ul>
                <?php
                foreach ($fileList as $file) {
                    echo '<li>' . htmlspecialchars($file) .
                         '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">                         '<input type="hidden" name="file" value="' . htmlspecialchars($file) . '">' .
                         '<button type="submit" name="delete">Borrar</button>' .
                         '</form>' .
                         '<form action="descargar.php" method="post" style="display:inline;">' .
                         '<input type="hidden" name="file" value="' . htmlspecialchars($file) . '">' .
                         '<button type="submit">Descargar</button>' .
                         '</form>' .
                         '</li>';
                }
                ?>
            </ul>
        </div>

        <div class="reset-password-form">
            <h2>Restablecer contraseña</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="password" name="new_password" placeholder="Nueva contraseña" required>
                <button type="submit" name="reset_password">Restablecer</button>
            </form>
        </div>
    </div>
</body>
</html>
