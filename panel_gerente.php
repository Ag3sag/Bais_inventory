<?php
session_start();
if ($_SESSION['rol'] != 'gerente') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Panel del Gerente</title>
</head>
<body>
  <h1>Bienvenido Gerente, <?php echo $_SESSION['clave_acceso']; ?></h1>
  <ul>
    <li><a href="logout.php">Cerrar sesión</a></li>
  </ul>
</body>
</html>