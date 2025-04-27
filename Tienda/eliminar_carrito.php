<?php
session_start();
require_once 'db_config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se envió el formulario correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrito_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $carrito_id = $_POST['carrito_id'];
    
    // Eliminar el item del carrito (verificando que pertenezca al usuario actual)
    $sql = "DELETE FROM carrito WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $carrito_id, $usuario_id);
    $stmt->execute();
    
    // Redirigir al carrito
    header('Location: carrito.php');
    exit;
} else {
    // Si no se envió el formulario correctamente, redirigir al carrito
    header('Location: carrito.php');
    exit;
}
?>
