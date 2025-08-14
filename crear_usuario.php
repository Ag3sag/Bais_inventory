<?php
session_start();


if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'gerente' && $_SESSION['rol'] != 'admin')) {
    header("Location: acceso_denegado.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear Usuario</title>
</head>
<body>
    <h2>Crear Nuevo Usuario</h2>
    
    <form action="guardar_usuario.php" method="POST">
        <label>Clave de acceso:</label><br>
        <input type="text" name="clave_acceso" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="contrasena" required><br><br>

        <label>Rol:</label><br>
        <select name="rol" required>
            <option value="trabajador">Trabajador</option>
            <option value="gerente">Gerente</option>
        </select><br><br>

        <button type="submit">Guardar Usuario</button>
    </form>
    
    <br>
    <a href="panel_gerente.php">← Volver al Panel</a>
</body>
</html>