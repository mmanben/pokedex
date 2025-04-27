<?php
session_start();
require_once 'db_config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se envió el formulario correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = (int)$_POST['cantidad'];
    
    // Validar que la cantidad sea positiva
    if ($cantidad <= 0) {
        header('Location: tienda.php');
        exit;
    }
    
    // Verificar si el producto ya está en el carrito
    $sql = "SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND producto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $usuario_id, $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Actualizar cantidad
        $item = $result->fetch_assoc();
        $nueva_cantidad = $item['cantidad'] + $cantidad;
        
        $sql = "UPDATE carrito SET cantidad = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $nueva_cantidad, $item['id']);
        $stmt->execute();
    } else {
        // Insertar nuevo item en el carrito
        $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
        $stmt->execute();
    }
    
    // Redirigir al carrito
    header('Location: carrito.php');
    exit;
} else {
    // Si no se envió el formulario correctamente, redirigir a la tienda
    header('Location: tienda.php');
    exit;
}
?>
