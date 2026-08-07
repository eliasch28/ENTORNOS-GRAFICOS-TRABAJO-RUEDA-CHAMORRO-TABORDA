<?php

// Plantilla de configuracion del entorno.
// Copiar este archivo como config/config.php y completar con los datos reales.
// config/config.php esta en .gitignore: no se sube al repositorio.

// Conexion a la base de datos.
define('DB_HOST', 'localhost');
define('DB_USER', 'usuario');
define('DB_PASS', 'contrasena');
define('DB_NAME', 'nombre_de_la_base');

// URL desde la que se sirve el sitio, sin barra final.
// Se usa para armar los enlaces de los correos.
define('BASE_URL', 'https://midominio.com');

// Mostrar el detalle de los errores en pantalla.
// Definir en true SOLO en desarrollo. En produccion no definir esta constante:
// si no existe, el sistema oculta los errores y muestra una pagina generica.
// define('APP_DEBUG', true);
