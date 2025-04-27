<?php
// Iniciamos la sesión para poder destruirla
session_start();

// Limpiamos todas las variables de sesión
$_SESSION = array();

// Destruimos la sesión
session_destroy();

// Configuramos el encabezado para redirigir después de mostrar el mensaje
header("refresh:3;url=tienda.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar Sesión - Tienda Pokémon</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="logout-container">
        <div class="logout-message">
            <i class="fas fa-check-circle fa-5x"></i>
            <h2>Sesión cerrada correctamente</h2>
            <p>Serás redirigido a la tienda en unos segundos...</p>
            <div class="loading-bar">
                <div class="loading-progress" id="progress-bar"></div>
            </div>
            <p>Si no eres redirigido automáticamente, <a href="tienda.php">haz clic aquí</a>.</p>
        </div>
    </div>

    <script>
        // Animación de la barra de progreso
        let width = 0;
        const progressBar = document.getElementById('progress-bar');
        const interval = setInterval(() => {
            if (width >= 100) {
                clearInterval(interval);
            } else {
                width += 1;
                progressBar.style.width = width + '%';
            }
        }, 30);
    </script>
</body>
</html>
