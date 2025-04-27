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


// Consulta para obtener Pokémon con sus habilidades
$sql = "SELECT p.*, h.ability_name, h.description 
        FROM pokemon p
        LEFT JOIN pokemon_habilidades ph ON p.id = ph.pokemon_id
        LEFT JOIN habilidades h ON ph.habilidad_id = h.id
        WHERE 1=1";

// Añadir filtros...

// Procesar resultados agrupando habilidades por Pokémon
$pokemon = [];
$pokemonIds = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $pokemonId = $fila['id'];
    
    // Si es la primera vez que vemos este Pokémon
    if (!in_array($pokemonId, $pokemonIds)) {
        $pokemonIds[] = $pokemonId;
        $pokemon[$pokemonId] = [
            'id' => $fila['id'],
            'name' => $fila['name'],
            'type1' => $fila['type1'],
            'type2' => $fila['type2'],
            'generation' => $fila['generation'],
            'sprite_url' => $fila['sprite_url'],
            'habilidades' => []
        ];
    }
    
    // Añadir habilidad si existe
    if (!empty($fila['ability_name'])) {
        $pokemon[$pokemonId]['habilidades'][] = [
            'nombre' => $fila['ability_name'],
            'descripcion' => $fila['description']
        ];
    }
}

// Convertir a array indexado para JSON
$resultado_final = array_values($pokemon);


?>
