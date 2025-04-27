<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$usuario = "martin"; // Usar el usuario creado, no root
$password = "pokedex123";
$base_datos = "pokedex";

// Establecer conexión
$conexion = mysqli_connect($host, $usuario, $password, $base_datos);

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Establecer codificación de caracteres
mysqli_set_charset($conexion, "utf8");

// Obtener parámetros de búsqueda
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$generacion = isset($_POST['generacion']) ? $_POST['generacion'] : '';

// Construir la consulta SQL base
$sql = "SELECT * FROM pokemon WHERE 1=1";

// Añadir filtros si se han especificado
if (!empty($nombre)) {
    $nombre = mysqli_real_escape_string($conexion, $nombre);
    $sql .= " AND name LIKE '%$nombre%'";
}

if (!empty($tipo)) {
    $tipo = mysqli_real_escape_string($conexion, $tipo);
    $sql .= " AND (type1 = '$tipo' OR type2 = '$tipo')";
}

if (!empty($generacion)) {
    $generacion = mysqli_real_escape_string($conexion, $generacion);
    $sql .= " AND generation = $generacion";
}

// Limitar resultados para mejor rendimiento
$sql .= " LIMIT 100";

// Ejecutar consulta
$resultado = mysqli_query($conexion, $sql);

// Verificar si hay resultados
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Crear array para almacenar los resultados
$pokemon = [];

// Recorrer resultados y añadirlos al array
while ($fila = mysqli_fetch_assoc($resultado)) {
    $pokemon[] = $fila;
}

// Cerrar conexión
mysqli_close($conexion);

// Establecer cabecera para JSON
header('Content-Type: application/json');

// Devolver resultados en formato JSON
echo json_encode($pokemon);
