<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";


if (isset($_POST['eliminar_exportacion']) && $_SESSION['rol'] === 'gerente') {
  $id_exportacion = $_POST['eliminar_exportacion'];
  mysqli_query($conexion, "DELETE FROM exportacion_ensamble WHERE id_exportacion = $id_exportacion");
  mysqli_query($conexion, "DELETE FROM exportacion WHERE id_exportacion = $id_exportacion");
  $_SESSION['mensaje'] = "Exportación eliminada correctamente";
  header("Location: exportaciones.php");
  exit();
}


if (isset($_POST['crear']) && $_SESSION['rol'] === 'gerente') {
  $ubicacion = $_POST['ubicacion'];
  $destino = $_POST['destino'];
  $id_usuario = $_SESSION['id_usuario'];
  $ensambles_seleccionados = isset($_POST['ensambles']) ? $_POST['ensambles'] : [];

  mysqli_query($conexion, "INSERT INTO exportacion (ubicacion, destino, id_usuario_responsable) VALUES ('$ubicacion', '$destino', $id_usuario)");
  $id_exportacion = mysqli_insert_id($conexion);

  foreach ($ensambles_seleccionados as $id_ensamble) {
    mysqli_query($conexion, "INSERT INTO exportacion_ensamble (id_exportacion, id_ensamble) VALUES ($id_exportacion, $id_ensamble)");
  }

  $_SESSION['mensaje'] = "Exportación creada correctamente";
  header("Location: exportaciones.php");
  exit();
}

$ensambles = mysqli_query($conexion, "SELECT * FROM ensamble");
$exportaciones = mysqli_query($conexion, "SELECT * FROM exportacion");

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
  <title>Gestión de Exportaciones</title>
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

    input[type="text"],
     select,
     button {
     width: 100%;
     padding: 10px;
     margin-bottom: 15px;
     border-radius: 8px;
     border: 1px solid #ccc;
     box-sizing: border-box;
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
      background-color: #3b82f6;
    }

    .mensaje {
      background: #d1fae5;
      color: #3b82f6;
      text-align: center;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 8px;
    }

    .aviso {
      background: #fef3c7;
      color: #92400eff;
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

<h1>Gestión de Exportaciones</h1>

<?php if ($mensaje): ?>
  <div class="mensaje" id="mensaje"><?php echo $mensaje; ?></div>
  <script>
    setTimeout(() => {
      const mensaje = document.getElementById("mensaje");
      if (mensaje) mensaje.style.display = "none";
    }, 3000);
  </script>
<?php endif; ?>

<?php if ($_SESSION['rol'] === 'gerente'): ?>
  <form method="POST">
    <select name="ubicacion" required>
      <option value="">Selecciona ubicación</option>
      <option value="A">A</option>
      <option value="B">B</option>
      <option value="C">C</option>
      <option value="D">D</option>
    </select>

    <input type="text" name="destino" placeholder="Destino de la exportación" required>

    <div class="checkbox-group">
      <?php while($e = mysqli_fetch_assoc($ensambles)): ?>
        <label>
          <input type="checkbox" name="ensambles[]" value="<?php echo $e['id_ensamble']; ?>">
          Ensamble #<?php echo $e['id_ensamble']; ?> ( <?php echo $e['nombre']; ?>) (Ubic: <?php echo $e['ubicacion']; ?>)
        </label>
      <?php endwhile; ?>
    </div>
    
    <div>
     <button type="submit" name="crear">Crear Exportación</button>
    </div>
  
  </form>
<?php else: ?>
  <div class="aviso">Solo los gerentes pueden crear exportaciones.</div>
<?php endif; ?>

<table>
  <tr>
    <th>ID Exportación</th>
    <th>Ubicación</th>
    <th>Destino</th>
    <th>ID Responsable</th>
    <th>Acciones</th>
  </tr>

  <?php mysqli_data_seek($exportaciones, 0); ?>
  <?php while($ex = mysqli_fetch_assoc($exportaciones)): ?>
    <tr>
      <td><?php echo $ex['id_exportacion']; ?></td>
      <td><?php echo $ex['ubicacion']; ?></td>
      <td><?php echo $ex['destino']; ?></td>
      <td><?php echo $ex['id_usuario_responsable']; ?></td>
      <td>
        <?php if ($_SESSION['rol'] === 'gerente'): ?>
          <form method="POST" style="display:inline;padding:15px 6px;">
            <input type="hidden" name="eliminar_exportacion" value="<?php echo $ex['id_exportacion']; ?>">
            <button type="submit" style="line-height: 1;display: inline-block;max-width: 150px;margin-left: 2px; margin-right: 2px;" onclick="return confirm('¿Estás seguro de eliminar este ensamble?')">Eliminar</button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<div style="text-align:center; margin-top: 20px;">
  <a href="panel.php">
    <button style="
      background-color: #3b82f6;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      max-width: 150px;
    ">
      Volver al inicio
    </button>
  </a>
</div>

</body>
</html>