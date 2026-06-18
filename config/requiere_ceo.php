<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['codUsuario']) || ($_SESSION['tipoUsuario'] ?? '') !== 'ceo de aerolinea') {
    header('Location: ../FlujoSesion/login.php');
    exit;
}
