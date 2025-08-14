<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";

if (isset($_POST['crear_usuario'])) {
    $clave = $_POST['clave'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];

    $hashed = md5($contrasena);
    mysqli_query($conexion, "INSERT INTO usuario (clave_acceso, contraseña, rol) VALUES ('$clave', '$hashed', '$rol')");

    $_SESSION['mensaje'] = "✅ Usuario creado correctamente.";
    header("Location: admin_usuarios.php");
    exit();
}

if (isset($_POST['eliminar_usuario'])) {
    $id_usuario = $_POST['eliminar_usuario'];
    mysqli_query($conexion, "DELETE FROM usuario WHERE id_usuario = $id_usuario");
    $_SESSION['mensaje'] = "🗑 Usuario eliminado.";
    header("Location: admin_usuarios.php");
    exit();
}

$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="vista/BA.png">
  <title>Administrar Usuarios</title>
  <style>
    body {
      font-family: Arial;
      background: #f1f5f9;
      padding: 40px;
    }

    .logo-container {
      position: absolute;
      top: 15px;
      left: 15px;
    }

    .logo {
      height: 50px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    .formulario {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 5px #ccc;
    }

    .formulario input,
    .formulario select,
    .formulario button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    table {
      width: 100%;
      margin-top: 30px;
      background: white;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #eee;
      text-align: center;
    }

    th {
      background: #3b82f6;
      color: white;
    }

    .mensaje {
      background: #d1fae5;
      color: #065f46;
      text-align: center;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 8px;
    }

    table form {
      display: inline-block;
      margin: 0;
    }

    table button {
      padding: 6px 12px;
      font-size: 14px;
      background-color: #3b82f6;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    table button:hover {
      background-color: #3b82f6;
    }

    .volver {
      text-align: center;
      margin-top: 30px;
    }

    .volver button {
      background-color: #3b82f6;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      max-width: 150px;
    }
  </style>
</head>
<body>
<div class="logo-container">
  <a href="panel.php">
    <img src="vista/BA.png" alt="Logo" class="logo">
  </a>
</div>

<h1>Administración de Usuarios</h1>

<?php if ($mensaje): ?>
  <div class="mensaje"> <?php echo $mensaje; ?> </div>
<?php endif; ?>

<form method="POST" class="formulario">
  <input type="text" name="clave" placeholder="Clave de acceso" required>
  <input type="password" name="contrasena" placeholder="Contraseña" required>
  <select name="rol" required>
    <option value="">Seleccione rol</option>
    <option value="trabajador">Trabajador</option>
    <option value="gerente">Gerente</option>
  </select>
  <button type="submit" name="crear_usuario">Crear Usuario</button>
</form>

<table>
  <tr>
    <th>ID</th>
    <th>Clave</th>
    <th>Rol</th>
    <th>Acción</th>
  </tr>
  <?php while ($u = mysqli_fetch_assoc($usuarios)): ?>
    <tr>
      <td><?php echo $u['id_usuario']; ?></td>
      <td><?php echo $u['clave_acceso']; ?></td>
      <td><?php echo $u['rol']; ?></td>
      <td>
        <?php if ($_SESSION['id_usuario'] != $u['id_usuario']): ?>
          <form method="POST">
            <input type="hidden" name="eliminar_usuario" value="<?php echo $u['id_usuario']; ?>">
            <button type="submit" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
          </form>
        <?php else: ?>
          -
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<div class="volver">
  <a href="panel.php">
    <button>Volver al inicio</button>
  </a>
</div>
</body>
</html>
