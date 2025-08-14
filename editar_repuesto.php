<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$id = $_GET['id'];
$repuesto = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM repuesto WHERE id_repuesto = $id"));

if (isset($_POST['actualizar'])) {
    $ubicacion = $_POST['ubicacion'];
    $cantidad = $_POST['cantidad'];
    $nombre = $_POST['nombre'];

    mysqli_query($conexion, "UPDATE repuesto SET ubicacion = '$ubicacion', cantidad = $cantidad, Nombre = '$nombre' WHERE id_repuesto = $id");
    header("Location: repuestos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="icon" type="image/png" href="vista/BA.png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Repuesto</title>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: #f1f5f9;
      font-family: Arial;
    }

    .formulario {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 350px;
    }

    input, select, button {
      display: block;
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      background: #3b82f6;
      color: white;
      font-weight: bold;
      cursor: pointer;
      border: none;
    }

    button:hover {
      background: #2563eb;
    }
  </style>
</head>
<body>
  <div class="formulario">
    <h2>Editar Repuesto</h2>
    <form method="POST">
      <input type="text" name="nombre" value="<?php echo $repuesto['Nombre']; ?>" placeholder="Nombre del repuesto" required>

      <select name="ubicacion" required>
        <option value="A" <?php if($repuesto['ubicacion']=='A') echo 'selected'; ?>>A</option>
        <option value="B" <?php if($repuesto['ubicacion']=='B') echo 'selected'; ?>>B</option>
        <option value="C" <?php if($repuesto['ubicacion']=='C') echo 'selected'; ?>>C</option>
        <option value="D" <?php if($repuesto['ubicacion']=='D') echo 'selected'; ?>>D</option>
      </select>

      <input type="number" name="cantidad" value="<?php echo $repuesto['cantidad']; ?>" required>

      <button type="submit" name="actualizar">Actualizar</button>
    </form>
  </div>
</body>
</html>