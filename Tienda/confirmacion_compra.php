<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si viene de una compra exitosa
if (!isset($_SESSION['compra_exitosa'])) {
    header('Location: tienda.php');
    exit;
}

// Limpiar la variable de sesión
unset($_SESSION['compra_exitosa']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Confirmada - Tienda Pokémon</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo-container">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/98/International_Pok%C3%A9mon_logo.svg" alt="Logo Pokémon" class="header-logo">
                <h1>Tienda de Merchandising</h1>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="tienda.php">Productos</a></li>
                    <li><a href="carrito.php"><i class="fas fa-shopping-cart"></i> Carrito</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión (<?php echo $_SESSION['usuario_nombre']; ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="confirmation-container">
        <div class="confirmation-message">
            <i class="fas fa-check-circle fa-5x"></i>
            <h2>¡Compra realizada con éxito!</h2>
            <p>Gracias por tu compra. Hemos registrado tu pedido correctamente.</p>
            <p>Recibirás un email con los detalles de tu compra.</p>
