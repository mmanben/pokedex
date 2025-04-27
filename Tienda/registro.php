<?php
session_start();
require_once 'db_config.php';

$error = '';
$success = '';

// Si el usuario ya está logueado, redirigir a la tienda
if (isset($_SESSION['usuario_id'])) {
    header('Location: tienda.php');
    exit;
}

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validaciones
    if (empty($nombre) || empty($email) || empty($password)) {
        $error = "Todos los campos son obligatorios";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del email no es válido";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Este email ya está registrado";
        } else {
            // Insertar nuevo usuario
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tienda Pokémon</title>
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
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a></li>
                    <li><a href="registro.php" class="active"><i class="fas fa-user-plus"></i> Registrarse</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="form-container">
        <div class="auth-form">
            <h2>Crear una cuenta</h2>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    <p>Redirigiendo al inicio de sesión...</p>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000);
                    </script>
                </div>
            <?php else: ?>
                <form action="registro.php" method="post">
                    <div class="form-group">
                        <label for="nombre"><i class="fas fa-user"></i> Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-lock"></i> Confirmar contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn-primary">Registrarse</button>
                </form>
                
                <div class="auth-links">
                    <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
                </div>
            <?php endif; ?>
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
