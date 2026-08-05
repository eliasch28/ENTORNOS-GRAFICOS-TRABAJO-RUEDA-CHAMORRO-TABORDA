<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
require_once __DIR__ . '/config.php';

// ─────────────────────────────────────────────────────────────────────
// CONEXIÓN HOSTINGER (WEB FINAL SUBIDA)
//   Usa las credenciales de config.php (DB_HOST / DB_USER / DB_PASS / DB_NAME).
//   Dejá ACTIVA esta línea y COMENTÁ la de LOCAL cuando subas el proyecto.
//   → Se apaga temporalmente el modo de excepciones de mysqli para poder
//     "intentarlo" sin que falle en local cuando no hay acceso remoto.
// ─────────────────────────────────────────────────────────────────────
$link = null;
mysqli_report(MYSQLI_REPORT_OFF);
$link = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ─────────────────────────────────────────────────────────────────────
// CONEXIÓN LOCAL (desarrollo en tu PC con XAMPP / WAMP)
//   Base: entornos_graficos · usuario: root · sin contraseña.
//   Solo se usa si la conexión de Hostinger NO llegó a establecerse.
// ─────────────────────────────────────────────────────────────────────
if (!$link) {
    $link = mysqli_connect('localhost', 'root', '', 'u963608887_vuelalibre');
}

if (!$link) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>