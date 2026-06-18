<?php
session_start();

// Verificamos si el usuario ya tiene una sesión activa (login exitoso)
if (isset($_SESSION['codUsuario'])) {
    // Si está logueado, lo mandamos directo a su interfaz
    header('Location: Views/LandPage/LandUsuarioRegistrado.php');
    exit;
} else {
    // Si es un visitante casual o no está logueado, lo mandamos a la pública
    header('Location: Views/LandPage/LandUsuarioNoRegistrado.php');
    exit;
}
?>