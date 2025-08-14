<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'trabajador') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel del Trabajador</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f3f4f6;
      margin: 0;
      padding: 40px;
    }
    .container {
      background: white;
      max-width: 600px;
      margin: auto;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    h1 {
      color: #111827;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background: #3b82f6;
      color: white;
      border-radius: 10px;
      text-decoration: none;
    }
    a:hover {
      background: #2563eb;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['clave_acceso']); ?></h1>
    <p>Estás en el panel del trabajador.</p>
    <a href="logout.php">Cerrar sesión</a>
  </div>
</body>
</html>