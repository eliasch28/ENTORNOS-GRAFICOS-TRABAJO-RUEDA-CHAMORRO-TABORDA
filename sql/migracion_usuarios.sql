-- Columnas que el codigo de la rama pruebasFinales2 necesita en la tabla USUARIOS
-- y que no existen en el esquema anterior.
--
-- Sin estas dos columnas fallan, entre otros:
--   Views/FuncionesAdmin/global-reports.php   (Reporte de Ventas y Reporte de Usuarios)
--   Views/FuncionesAdmin/solicitudes-ceo.php
--   Views/FuncionesCEO/reportes-ceo.php
--   Views/FuncionesCEO/vuelos-index.php, vuelos-create.php, vuelos-mod.php
--   Views/FuncionesCEO/promociones-index.php, promociones-create.php, promociones-mod.php
--   Views/FuncionesUsuario/mi_perfil.php, reservar_vuelo.php
--   Views/FlujoSesion/registrarse_.php, login.php
--   Views/LandPage/LandUsuarioRegistrado.php
--
-- El error que aparece es:
--   Uncaught mysqli_sql_exception: Unknown column 'u.apellidoUsuario' in 'field list'

ALTER TABLE USUARIOS
  ADD COLUMN apellidoUsuario VARCHAR(100) NOT NULL DEFAULT '' AFTER nombreUsuario;

ALTER TABLE USUARIOS
  ADD COLUMN codAerolinea INT NULL;

ALTER TABLE USUARIOS
  ADD CONSTRAINT fk_usuarios_aerolinea
  FOREIGN KEY (codAerolinea) REFERENCES AEROLINEAS(codAerolinea);

-- Los usuarios que ya existian quedan con el apellido vacio.
-- Completar a mano o dejar un valor provisorio:
-- UPDATE USUARIOS SET apellidoUsuario = 'Sin apellido' WHERE apellidoUsuario = '';

-- codAerolinea solo se completa para los usuarios de tipo 'ceo de aerolinea'.
-- Ejemplo:
-- UPDATE USUARIOS SET codAerolinea = 1 WHERE nombreUsuario = 'ceo_argentina';
