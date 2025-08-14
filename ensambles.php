<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";

if (isset($_POST['eliminar_ensamble']) && $_SESSION['rol'] === 'gerente') {
  $id_ensamble = $_POST['eliminar_ensamble'];
  mysqli_query($conexion, "DELETE FROM repuesto_ensamble WHERE id_ensamble = $id_ensamble");
  mysqli_query($conexion, "DELETE FROM ensamble WHERE id_ensamble = $id_ensamble");
  $_SESSION['mensaje'] = "Ensamble eliminado correctamente";
  header("Location: ensambles.php");
  exit();
}

if (isset($_POST['crear']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'gerente') {
    $nombre_ensamble = $_POST['nombre_ensamble'];
    $ubicacion = $_POST['ubicacion'];
    $id_usuario = $_SESSION['id_usuario'];
    $repuestos_seleccionados = isset($_POST['repuestos']) ? $_POST['repuestos'] : [];

    mysqli_query($conexion, "INSERT INTO ensamble (nombre, ubicacion, id_usuario_responsable) VALUES ('$nombre_ensamble', '$ubicacion', $id_usuario)");
    $id_ensamble = mysqli_insert_id($conexion);

    foreach ($repuestos_seleccionados as $id_repuesto) {
        mysqli_query($conexion, "INSERT INTO repuesto_ensamble (id_ensamble, id_repuesto) VALUES ($id_ensamble, $id_repuesto)");
        mysqli_query($conexion, "UPDATE repuesto SET cantidad = cantidad - 1 WHERE id_repuesto = $id_repuesto");
    }

    $_SESSION['mensaje'] = "Ensamble creado correctamente";
    header("Location: ensambles.php");
    exit();
}

$repuestos = mysqli_query($conexion, "SELECT * FROM repuesto WHERE cantidad > 0");
$ensambles = mysqli_query($conexion, "SELECT * FROM ensamble");

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
  <title>Gestión de Ensambles</title>
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
      z-index: 1000;
    }

    .logo {
      height: 50px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    form {
      margin-bottom: 30px;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 5px #ccc;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    select, button, input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .checkbox-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      max-height: 150px;
      overflow-y: auto;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 10px;
    }

    .checkbox-group label {
      display: block;
      width: 48%;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      margin-top: 20px;
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

    button {
      background-color: #3b82f6;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #2563eb;
    }

    .mensaje {
      background: #d1fae5;
      color: #065f46;
      text-align: center;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 8px;
    }

    .aviso {
      background: #fef3c7;
      color: #92400e;
      text-align: center;
      padding: 15px;
      border-radius: 8px;
      font-weight: bold;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<div class="logo-container">
  <a href="panel.php">
    <img src="vista/BA.png" alt="Logo" class="logo">
  </a>
</div>

<h1>Gestión de Ensambles</h1>

<?php if ($mensaje): ?>
  <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>
  <script>
    setTimeout(() => {
      const mensaje = document.getElementById("mensaje");
      if (mensaje) mensaje.style.display = "none";
    }, 3000);
  </script>
<?php endif; ?>

<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'gerente'): ?>
  <form method="POST">
    <input type="text" name="nombre_ensamble" placeholder="Nombre del ensamble" required>

    <select name="ubicacion" required>
      <option value="">Selecciona ubicación</option>
      <option value="A">A</option>
      <option value="B">B</option>
      <option value="C">C</option>
      <option value="D">D</option>
    </select>

    <div class="checkbox-group">
      <?php while($r = mysqli_fetch_assoc($repuestos)): ?>
        <label>
          <input type="checkbox" name="repuestos[]" value="<?php echo $r['id_repuesto']; ?>">
          Repuesto #<?php echo $r['id_repuesto']; ?> (<?php echo $r['Nombre']; ?>) (<?php echo $r['ubicacion']; ?> - Cant: <?php echo $r['cantidad']; ?>)
        </label>
      <?php endwhile; ?>
    </div>

    <button type="submit" name="crear">Crear Ensamble</button>
  </form>
<?php else: ?>
  <div class="aviso">Solo los gerentes pueden crear ensambles.</div>
<?php endif; ?>

<table>
  <tr>
    <th>ID Ensamble</th>
    <th>Nombre</th>
    <th>Ubicación</th>
    <th>ID Responsable</th>
    <th>Acciones</th>
  </tr>

<?php while($e = mysqli_fetch_assoc($ensambles)): ?>
<tr>
  <td><?php echo $e['id_ensamble']; ?></td>
  <td><?php echo $e['nombre']; ?></td>
  <td><?php echo $e['ubicacion']; ?></td>
  <td><?php echo $e['id_usuario_responsable']; ?></td>
  <td>
    <?php if ($_SESSION['rol'] === 'gerente'): ?>
      <form method="POST" style="display:inline;padding:15px 6px;">
        <input type="hidden" name="eliminar_ensamble" value="<?php echo $e['id_ensamble']; ?>">
        <button type="submit" style="line-height: 1;display: inline-block;max-width: 150px;margin-left: 2px; margin-right: 2px;" onclick="return confirm('¿Estás seguro de eliminar este ensamble?')">Eliminar</button>
      </form>
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>
</table>

<div style="text-align: center; margin-top: 20px;">
  <a href="panel.php">
    <button style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;max-width: 150px;">
      Volver al Inicio
    </button>
  </a>
</div>

</body>
</html>