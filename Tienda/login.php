<?php
session_start();
require_once 'db_config.php';

$error = '';

// Si el usuario ya está logueado, redirigir a la tienda
if (isset($_SESSION['usuario_id'])) {
    header('Location: tienda.php');
    exit;
}

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validaciones
    if (empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios";
    } else {
        // Buscar usuario por email
        $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();
            
            // Verificar contraseña
            if (password_verify($password, $usuario['password'])) {
                // Iniciar sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                
                // Redirigir a la tienda
                header('Location: tienda.php');
                exit;
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "No existe una cuenta con este email";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Tienda Pokémon</title>
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
                    <li><a href="login.php" class="active"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
                    <li><a href="registro.php"><i class="fas fa-user-plus"></i> Registrarse</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="form-container">
        <div class="auth-form">
            <h2>Iniciar Sesión</h2>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Iniciar Sesión</button>
            </form>
            
            <div class="auth-links">
                <p>¿No tienes una cuenta? <a href="registro.php">Regístrate</a></p>
            </div>
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
</body>
</html>
