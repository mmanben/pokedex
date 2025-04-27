<?php
// db_config.php - Archivo de configuración para la conexión a la base de datos

// Parámetros de conexión a la base de datos
define('DB_HOST', 'localhost');      // Servidor de la base de datos
define('DB_USERNAME', 'martin');       // Nombre de usuario de la base de datos
define('DB_PASSWORD', 'pokedex123');           // Contraseña de la base de datos (vacía por defecto en instalaciones locales)
define('DB_NAME', 'pokedex');        // Nombre de la base de datos

// Crear conexión
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer conjunto de caracteres
$conn->set_charset("utf8");

// Mensaje de éxito (comentado para uso en producción)
// echo "Conexión establecida correctamente";
?>
