<?php
session_start();
if (isset($_SESSION['codUsuario'])) {
    header('Location: Views/LandPage/LandUsuarioRegistrado.php');
    exit;
} else {
    header('Location: Views/LandPage/LandUsuarioNoRegistrado.php');
    exit;
}
?>