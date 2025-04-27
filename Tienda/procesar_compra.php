<?php
session_start();
require_once 'db_config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se envió el formulario correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    
    // Iniciar transacción
    $conn->begin_transaction();
    
    try {
        // Obtener los productos en el carrito del usuario
        $sql = "SELECT c.id, c.cantidad, p.id as producto_id, p.precio, p.stock 
                FROM carrito c 
                JOIN productos p ON c.producto_id = p.id 
                WHERE c.usuario_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while($item = $result->fetch_assoc()) {
                // Verificar stock disponible
                if ($item['stock'] < $item['cantidad']) {
                    throw new Exception("Stock insuficiente para uno o más productos.");
                }
                
                // Registrar la compra
                $total_item = $item['precio'] * $item['cantidad'];
                $sql_compra = "INSERT INTO compras (usuario_id, producto_id, cantidad, total) 
                              VALUES (?, ?, ?, ?)";
                
                $stmt_compra = $conn->prepare($sql_compra);
                $stmt_compra->bind_param("iiid", $usuario_id, $item['producto_id'], $item['cantidad'], $total_item);
                $stmt_compra->execute();
                
                // Actualizar stock
                $nuevo_stock = $item['stock'] - $item['cantidad'];
                $sql_stock = "UPDATE productos SET stock = ? WHERE id = ?";
                
                $stmt_stock = $conn->prepare($sql_stock);
                $stmt_stock->bind_param("ii", $nuevo_stock, $item['producto_id']);
                $stmt_stock->execute();
            }
            
            // Vaciar el carrito del usuario
            $sql_vaciar = "DELETE FROM carrito WHERE usuario_id = ?";
            $stmt_vaciar = $conn->prepare($sql_vaciar);
            $stmt_vaciar->bind_param("i", $usuario_id);
            $stmt_vaciar->execute();
            
            // Confirmar transacción
            $conn->commit();
            
            // Redirigir a página de confirmación
            $_SESSION['compra_exitosa'] = true;
            header('Location: confirmacion_compra.php');
            exit;
        } else {
            throw new Exception("No hay productos en el carrito.");
        }
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $conn->rollback();
        
        $_SESSION['error_compra'] = $e->getMessage();
        header('Location: carrito.php');
        exit;
    }
} else {
    // Si no se envió el formulario correctamente, redirigir al carrito
    header('Location: carrito.php');
    exit;
}
?>
