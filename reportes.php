<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";


if (isset($_POST['crear_reporte']) && $_SESSION['rol'] === 'gerente') {
    $tipo = $_POST['tipo'];
    $contenido = $_POST['contenido'];
    $id_usuario = $_SESSION['id_usuario'];

    mysqli_query($conexion, "INSERT INTO reporte (tipo, contenido, fecha, id_usuario) VALUES ('$tipo', '$contenido', NOW(), $id_usuario)");
    $_SESSION['mensaje'] = "Reporte creado correctamente.";
    header("Location: reportes.php");
    exit();
}


if (isset($_POST['marcar_visto'])) {
    $id_reporte = $_POST['marcar_visto'];
    mysqli_query($conexion, "UPDATE reporte SET visto = 1, fecha_visto = NOW() WHERE id_reporte = $id_reporte");
    header("Location: reportes.php");
    exit();
}


mysqli_query($conexion, "DELETE FROM reporte WHERE visto = 1 AND TIMESTAMPDIFF(MINUTE, fecha_visto, NOW()) >= 5");

$reportes = mysqli_query($conexion, "
  SELECT r.id_reporte, r.tipo, r.contenido, r.fecha, r.visto, u.id_usuario
  FROM reporte r
  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
  ORDER BY r.fecha DESC
");

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
  <title>Ver Reportes</title>
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

    .formulario {
      margin: 0 auto 30px auto;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 5px #ccc;
      max-width: 600px;
    }

    select, textarea, button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    textarea {
      resize: vertical;
      height: 100px;
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

    .mensaje {
      background: #d1fae5;
      color: #065f46;
      text-align: center;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 8px;
    }

    .volver {
      text-align: center;
      margin-top: 20px;
    }

    .volver button {
      background: #3b82f6; 
      color: white; 
      border: none; 
      padding: 10px 20px; 
      border-radius: 8px; 
      font-weight: bold; 
      cursor: pointer;
      max-width: 150px;
    }

    .volver button:hover {
      background-color: #2563eb;
    }
  </style>
</head>
<body>

<div class="logo-container">
  <a href="panel.php">
    <img src="vista/BA.png" alt="Logo" class="logo">
  </a>
</div>

<h1>Reportes Generados</h1>

<?php if ($mensaje): ?>
  <div class="mensaje"><?php echo $mensaje; ?></div>
<?php endif; ?>

<?php if ($_SESSION['rol'] === 'gerente'): ?>
  <form method="POST" class="formulario">
    <select name="tipo" required>
      <option value="">Selecciona tipo de reporte</option>
      <option value="Repuestos">Repuestos</option>
      <option value="Ensambles">Ensambles</option>
      <option value="Exportaciones">Exportaciones</option>
      <option value="Otro">Otro</option>
    </select>

    <textarea name="contenido" placeholder="Escribe el contenido del reporte..." required></textarea>

    <button type="submit" name="crear_reporte">Crear Reporte</button>
  </form>
<?php endif; ?>

<table>
  <tr>
    <th>ID</th>
    <th>Tipo</th>
    <th>Contenido</th>
    <th>Fecha</th>
    <th>Usuario</th>
    <th>Acción</th>
  </tr>

  <?php while($r = mysqli_fetch_assoc($reportes)): ?>
    <tr>
      <td><?php echo $r['id_reporte']; ?></td>
      <td><?php echo $r['tipo']; ?></td>
      <td><?php echo $r['contenido']; ?></td>
      <td><?php echo $r['fecha']; ?></td>
      <td><?php echo $r['id_usuario']; ?></td>
      <td>
        <?php if (!$r['visto']): ?>
          <form method="POST">
            <input type="hidden" name="marcar_visto" value="<?php echo $r['id_reporte']; ?>">
            <button type="submit">Marcar como visto</button>
          </form>
        <?php else: ?>
          Visto
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
