<?php
session_start();
require_once 'db_config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener los productos en el carrito del usuario
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT c.id, c.cantidad, p.id as producto_id, p.nombre, p.precio, p.imagen_url 
        FROM carrito c 
        JOIN productos p ON c.producto_id = p.id 
        WHERE c.usuario_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$items_carrito = [];
$total = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $items_carrito[] = $row;
        $total += $row['precio'] * $row['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito - Tienda Pokémon</title>
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
                    <li><a href="carrito.php" class="active"><i class="fas fa-shopping-cart"></i> Carrito</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión (<?php echo $_SESSION['usuario_nombre']; ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="cart-container">
        <h2 class="section-title">Mi Carrito de Compras</h2>
        
        <?php if (empty($items_carrito)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart fa-4x"></i>
                <p>Tu carrito está vacío</p>
                <a href="tienda.php" class="btn-primary">Ver productos</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach($items_carrito as $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="<?php echo $item['imagen_url']; ?>" alt="<?php echo $item['nombre']; ?>">
                        </div>
                        <div class="item-details">
                            <h3><?php echo $item['nombre']; ?></h3>
                            <p class="item-price">€<?php echo number_format($item['precio'], 2); ?></p>
                            <div class="item-quantity">
                                <span>Cantidad: <?php echo $item['cantidad']; ?></span>
                                <form action="eliminar_carrito.php" method="post" class="delete-form">
                                    <input type="hidden" name="carrito_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="item-total">
                            <p>€<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>€<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Envío:</span>
                    <span>€5.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>€<?php echo number_format($total + 5, 2); ?></span>
                </div>
                
                <form action="procesar_compra.php" method="post">
                    <button type="submit" class="btn-checkout">
                        <i class="fas fa-credit-card"></i> Proceder al pago
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <footer class="main-footer">
        <div class="footer-container">
            <p>&copy; 2023 Tienda Pokémon. Todos los derechos reservados.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
