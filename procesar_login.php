<?php
include 'conexion.php'; 


$clave = $_POST['clave_acceso'];
$contrasena = $_POST['contrasena'];


$sql = "SELECT * FROM usuario WHERE clave_acceso = '$clave' AND contraseña = '$contrasena'";
$resultado = mysqli_query($conexion, $sql);


if (mysqli_num_rows($resultado) === 1) {
    $usuario = mysqli_fetch_assoc($resultado);
    
    
    session_start();
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['rol'] = $usuario['rol'];  

    
    header("Location: panel.php");
    exit();
} else {
    
    header("Location: login.php?error=Datos incorrectos");
    exit();
}
?>