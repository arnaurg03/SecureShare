<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

// PROCESAR ASIGNACIÓN DE DEPARTAMENTO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['departamento'])) {
    $id = intval($_POST['id']);
    $departamento = $conn->real_escape_string($_POST['departamento']);

    $sqlUpdate = "UPDATE usuarios SET departamento = '$departamento' WHERE id = $id";
    if ($conn->query($sqlUpdate)) {
        header("Location: administrador.php?success=1");
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

// Obtener usuarios
$sql = "SELECT * FROM usuarios";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Administrador - SecureShare</title>
    <style>
        body {
            background-color: #ccc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #ff8800;
            padding: 20px;
            text-align: center;
        }

        .navbar a {
            margin: 0 15px;
            color: white;
            font-weight: bold;
            text-decoration: none;
        }

        h1 {
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background-color: #eee;
            padding: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #999;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin: 20px auto;
            width: 50%;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }

        select, button {
            padding: 5px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <span style="font-size: 30px; font-weight: bold; color: white;">SecureShare</span><br>
    <a href="analisis.php">Analizar archivo</a>
    <a href="perfil.php">Mi perfil</a>
    <a href="cerrar_sesion.php">Cerrar sesión</a>
    <a href="administrador.php">Administrador</a>
</div>

<div class="container">
    <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert-success">
            Departamento asignado correctamente.
        </div>
    <?php endif; ?>

    <h2>Lista de Usuarios Registrados</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Departamento</th>
            <th>Asignar Departamento</th>
        </tr>

        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $fila["id"]; ?></td>
                <td><?php echo htmlspecialchars($fila["username"]); ?></td>
                <td><?php echo htmlspecialchars($fila["rol"]); ?></td>
                <td><?php echo htmlspecialchars($fila["departamento"]); ?></td>
                <td>
                    <form method="post" action="administrador.php">
                        <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                        <select name="departamento" required>
                            <option value="Ciberseguridad">Ciberseguridad</option>
                            <option value="Integración social">Integración social</option>
                            <option value="Soporte">Soporte</option>
                            <option value="Marketing">Marketing</option>
                        </select>
                        <button type="submit">Asignar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
