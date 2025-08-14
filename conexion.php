<?php
$host = "localhost";            
$usuario = "root";              
$contrasena = "root1234";               
$base_datos = "bais_inventory"; 

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("❌ Conexión fallida: " . $conexion->connect_error);
}