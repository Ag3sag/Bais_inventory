<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";

if (isset($_POST['agregar'])) {
    $ubicacion = $_POST['ubicacion'];
    $cantidad = $_POST['cantidad'];
    $nombre = $_POST['nombre'];
    mysqli_query($conexion, "INSERT INTO repuesto (ubicacion, cantidad, Nombre) VALUES ('$ubicacion', $cantidad, '$nombre')");
    $_SESSION['mensaje'] = "Repuesto agregado correctamente";
    header("Location: repuestos.php");
    exit();
}

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM repuesto WHERE id_repuesto = $id");
    $_SESSION['mensaje'] = "Repuesto eliminado correctamente";
    header("Location: repuestos.php");
    exit();
}

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}

if (isset($_GET['filtro']) && $_GET['filtro'] !== "") {
    $ubicacion_filtro = $_GET['filtro'];
    $repuestos = mysqli_query($conexion, "SELECT * FROM repuesto WHERE ubicacion = '$ubicacion_filtro'");
} else {
    $repuestos = mysqli_query($conexion, "SELECT * FROM repuesto");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="icon" type="image/png" href="vista/BA.png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Repuestos</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f1f5f9;
      padding: 40px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    .filtros, .agregar-form {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      justify-content: center;
      flex-wrap: wrap;
    }

    input, select, button {
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    button {
      background: #3b82f6; 
      color: white; 
      border: none; 
      padding: 10px 20px; 
      border-radius: 8px; 
      font-weight: bold; 
      cursor: pointer;
      max-width: 150px;
    }

    button:hover {
      background-color: #2563eb;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #3b82f6;
      color: white;
    }

    a {
      color: #2563eb;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    .logo-container {
      position: absolute;
      top: 15px;
      left: 15px;
      z-index: 1000;
    }

    .logo {
      height: 100px;
      width: auto;
    }

  </style>
</head>
<body>

<div class="logo-container">
  <a href="panel.php">
    <img src="vista/BA.png" alt="Logo BA" class="logo">
  </a>
</div>

<h1>Gestión de Repuestos</h1>

<?php if ($mensaje): ?>
  <div id="mensaje" style="background: #d1fae5; color: #065f46; padding: 10px 15px; margin-bottom: 20px; border-radius: 8px; text-align: center;">
    <?php echo $mensaje; ?>
  </div>
  <script>
    setTimeout(() => {
      const mensaje = document.getElementById("mensaje");
      if (mensaje) {
        mensaje.style.display = "none";
      }
    }, 3000);
  </script>
<?php endif; ?>

<form class="filtros" method="GET">
  <label>Filtrar por ubicación:</label>
  <select name="filtro">
    <option value="">Todas</option>
    <option value="A" <?php if(isset($_GET['filtro']) && $_GET['filtro']=='A') echo 'selected'; ?>>A</option>
    <option value="B" <?php if(isset($_GET['filtro']) && $_GET['filtro']=='B') echo 'selected'; ?>>B</option>
    <option value="C" <?php if(isset($_GET['filtro']) && $_GET['filtro']=='C') echo 'selected'; ?>>C</option>
    <option value="D" <?php if(isset($_GET['filtro']) && $_GET['filtro']=='D') echo 'selected'; ?>>D</option>
  </select>
  <button type="submit">Aplicar</button>
</form>

<form class="agregar-form" method="POST">
  <input type="text" name="nombre" placeholder="Nombre del repuesto" required>
  <select name="ubicacion" required>
    <option value="">Ubicación</option>
    <option value="A">A</option>
    <option value="B">B</option>
    <option value="C">C</option>
    <option value="D">D</option>
  </select>
  <input type="number" name="cantidad" placeholder="Cantidad" required>
  <button type="submit" name="agregar">Agregar</button>
</form>

<table>
  <tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Ubicación</th>
    <th>Cantidad</th>
    <th>Acciones</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($repuestos)): ?>
  <tr>
    <td><?php echo $row['id_repuesto']; ?></td>
    <td><?php echo $row['Nombre']; ?></td>
    <td><?php echo $row['ubicacion']; ?></td>
    <td><?php echo $row['cantidad']; ?></td>
    <td>
      <a href="editar_repuesto.php?id=<?php echo $row['id_repuesto']; ?>">Editar</a> |
      <a href="?eliminar=<?php echo $row['id_repuesto']; ?>" onclick="return confirm('¿Eliminar repuesto?');">Eliminar</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

<div style="text-align: center; margin-top: 20px;">
  <a href="panel.php">
    <button style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">
      Volver al Inicio
    </button>
  </a>
</div>

</body>
</html>