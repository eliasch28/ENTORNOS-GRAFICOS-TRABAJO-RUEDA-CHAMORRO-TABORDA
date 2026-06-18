<?php
$link = mysqli_connect("localhost", "root", "");
if (!$link) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_select_db($link, "entornos_graficos");

define('BASE_URL', 'http://localhost/EC/EG-TP');
?>