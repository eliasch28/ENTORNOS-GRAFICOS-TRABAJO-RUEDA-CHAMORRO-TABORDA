-- =============================================================================
-- VuelaLibre - Datos de prueba
-- =============================================================================
--
-- Carga un juego de datos completo para que el sistema no se abra vacio:
-- aerolineas, usuarios de los 3 roles, vuelos, promociones, reservas y novedades.
--
-- ANTES DE EJECUTAR
--   1. Correr primero sql/migracion_usuarios.sql. Este script usa las columnas
--      apellidoUsuario y codAerolinea, que no existen en el esquema viejo.
--   2. Es ADITIVO: no borra nada de lo que ya haya cargado.
--
-- CODIGOS
--   Todo lo que se inserta usa codigos desde el 100 en adelante para no chocar
--   con los registros que ya existen (que usan codigos de 1 a 20).
--
-- FECHAS
--   Son relativas a CURDATE(), asi que el juego de datos no se vence: siempre
--   va a haber vuelos futuros, reservas cancelables y novedades vigentes,
--   se ejecute el dia que se ejecute.
--
-- USUARIOS DE PRUEBA (el login es por correo electronico)
--   Administrador     admin.demo@vuelalibre.test        Admin2026!
--   CEO Andes         ceo.andes@vuelalibre.test         Ceo2026!
--   CEO Pampa         ceo.pampa@vuelalibre.test         Ceo2026!
--   CEO Litoral       ceo.litoral@vuelalibre.test       Ceo2026!
--   Usuario           lucia.fernandez@vuelalibre.test   Vuela2026!
--   Usuario           martin.gimenez@vuelalibre.test    Vuela2026!
--   Usuario           carla.rossi@vuelalibre.test       Vuela2026!
--   Usuario           diego.pereyra@vuelalibre.test     Vuela2026!
--   Usuario           sofia.medina@vuelalibre.test      Vuela2026!
--
--   Ademas hay 2 solicitudes de CEO sin aprobar, para que la pantalla
--   "Solicitudes CEO" del administrador no aparezca vacia:
--     ceo.austral@vuelalibre.test   y   ceo.cuyo@vuelalibre.test
--
-- Para empezar de cero, descomentar este bloque:
--   DELETE FROM RESERVAS;
--   DELETE FROM PROMOCIONES;
--   DELETE FROM VUELOS;
--   DELETE FROM NOVEDADES;
--   DELETE FROM USUARIOS WHERE tipoUsuario <> 'administrador';
--   DELETE FROM AEROLINEAS;
-- =============================================================================

SET NAMES utf8mb4;


-- -----------------------------------------------------------------------------
-- AEROLINEAS
-- Codigos IATA nuevos, para no pisar los que ya estan cargados (ARG, LAN, IBE).
-- -----------------------------------------------------------------------------
INSERT INTO AEROLINEAS (codAerolinea, nombreAerolinea, codigoIATA, descripcionAerolinea, codPais) VALUES
(101, 'Andes Airlines',      'AND', 'Aerolinea regional con base en Mendoza, especializada en rutas cordilleranas.', 'AR'),
(102, 'Pampa Air',           'PMP', 'Vuelos de cabotaje de bajo costo dentro de Argentina.',                        'AR'),
(103, 'Litoral Conexion',    'LTC', 'Conecta el noreste argentino con Brasil, Paraguay y Uruguay.',                 'AR'),
(104, 'Patagonia Express',   'PTX', 'Rutas turisticas hacia la Patagonia y Tierra del Fuego.',                      'AR'),
(105, 'Cruz del Sur Airways','CDS', 'Aerolinea uruguaya con vuelos regionales al Cono Sur.',                        'UY');


-- -----------------------------------------------------------------------------
-- USUARIOS
-- Las contrasenas estan en md5, igual que las genera el sistema.
-- verificado = 1 permite iniciar sesion; verificado = 0 deja al CEO pendiente
-- de aprobacion por el administrador.
-- -----------------------------------------------------------------------------
INSERT INTO USUARIOS (codUsuario, nombreUsuario, apellidoUsuario, claveUsuario, tipoUsuario, emailUsuario, telefonoUsuario, verificado, codAerolinea) VALUES
(101, 'Ana',     'Belgrano',  '7da80690c8a437586fe8c8763f44ce92', 'administrador',    'admin.demo@vuelalibre.test',      '+5493411000001', 1, NULL),

(111, 'Ricardo', 'Salinas',   '9b1b17d1efa41f909887b42ee0ef91c5', 'ceo de aerolinea', 'ceo.andes@vuelalibre.test',       '+5492611000011', 1, 101),
(112, 'Valeria', 'Ocampo',    '9b1b17d1efa41f909887b42ee0ef91c5', 'ceo de aerolinea', 'ceo.pampa@vuelalibre.test',       '+5493411000012', 1, 102),
(113, 'Hernan',  'Duarte',    '9b1b17d1efa41f909887b42ee0ef91c5', 'ceo de aerolinea', 'ceo.litoral@vuelalibre.test',     '+5493781000013', 1, 103),
(114, 'Marina',  'Quiroga',   '9b1b17d1efa41f909887b42ee0ef91c5', 'ceo de aerolinea', 'ceo.austral@vuelalibre.test',     '+5492901000014', 0, 104),
(115, 'Julian',  'Ferreyra',  '9b1b17d1efa41f909887b42ee0ef91c5', 'ceo de aerolinea', 'ceo.cuyo@vuelalibre.test',        '+5492611000015', 0, 105),

(121, 'Lucia',   'Fernandez', '75894df357962ec2406c512e90f91c21', 'usuario', 'lucia.fernandez@vuelalibre.test',  '+5493411000021', 1, NULL),
(122, 'Martin',  'Gimenez',   '75894df357962ec2406c512e90f91c21', 'usuario', 'martin.gimenez@vuelalibre.test',   '+5491111000022', 1, NULL),
(123, 'Carla',   'Rossi',     '75894df357962ec2406c512e90f91c21', 'usuario', 'carla.rossi@vuelalibre.test',      '+5493511000023', 1, NULL),
(124, 'Diego',   'Pereyra',   '75894df357962ec2406c512e90f91c21', 'usuario', 'diego.pereyra@vuelalibre.test',    '+5492611000024', 1, NULL),
(125, 'Sofia',   'Medina',    '75894df357962ec2406c512e90f91c21', 'usuario', 'sofia.medina@vuelalibre.test',     '+5493411000025', 1, NULL),
(126, 'Tomas',   'Aguirre',   '75894df357962ec2406c512e90f91c21', 'usuario', 'tomas.aguirre@vuelalibre.test',    '+5493421000026', 1, NULL),
(127, 'Paula',   'Sosa',      '75894df357962ec2406c512e90f91c21', 'usuario', 'paula.sosa@vuelalibre.test',       '+5492231000027', 1, NULL),
(128, 'Bruno',   'Cabrera',   '75894df357962ec2406c512e90f91c21', 'usuario', 'bruno.cabrera@vuelalibre.test',    '+5493881000028', 1, NULL);


-- -----------------------------------------------------------------------------
-- VUELOS
-- Fechas relativas a hoy para que el juego de datos no se venza.
--   201-224  vuelos futuros con asientos, aparecen en la busqueda
--   231-234  vuelos futuros sin asientos, para ver el estado "sin disponibilidad"
--   241-248  vuelos ya realizados, sostienen el historial y los reportes
-- -----------------------------------------------------------------------------
INSERT INTO VUELOS (codVuelo, codAerolinea, origenVuelo, destinoVuelo, fechaSalidaVuelo, horaSalidaVuelo, precioVuelo, asientosDisponibles) VALUES
(201, 101, 'Mendoza',      'Santiago',      DATE_ADD(CURDATE(), INTERVAL  6 DAY), '07:30:00',  185000.00, 42),
(202, 101, 'Mendoza',      'Buenos Aires',  DATE_ADD(CURDATE(), INTERVAL  9 DAY), '14:20:00',  132000.00, 88),
(203, 101, 'Buenos Aires', 'Mendoza',       DATE_ADD(CURDATE(), INTERVAL 12 DAY), '18:45:00',  139500.00, 76),
(204, 101, 'Mendoza',      'Bariloche',     DATE_ADD(CURDATE(), INTERVAL 15 DAY), '09:10:00',  168000.00, 30),
(205, 101, 'Santiago',     'Mendoza',       DATE_ADD(CURDATE(), INTERVAL 21 DAY), '16:00:00',  178000.00, 55),

(206, 102, 'Buenos Aires', 'Cordoba',       DATE_ADD(CURDATE(), INTERVAL  4 DAY), '06:15:00',   74000.00, 120),
(207, 102, 'Buenos Aires', 'Rosario',       DATE_ADD(CURDATE(), INTERVAL  7 DAY), '08:00:00',   58000.00, 95),
(208, 102, 'Cordoba',      'Buenos Aires',  DATE_ADD(CURDATE(), INTERVAL 10 DAY), '19:30:00',   76500.00, 110),
(209, 102, 'Rosario',      'Buenos Aires',  DATE_ADD(CURDATE(), INTERVAL 13 DAY), '20:05:00',   59500.00, 84),
(210, 102, 'Buenos Aires', 'Salta',         DATE_ADD(CURDATE(), INTERVAL 18 DAY), '05:40:00',   98000.00, 64),
(211, 102, 'Salta',        'Buenos Aires',  DATE_ADD(CURDATE(), INTERVAL 24 DAY), '21:15:00',  101000.00, 71),

(212, 103, 'Posadas',      'Sao Paulo',     DATE_ADD(CURDATE(), INTERVAL  5 DAY), '10:25:00',  245000.00, 48),
(213, 103, 'Posadas',      'Asuncion',      DATE_ADD(CURDATE(), INTERVAL  8 DAY), '13:50:00',  118000.00, 60),
(214, 103, 'Corrientes',   'Montevideo',    DATE_ADD(CURDATE(), INTERVAL 11 DAY), '15:35:00',  156000.00, 52),
(215, 103, 'Sao Paulo',    'Posadas',       DATE_ADD(CURDATE(), INTERVAL 17 DAY), '22:40:00',  251000.00, 39),
(216, 103, 'Asuncion',     'Corrientes',    DATE_ADD(CURDATE(), INTERVAL 26 DAY), '11:05:00',  112000.00, 66),

(217, 104, 'Buenos Aires', 'Ushuaia',       DATE_ADD(CURDATE(), INTERVAL  3 DAY), '07:00:00',  289000.00, 25),
(218, 104, 'Buenos Aires', 'El Calafate',   DATE_ADD(CURDATE(), INTERVAL  9 DAY), '08:45:00',  264000.00, 33),
(219, 104, 'Ushuaia',      'Buenos Aires',  DATE_ADD(CURDATE(), INTERVAL 14 DAY), '17:20:00',  295000.00, 18),
(220, 104, 'El Calafate',  'Bariloche',     DATE_ADD(CURDATE(), INTERVAL 20 DAY), '12:30:00',  147000.00, 44),
(221, 104, 'Bariloche',    'Buenos Aires',  DATE_ADD(CURDATE(), INTERVAL 28 DAY), '19:55:00',  158000.00, 57),

(222, 105, 'Montevideo',   'Buenos Aires',  DATE_ADD(CURDATE(), INTERVAL  5 DAY), '09:40:00',   82000.00, 90),
(223, 105, 'Montevideo',   'Santiago',      DATE_ADD(CURDATE(), INTERVAL 16 DAY), '06:50:00',  221000.00, 47),
(224, 105, 'Punta del Este','Buenos Aires', DATE_ADD(CURDATE(), INTERVAL 23 DAY), '18:10:00',   96000.00, 62),

(231, 101, 'Mendoza',      'Lima',          DATE_ADD(CURDATE(), INTERVAL 19 DAY), '23:30:00',  312000.00, 0),
(232, 102, 'Buenos Aires', 'Tucuman',       DATE_ADD(CURDATE(), INTERVAL 22 DAY), '07:45:00',   88000.00, 0),
(233, 104, 'Buenos Aires', 'Rio Gallegos',  DATE_ADD(CURDATE(), INTERVAL 27 DAY), '13:15:00',  238000.00, 0),
(234, 105, 'Montevideo',   'Rio de Janeiro',DATE_ADD(CURDATE(), INTERVAL 30 DAY), '10:00:00',  268000.00, 0),

(241, 101, 'Mendoza',      'Buenos Aires',  DATE_SUB(CURDATE(), INTERVAL 45 DAY), '14:20:00',  128000.00, 40),
(242, 101, 'Buenos Aires', 'Santiago',      DATE_SUB(CURDATE(), INTERVAL 32 DAY), '08:30:00',  175000.00, 35),
(243, 102, 'Buenos Aires', 'Cordoba',       DATE_SUB(CURDATE(), INTERVAL 28 DAY), '06:15:00',   71000.00, 60),
(244, 102, 'Rosario',      'Buenos Aires',  DATE_SUB(CURDATE(), INTERVAL 21 DAY), '20:05:00',   57000.00, 72),
(245, 103, 'Posadas',      'Asuncion',      DATE_SUB(CURDATE(), INTERVAL 18 DAY), '13:50:00',  114000.00, 45),
(246, 104, 'Buenos Aires', 'Ushuaia',       DATE_SUB(CURDATE(), INTERVAL 14 DAY), '07:00:00',  281000.00, 20),
(247, 104, 'Buenos Aires', 'El Calafate',   DATE_SUB(CURDATE(), INTERVAL  9 DAY), '08:45:00',  259000.00, 28),
(248, 105, 'Montevideo',   'Buenos Aires',  DATE_SUB(CURDATE(), INTERVAL  5 DAY), '09:40:00',   79000.00, 80);


-- -----------------------------------------------------------------------------
-- PROMOCIONES
-- El sistema solo admite UNA promocion 'pendiente' o 'aprobada' por aerolinea.
-- Por eso cada aerolinea nueva tiene una sola activa; las 'denegada' no cuentan
-- para ese limite y sirven para que la auditoria del administrador tenga
-- historial. No se agregan promociones activas a las aerolineas que ya existian,
-- justamente para no romper esa regla.
-- -----------------------------------------------------------------------------
INSERT INTO PROMOCIONES (codPromocion, descripcionPromocion, descuentoPromocion, codAerolinea, estadoPromocion) VALUES
(101, 'Cruce de los Andes - descuento de temporada',        25.00, 101, 'aprobada'),
(102, 'Cabotaje simple - promocion permanente',             15.00, 102, 'aprobada'),
(103, 'Escapada al Litoral - fines de semana largos',       12.00, 103, 'aprobada'),
(104, 'Invierno en la Patagonia',                           30.00, 104, 'pendiente'),
(105, 'Verano en la costa uruguaya',                        18.00, 105, 'pendiente'),

(111, 'Promocion de lanzamiento 60%',                       60.00, 101, 'denegada'),
(112, 'Descuento acumulable con otras promociones',         40.00, 102, 'denegada'),
(113, 'Black Friday 55%',                                   55.00, 104, 'denegada'),
(114, 'Semana Santa 45%',                                   45.00, 105, 'denegada');


-- -----------------------------------------------------------------------------
-- RESERVAS
-- Mezcla de estados para que se vean todas las variantes de la pantalla:
--   confirmada          alimenta el historial de compras y los reportes de venta
--   pendiente de pago   muestra los botones de pagar y cancelar
--   cancelada           muestra el estado sin acciones disponibles
-- Las reservas sobre vuelos a mas de 72 horas se pueden cancelar desde la web;
-- las de vuelos cercanos, no. Las dos situaciones estan representadas.
-- -----------------------------------------------------------------------------
INSERT INTO RESERVAS (codReserva, codUsuario, codVuelo, fechaReserva, estadoReserva) VALUES
-- Lucia: compradora frecuente, historial cargado
(301, 121, 241, DATE_SUB(CURDATE(), INTERVAL 50 DAY), 'confirmada'),
(302, 121, 243, DATE_SUB(CURDATE(), INTERVAL 33 DAY), 'confirmada'),
(303, 121, 247, DATE_SUB(CURDATE(), INTERVAL 12 DAY), 'confirmada'),
(304, 121, 202, DATE_SUB(CURDATE(), INTERVAL  3 DAY), 'confirmada'),
(305, 121, 218, DATE_SUB(CURDATE(), INTERVAL  1 DAY), 'pendiente de pago'),
(306, 121, 206, CURDATE(),                            'pendiente de pago'),

-- Martin: mezcla de estados
(311, 122, 242, DATE_SUB(CURDATE(), INTERVAL 35 DAY), 'confirmada'),
(312, 122, 244, DATE_SUB(CURDATE(), INTERVAL 24 DAY), 'confirmada'),
(313, 122, 207, DATE_SUB(CURDATE(), INTERVAL  2 DAY), 'pendiente de pago'),
(314, 122, 210, CURDATE(),                            'pendiente de pago'),
(315, 122, 213, DATE_SUB(CURDATE(), INTERVAL  4 DAY), 'cancelada'),

-- Carla: viajera de la Patagonia
(321, 123, 246, DATE_SUB(CURDATE(), INTERVAL 20 DAY), 'confirmada'),
(322, 123, 217, DATE_SUB(CURDATE(), INTERVAL  6 DAY), 'confirmada'),
(323, 123, 219, DATE_SUB(CURDATE(), INTERVAL  2 DAY), 'pendiente de pago'),
(324, 123, 220, CURDATE(),                            'pendiente de pago'),
(325, 123, 204, DATE_SUB(CURDATE(), INTERVAL  8 DAY), 'cancelada'),

-- Diego: rutas de Cuyo
(331, 124, 241, DATE_SUB(CURDATE(), INTERVAL 48 DAY), 'confirmada'),
(332, 124, 201, DATE_SUB(CURDATE(), INTERVAL  5 DAY), 'confirmada'),
(333, 124, 203, DATE_SUB(CURDATE(), INTERVAL  1 DAY), 'pendiente de pago'),
(334, 124, 205, CURDATE(),                            'pendiente de pago'),

-- Sofia: region del Litoral
(341, 125, 245, DATE_SUB(CURDATE(), INTERVAL 22 DAY), 'confirmada'),
(342, 125, 212, DATE_SUB(CURDATE(), INTERVAL  7 DAY), 'confirmada'),
(343, 125, 214, DATE_SUB(CURDATE(), INTERVAL  3 DAY), 'pendiente de pago'),
(344, 125, 216, CURDATE(),                            'pendiente de pago'),
(345, 125, 215, DATE_SUB(CURDATE(), INTERVAL  9 DAY), 'cancelada'),

-- Tomas, Paula y Bruno: menos movimiento
(351, 126, 248, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 'confirmada'),
(352, 126, 222, DATE_SUB(CURDATE(), INTERVAL  2 DAY), 'pendiente de pago'),
(353, 126, 224, CURDATE(),                            'pendiente de pago'),

(361, 127, 243, DATE_SUB(CURDATE(), INTERVAL 30 DAY), 'confirmada'),
(362, 127, 209, DATE_SUB(CURDATE(), INTERVAL  4 DAY), 'confirmada'),
(363, 127, 211, DATE_SUB(CURDATE(), INTERVAL  1 DAY), 'pendiente de pago'),
(364, 127, 223, CURDATE(),                            'cancelada'),

(371, 128, 244, DATE_SUB(CURDATE(), INTERVAL 26 DAY), 'confirmada'),
(372, 128, 208, DATE_SUB(CURDATE(), INTERVAL  3 DAY), 'pendiente de pago'),
(373, 128, 221, CURDATE(),                            'pendiente de pago');


-- -----------------------------------------------------------------------------
-- NOVEDADES
-- Cubre los tres estados que muestra la pantalla: vigente, proxima y expirada.
-- -----------------------------------------------------------------------------
INSERT INTO NOVEDADES (codNovedad, textoNovedad, fechaPublicacionNovedad, fechaExpiracionNovedad) VALUES
(101, 'Ya estan disponibles los vuelos a la Patagonia para la temporada de invierno.', DATE_SUB(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY)),
(102, 'Incorporamos Andes Airlines y Pampa Air a nuestras aerolineas asociadas.',      DATE_SUB(CURDATE(), INTERVAL  7 DAY), DATE_ADD(CURDATE(), INTERVAL 20 DAY)),
(103, 'Ahora podes cancelar tus reservas online hasta 72 horas antes del vuelo.',      DATE_SUB(CURDATE(), INTERVAL  4 DAY), DATE_ADD(CURDATE(), INTERVAL 30 DAY)),
(104, 'Nuevas rutas al Litoral: Posadas, Corrientes y conexiones con Brasil.',         DATE_SUB(CURDATE(), INTERVAL  2 DAY), DATE_ADD(CURDATE(), INTERVAL 40 DAY)),
(105, 'Mejoramos el buscador de vuelos: ahora podes filtrar por aerolinea.',           CURDATE(),                            DATE_ADD(CURDATE(), INTERVAL 15 DAY)),

(111, 'Proximamente: programa de pasajero frecuente con acumulacion de millas.',       DATE_ADD(CURDATE(), INTERVAL  7 DAY), DATE_ADD(CURDATE(), INTERVAL 45 DAY)),
(112, 'En agosto sumamos vuelos internacionales a Lima y Bogota.',                     DATE_ADD(CURDATE(), INTERVAL 15 DAY), DATE_ADD(CURDATE(), INTERVAL 60 DAY)),

(121, 'Promocion de lanzamiento: 2x1 en vuelos de cabotaje.',                          DATE_SUB(CURDATE(), INTERVAL 60 DAY), DATE_SUB(CURDATE(), INTERVAL 30 DAY)),
(122, 'Mantenimiento programado del sistema durante el fin de semana.',                DATE_SUB(CURDATE(), INTERVAL 40 DAY), DATE_SUB(CURDATE(), INTERVAL 20 DAY));


-- =============================================================================
-- Resumen de lo cargado (verificado ejecutando el script)
--    5 aerolineas
--   14 usuarios     1 administrador
--                   5 CEO (3 aprobados + 2 pendientes de aprobacion)
--                   8 usuarios comunes
--   36 vuelos      24 con disponibilidad, aparecen en la busqueda
--                   4 sin asientos
--                   8 ya realizados, sostienen historial y reportes
--    9 promociones  3 aprobadas, 2 pendientes, 4 denegadas
--   35 reservas    16 confirmadas, 15 pendientes de pago, 4 canceladas
--                  19 de ellas se pueden cancelar desde la web
--    9 novedades    5 vigentes, 2 proximas, 2 expiradas
-- =============================================================================
