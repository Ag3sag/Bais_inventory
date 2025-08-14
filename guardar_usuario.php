<?php
include 'conexion.php'; 
session_start();


if (!isset($_SESSION['rol']) || ($_SESSION['rol'] != 'gerente' && $_SESSION['rol'] != 'admin')) {
    die("Acceso denegado.");
}


$clave_acceso = $_POST['clave_acceso'];
$contrasena = $_POST['contrasena'];
$rol = $_POST['rol'];


$hash = password_hash($contrasena, PASSWORD_DEFAULT);


$sql = "INSERT INTO usuario (clave_acceso, contraseña, rol) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    die("Error en la preparación: " . $conexion->error);
}

$stmt->bind_param("sss", $clave_acceso, $hash, $rol);

if ($stmt->execute()) {
    echo "✅ Usuario creado exitosamente. <a href='crear_usuario.php'>Volver</a>";
} else {
    echo "❌ Error al guardar usuario: " . $stmt->error;
}
?>