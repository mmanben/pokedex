<?php
session_start();
require_once 'db_config.php';

// Obtener todos los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
$productos = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Obtener el número de productos en el carrito (si el usuario está logueado)
$num_productos_carrito = 0;
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $sql_carrito = "SELECT SUM(cantidad) as total FROM carrito WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql_carrito);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result_carrito = $stmt->get_result();
    
    if ($result_carrito->num_rows > 0) {
        $row_carrito = $result_carrito->fetch_assoc();
        $num_productos_carrito = $row_carrito['total'] ? $row_carrito['total'] : 0;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Pokémon - Merchandising</title>
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
                <li><a href="../index.html  "><i class="active"></i> Volver a la Web</a></li>

                    <li><a href="tienda.php" class="active">Productos</a></li>
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <li>
                            <a href="carrito.php">
                                <i class="fas fa-shopping-cart"></i> Carrito
                                <?php if($num_productos_carrito > 0): ?>
                                    <span class="cart-count"><?php echo $num_productos_carrito; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión (<?php echo $_SESSION['usuario_nombre']; ?>)</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
                        <li><a href="registro.php"><i class="fas fa-user-plus"></i> Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="products-container">
        <h2 class="section-title">Nuestros Productos</h2>
        
        <?php if(isset($_SESSION['mensaje'])): ?>
            <div class="<?php echo $_SESSION['mensaje_tipo']; ?>-message">
                <i class="fas <?php echo $_SESSION['mensaje_tipo'] == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <?php echo $_SESSION['mensaje']; ?>
            </div>
            <?php 
                // Limpiar el mensaje después de mostrarlo
                unset($_SESSION['mensaje']);
                unset($_SESSION['mensaje_tipo']);
            ?>
        <?php endif; ?>
        
        <div class="products-grid">
            <?php foreach($productos as $producto): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $producto['imagen_url']; ?>" alt="<?php echo $producto['nombre']; ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo $producto['nombre']; ?></h3>
                        <p class="product-description"><?php echo $producto['descripcion']; ?></p>
                        <p class="product-price">€<?php echo number_format($producto['precio'], 2); ?></p>
                        <?php if ($producto['stock'] > 0): ?>
                            <form action="agregar_carrito.php" method="post">
                                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                                <div class="quantity-selector">
                                    <label for="cantidad_<?php echo $producto['id']; ?>">Cantidad:</label>
                                    <select name="cantidad" id="cantidad_<?php echo $producto['id']; ?>">
                                        <?php for($i = 1; $i <= min(5, $producto['stock']); $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <button type="submit" class="add-to-cart-btn">
                                    <i class="fas fa-cart-plus"></i> Añadir al carrito
                                </button>
                            </form>
                        <?php else: ?>
                            <p class="out-of-stock">Agotado</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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

    <script>
        // Mostrar mensaje de éxito al agregar al carrito
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!<?php echo isset($_SESSION['usuario_id']) ? 'true' : 'false'; ?>) {
                    alert('Debes iniciar sesión para añadir productos al carrito');
                    window.location.href = 'login.php';
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
