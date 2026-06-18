<?php
$link = mysqli_connect("localhost", "root", "", "entornos_graficos");
if (!$link) {
    die("Error de conexión: " . mysqli_connect_error());
}

define('BASE_URL', 'http://localhost/EC/EG-TP');
?>