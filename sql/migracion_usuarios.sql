-- =============================================================================
-- VuelaLibre - Migracion de la tabla USUARIOS
-- =============================================================================
--
-- Agrega las dos columnas que el codigo necesita y que no existen en el
-- esquema original:
--
--   apellidoUsuario   se usa en registro, login, mi perfil, reportes del
--                     administrador, reportes del CEO y solicitudes de CEO
--   codAerolinea      vincula al usuario CEO con su aerolinea
--
-- Sin ellas aparece, entre otros:
--   Uncaught mysqli_sql_exception: Unknown column 'u.apellidoUsuario'
--
-- SE PUEDE EJECUTAR VARIAS VECES.
-- Antes de cada cambio consulta el estado real de la base, asi que sirve tanto
-- para una base con el esquema viejo como para una que ya tenga alguna de las
-- dos columnas. Si ya esta todo aplicado, no hace nada.
--
-- Al terminar muestra un resumen de como quedo la tabla.
-- =============================================================================


-- -----------------------------------------------------------------------------
-- apellidoUsuario
-- -----------------------------------------------------------------------------
SET @existe := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND UPPER(TABLE_NAME) = 'USUARIOS'
    AND COLUMN_NAME = 'apellidoUsuario'
);

SET @sql := IF(@existe = 0,
  'ALTER TABLE USUARIOS ADD COLUMN apellidoUsuario VARCHAR(100) NOT NULL DEFAULT '''' AFTER nombreUsuario',
  'DO 0'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


-- -----------------------------------------------------------------------------
-- codAerolinea
-- -----------------------------------------------------------------------------
SET @existe := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND UPPER(TABLE_NAME) = 'USUARIOS'
    AND COLUMN_NAME = 'codAerolinea'
);

SET @sql := IF(@existe = 0,
  'ALTER TABLE USUARIOS ADD COLUMN codAerolinea INT NULL',
  'DO 0'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


-- -----------------------------------------------------------------------------
-- Clave foranea de codAerolinea hacia AEROLINEAS
--
-- Es opcional: ninguna consulta del sistema la necesita, solo aporta integridad
-- referencial. Se agrega unicamente si todavia no hay una clave foranea sobre
-- esa columna y si las dos tablas son InnoDB (MyISAM no soporta claves foraneas
-- y la sentencia fallaria).
-- -----------------------------------------------------------------------------
SET @yaTiene := (
  SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE
  WHERE TABLE_SCHEMA = DATABASE()
    AND UPPER(TABLE_NAME) = 'USUARIOS'
    AND COLUMN_NAME = 'codAerolinea'
    AND REFERENCED_TABLE_NAME IS NOT NULL
);

SET @innodb := (
  SELECT COUNT(*) FROM information_schema.TABLES
  WHERE TABLE_SCHEMA = DATABASE()
    AND UPPER(TABLE_NAME) IN ('USUARIOS', 'AEROLINEAS')
    AND ENGINE = 'InnoDB'
);

SET @sql := IF(@yaTiene = 0 AND @innodb = 2,
  'ALTER TABLE USUARIOS ADD CONSTRAINT fk_usuarios_aerolinea FOREIGN KEY (codAerolinea) REFERENCES AEROLINEAS(codAerolinea)',
  'DO 0'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


-- -----------------------------------------------------------------------------
-- Resultado
-- -----------------------------------------------------------------------------
SELECT
  (SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND UPPER(TABLE_NAME) = 'USUARIOS'
      AND COLUMN_NAME = 'apellidoUsuario')                          AS apellidoUsuario,
  (SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND UPPER(TABLE_NAME) = 'USUARIOS'
      AND COLUMN_NAME = 'codAerolinea')                             AS codAerolinea,
  (SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE() AND UPPER(TABLE_NAME) = 'USUARIOS'
      AND COLUMN_NAME = 'codAerolinea'
      AND REFERENCED_TABLE_NAME IS NOT NULL)                        AS claveForanea;
-- Las tres columnas del resultado deben dar 1.


-- =============================================================================
-- Despues de migrar
--
-- Los usuarios que ya existian quedan con el apellido vacio. Para completarlos:
--   UPDATE USUARIOS SET apellidoUsuario = 'Sin apellido' WHERE apellidoUsuario = '';
--
-- codAerolinea solo se completa para los usuarios de tipo 'ceo de aerolinea':
--   UPDATE USUARIOS SET codAerolinea = 1 WHERE emailUsuario = 'ceo@ejemplo.com';
-- =============================================================================
