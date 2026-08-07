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
$modoDebug = defined('APP_DEBUG') && APP_DEBUG;
error_reporting(E_ALL);
ini_set('display_errors', $modoDebug ? '1' : '0');

function paginaErrorGeneral(string $titulo, string $detalle): void
{
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=UTF-8');
    }
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"/>'
        . '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>'
        . '<title>' . htmlspecialchars($titulo) . ' | VuelaLibre</title>'
        . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>'
        . '</head><body class="bg-light">'
        . '<main class="container py-5"><div class="row justify-content-center"><div class="col-md-8 col-lg-6">'
        . '<div class="card border-0 shadow-sm p-4 p-md-5 text-center">'
        . '<h1 class="h4 fw-bold mb-3">' . htmlspecialchars($titulo) . '</h1>'
        . '<p class="text-secondary mb-4">' . htmlspecialchars($detalle) . '</p>'
        . '<a href="/" class="btn btn-primary">Volver al inicio</a>'
        . '</div></div></div></main></body></html>';
}

set_exception_handler(function ($e) use ($modoDebug) {
    error_log('Error no controlado: ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) {
        http_response_code(500);
    }
    if ($modoDebug) {
        echo '<pre>' . htmlspecialchars((string) $e) . '</pre>';
        return;
    }
    paginaErrorGeneral(
        'Ocurrio un error inesperado',
        'No pudimos completar la operacion. Volve a intentarlo en unos minutos.'
    );
});

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
    error_log('Error de conexion a la base de datos: ' . mysqli_connect_error());
    if (!headers_sent()) {
        http_response_code(503);
    }
    paginaErrorGeneral(
        'El servicio no esta disponible',
        'No pudimos conectarnos a la base de datos. Volve a intentarlo en unos minutos.'
    );
    exit;
}
?>