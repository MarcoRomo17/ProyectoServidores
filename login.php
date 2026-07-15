<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';

// Si ya tiene sesión activa, mandarlo directo a usuarios.php
if (isset($_SESSION['username'])) {
    header("Location: usuarios.php");
    exit();
}

// Lógica de Alertas SweetAlert2 (Igual a la de usuarios.php)
$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;
if (!empty($alert)) {
    $title = isset($alert['title']) ? json_encode($alert['title']) : '"Notificación"';
    $message = isset($alert['message']) ? json_encode($alert['message']) : '""';
    $icon = isset($alert['icon']) ? json_encode($alert['icon']) : '"info"';

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: $title,
                    " . (!empty($alert['message']) ? "text: $message," : "") . "
                    icon: $icon,
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    unset($_SESSION['alert']);
}

// Procesar el formulario cuando se envía
if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password']; // No se escapa porque se compara con password_verify

    $query = "SELECT * FROM usuarios WHERE username = '$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Verificar contraseña encriptada (compatible con password_hash)
        if (password_verify($password, $row['password'])) {
            
            // Revisar si el usuario está activo (estatus = 1)
            if ($row['estatus'] == "1") {
                $_SESSION['username'] = $row['username'];
                
                header("Location: usuarios.php");
                exit();
            } else {
                $_SESSION['alert'] = [
                    'title' => 'ACCESO DENEGADO',
                    'message' => 'Tu usuario se encuentra inactivo.',
                    'icon' => 'warning'
                ];
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = [
                'title' => 'ERROR',
                'message' => 'Contraseña incorrecta.',
                'icon' => 'error'
            ];
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = [
            'title' => 'ERROR',
            'message' => 'El correo electrónico no está registrado.',
            'icon' => 'error'
        ];
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Mi Empresa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-login {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card-header-custom {
            background-color: #1e375c;
            color: white;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="card card-login">
    <div class="card-header card-header-custom">
        <h4 class="m-0"><i class="bi bi-shield-lock-fill me-2"></i>Mi Empresa</h4>
        <small>Control de Acceso</small>
    </div>
    <div class="card-body p-4">
        <form action="login.php" method="POST">
            
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="correo@ejemplo.com" autocomplete="off" required>
                <label for="email">Correo Electrónico</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" autocomplete="off" required>
                <label for="password">Contraseña</label>
            </div>

            <button type="submit" name="login_btn" class="btn btn-primary w-100 py-2" style="background-color: #1e375c; border: none;">
                Ingresar
            </button>
            
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>

</body>
</html>