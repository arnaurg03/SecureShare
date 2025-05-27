<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureShare - Análisis</title>
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
        .box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 50%;
            margin-top: 20px;
        }
        .upload-container {
            border: 2px dashed #ff8c00;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .upload-button {
            background-color: #ff8c00;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 1.1em;
        }
        .upload-button:hover {
            background-color: #e07b00;
        }
        input[type="file"] {
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            background-color: #fff5e6;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div href="inicio.php" class="header">SecureShare</div>
    <div class="nav-bar">
        <a href="analisis.php">Analizar archivo</a>
        <a href="perfil.php">Mi perfil</a>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
	<a href="administrador.php">Administrador</a>

    </div>
    <div class="content">
        <h1>Analizar Archivos</h1>
        <div class="box">
            <h2>Subir Archivo</h2>
            <div class="upload-container">
                <form action="procesar_analisis.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="archivo" required>
                    <br><br>
                    <button type="submit" class="upload-button">Analizar Archivo</button>
                </form>
            </div>
        </div>
        <div class="box" id="resultado">
            <h2>Resultado del Análisis</h2>
            <?php
		if (isset($_SESSION["resultado_analisis"])) {
    		echo "<p><strong>Resultados:</strong></p>";
    		echo "<p>" . $_SESSION["resultado_analisis"] . "</p>";
    		unset($_SESSION["resultado_analisis"]); // Limpiar el resultado después de mostrarlo
		}
		?>

        </div>
    </div>
</body>
</html>
