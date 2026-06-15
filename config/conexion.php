<?php
$link = mysqli_connect("localhost", "root", "");
if (!$link) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_select_db($link, "entornos_graficos");
?>