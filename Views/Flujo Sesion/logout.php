<?php
/*
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  logout.php — SkyReserva                                    ║
 * ║  UTN · Facultad Regional Rosario · Entornos Gráficos 2026   ║
 * ╠══════════════════════════════════════════════════════════════╣
 * ║  Descripción: Cierre de sesión del usuario autenticado.     ║
 * ║  Solo acepta peticiones POST para evitar cierres            ║
 * ║  accidentales por bots o links externos (seguridad CSRF).   ║
 * ║  Prototipo — lógica de sesión pendiente de integración.     ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

/* ──────────────────────────────────────────────────────────────
   BLOQUE 1: LÓGICA DE CIERRE DE SESIÓN
   TODO: Descomentar en etapa de integración con BD.

   // Solo procesar si la petición es POST
   if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
       header('Location: ../LandPage/LandUsuarioRegistrado.php');
       exit;
   }

   session_start();

   // Destruir todos los datos de sesión
   $_SESSION = [];

   // Eliminar la cookie de sesión del navegador
   if (ini_get('session.use_cookies')) {
       $params = session_get_cookie_params();
       setcookie(
           session_name(), '',
           time() - 42000,
           $params['path'],
           $params['domain'],
           $params['secure'],
           $params['httponly']
       );
   }

   session_destroy();

   // Redirigir al login con mensaje de confirmación
   header('Location: login.php?logout=1');
   exit;
────────────────────────────────────────────────────────────── */


/*
 * COMPORTAMIENTO ACTUAL (sin BD):
 * Al no tener sesiones implementadas todavía, simplemente
 * redirigimos al login. Cuando se integre la BD, este bloque
 * se reemplaza por la lógica de arriba.
 */
header('Location: login.php');
exit;
?>
