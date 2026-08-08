-- =============================================================================
-- VuelaLibre - Migracion de la tabla RESERVAS
-- =============================================================================
--
-- Agrega la columna que permite reservar mas de un pasaje por vuelo:
--
--   cantidadPasajes   cuantos asientos ocupa la reserva (de 1 a 10)
--
-- Sin ella aparece, entre otros:
--   Uncaught mysqli_sql_exception: Unknown column 'r.cantidadPasajes'
--
-- Las reservas que ya estaban cargadas quedan en 1, que es exactamente lo que
-- valian antes de este cambio, asi que los totales del historial y de los
-- reportes no se mueven.
--
-- SE PUEDE EJECUTAR VARIAS VECES.
-- Antes de cada cambio consulta el estado real de la base, asi que si la
-- columna ya existe no hace nada.
--
-- Al terminar muestra un resumen de como quedo la tabla.
-- =============================================================================


-- -----------------------------------------------------------------------------
-- cantidadPasajes
-- -----------------------------------------------------------------------------
SET @existe := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND UPPER(TABLE_NAME) = 'RESERVAS'
    AND COLUMN_NAME = 'cantidadPasajes'
);

SET @sql := IF(@existe = 0,
  'ALTER TABLE RESERVAS ADD COLUMN cantidadPasajes TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER codVuelo',
  'DO 0'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


-- -----------------------------------------------------------------------------
-- Normaliza cualquier valor fuera del rango permitido (1 a 10).
-- Contempla filas viejas cargadas a mano antes de esta migracion.
-- -----------------------------------------------------------------------------
UPDATE RESERVAS SET cantidadPasajes = 1  WHERE cantidadPasajes < 1;
UPDATE RESERVAS SET cantidadPasajes = 10 WHERE cantidadPasajes > 10;


-- -----------------------------------------------------------------------------
-- Resumen
-- -----------------------------------------------------------------------------
SELECT 'RESERVAS' AS tabla,
       COUNT(*)                        AS reservas,
       MIN(cantidadPasajes)            AS minPasajes,
       MAX(cantidadPasajes)            AS maxPasajes,
       SUM(cantidadPasajes)            AS pasajesTotales
FROM RESERVAS;
