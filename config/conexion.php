<?php
$link = mysqli_connect("localhost", "u963608887_Vuelalibre", "ChRuTa123", "u963608887_vuelalibre");
if (!$link) {
    die("Error de conexión: " . mysqli_connect_error());
}
define('BASE_URL', 'https://khaki-oryx-582108.hostingersite.com');
?>