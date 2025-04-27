<?php
$mysqli = new mysqli("localhost", "root", "", "pokedex");

// Registro de usuario
if ($_POST['action'] === 'register') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $password);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al registrar"]);
    }
}

// Inicio de sesión
if ($_POST['action'] === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo json_encode(["status" => "success", "user_id" => $user['id']]);
        } else {
            echo json_encode(["status" => "error", "message" => "Contraseña incorrecta"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
    }
}

// Compra
if ($_POST['action'] === 'purchase') {
    $usuario_id = $_POST['usuario_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    
    // Obtener precio del producto
    $stmt = $mysqli->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        $total = $producto['precio'] * $cantidad;

        // Registrar compra
        $stmtCompra = $mysqli->prepare("INSERT INTO compras (usuario_id, producto_id, cantidad, total) VALUES (?, ?, ?, ?)");
        $stmtCompra->bind_param("iiid", $usuario_id, $producto_id, $cantidad, $total);
        
        if ($stmtCompra->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al realizar la compra"]);
        }
        
        // Actualizar stock
        $stmtStock = $mysqli->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $stmtStock->bind_param("ii", $cantidad, $producto_id);
        $stmtStock->execute();
        
    } else {
        echo json_encode(["status" => "error", "message" => "Producto no encontrado"]);
    }
}
?>
