<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <link rel="icon" type="image/png" href="vista/BA.png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Principal - BAIS Inventory</title>
  <style>
    body {
        display: flex;
     justify-content: center;
     align-items: center;
     height: 100vh;
     margin: 0;
     background-color: #f1f5f9;
     font-family: Arial, sans-serif;
    }

    .container {
      max-width: 800px;
      margin: 50px auto;
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      color: #333;
    }

    .rol {
      text-align: center;
      margin-top: 10px;
      font-weight: bold;
      color: #555;
    }

    .opciones {
      margin-top: 40px;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .opcion {
      background: #e2e8f0;
      padding: 20px;
      border-radius: 10px;
      width: 200px;
      text-align: center;
      font-weight: bold;
      text-decoration: none;
      color: #1e293b;
      box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
    }

    .opcion:hover {
      background: #cbd5e1;
    }

    .logout {
      margin-top: 30px;
      text-align: center;
    }

    .logout a {
      text-decoration: none;
      color: red;
      font-weight: bold;
    }

    .logo-container {
    position: absolute;
    top: 15px;
    left: 15px;
  }

  .logo {
    height: 60px; 
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
 
  <div class="container">
    <h1>Bienvenido a BAIS Inventory</h1>
    <p class="rol">Tu rol: <?php echo htmlspecialchars($rol); ?></p>

    <div class="opciones">
      <?php if ($rol === "gerente"): ?>
        <a href="repuestos.php" class="opcion">Gestión de Repuestos</a>
        <a href="ensambles.php" class="opcion">Gestión de Ensambles</a>
        <a href="exportaciones.php" class="opcion">Gestión de Exportaciones</a>
        <a href="reportes.php" class="opcion">Ver Reportes</a>
        <a href="admin_usuarios.php" class="opcion">Administrar Usuarios</a>
      <?php else: ?>
        <a href="repuestos.php" class="opcion">Ver Repuestos</a>
        <a href="ensambles.php" class="opcion">Ver Ensambles</a>
        <a href="exportaciones.php" class="opcion">Ver Exportaciones</a>
        <a href="reportes.php" class="opcion">Ver Reportes</a>
      <?php endif; ?>
    </div>

    <div class="logout">
      <a href="logout.php">Cerrar sesión</a>
    </div>
     </div>
       
</body>
</html>