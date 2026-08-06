# Checklist de entrega de proyecto — Entornos Gráficos (UTN)
---

## Datos Del Proyecto
- Sobre el sistema:
.VuelaLibre es un sitio web dinámico que centraliza la interacción entre pasajeros, aerolíneas y administradores del sistema. La plataforma permite el ciclo completo de una reserva aérea: desde la búsqueda por origen, destino y fecha, hasta la confirmación de la compra y la gestión del historial personal.
Para las organizaciones, el sitio actúa como un panel de control donde se pueden gestionar flotas de vuelos, aplicar descuentos estratégicos mediante un sistema de promociones (sujeto a aprobación por parte de administradores) y comunicar novedades relevantes a los usuarios mediante anuncios programables.

- Tecnologias Utilizadas:
.PhP
.Bootstrap
.HTML
.CSS

- Integrantes del grupo: Ezequiel Rueda, Fausto Taborda, Elias Chamorro

- Usuarios de Prueba:
.ADMIN:
Correo: admin.demo@vuelalibre.test ;  Contraseña:  Admin2026!
.CEO:
Correo: ceo.andes@vuelalibre.test ; Contraseña: Ceo2026!
.USUARIO:
Correo: lucia.fernandez@vuelalibre.test ; Contraseña: Vuela2026!

## 1. Despliegue y acceso

- [x] El sistema está subido a un hosting y es accesible desde el navegador con una URL pública.
- [x] La URL funciona desde cualquier red y dispositivo (probalo desde el celular con datos, no desde tu wifi).
- [x] El sitio carga sin errores de certificado ni advertencias del navegador (HTTPS).
- [x] No quedan páginas rotas, links muertos ni rutas que devuelvan error 404 / 500.
- [x] Los archivos estáticos (imágenes, CSS, JS) cargan bien en producción, no solo en local.
- [x] El entorno de producción usa la base de datos de producción, no la de tu máquina.

## 2. Repositorio

- [x] El repositorio es público y la URL está incluida en la entrega.
- [x] Tiene un `README.md` con: qué hace el sistema, URL de producción, tecnologías usadas, integrantes del grupo y usuarios de prueba.
- [x] Historial de commits real y distribuido entre los integrantes (no un único commit "final").
- [x] Mensajes de commit entendibles.
- [ ] Hay un `.gitignore` correcto: **no** están subidos `vendor/`, `node_modules/`, `.env`, ni archivos temporales.
- [ ] **No hay credenciales, claves de API ni contraseñas en el código.**
- [ ] La rama principal es la que corresponde a lo entregado y está actualizada.

## 3. Se puede testear sin instrucciones

- [x] Existe al menos un usuario de prueba con su contraseña documentado en el README y en el informe.
- [x] Si hay roles (admin, usuario, etc.), hay un usuario de prueba por cada rol lo cual debe contar en el informe en la sección de Puesta en Funcionamiento.
- [x] Hay datos de prueba cargados: el sistema no debe abrirse vacío.
- [x] El registro de un usuario nuevo funciona de punta a punta.
- [x] Cualquier flujo importante se puede completar sin que nadie te explique nada.

## 4. Emails

- [x] El email de registro / confirmación de cuenta **llega efectivamente** a la casilla.
- [x] El email de recuperación de contraseña **llega** y el link funciona. (No aplica puesto que como regla de negocio no solicitamos un mail de confirmacion para cambiar la contraseña)
- [x] Los links dentro de los emails apuntan al dominio de producción, no a `localhost`.
- [x] Los emails no caen en spam (probalo con al menos dos proveedores distintos, ej. Gmail y Outlook).
- [x] El contenido del email está en español y sin textos de plantilla sin reemplazar (`{{nombre}}`, "Lorem ipsum", etc.).
- [x] El remitente es identificable (no "no-reply@algo-raro").

## 5. Navegación y usabilidad

- [x] Un usuario que no conoce el sistema entiende qué hacer al entrar.
- [x] Todas las pantallas son alcanzables desde el menú o desde algún flujo lógico. No hay pantallas "huérfanas" que solo se llegan escribiendo la URL.
- [x] Siempre se puede volver atrás o a la pantalla principal.
- [x] Los botones y links dicen qué hacen ("Guardar cambios", no "Enviar").
- [x] El estado actual es visible: se sabe si estás logueado, en qué sección estás y con qué usuario.
- [x] Las acciones destructivas (borrar) piden confirmación.
- [x] Después de cada acción hay feedback: mensaje de éxito o de error.
- [x] Las listas vacías muestran un mensaje ("Todavía no hay pedidos"), no una tabla en blanco.

## 6. Idioma y textos

- [x] Todos los textos visibles están en español: botones, títulos, menús, placeholders.
- [x] Los mensajes de validación están en español ("El email es obligatorio", no "This field is required").
- [x] Las alertas y mensajes de error del framework están traducidos o interceptados.
- [x] Los mensajes de error de la base de datos nunca se muestran crudos al usuario.
- [x] Sin faltas de ortografía ni textos de prueba ("asdasd", "probando 123").
- [x] Fechas, números y moneda en formato local.

## 7. Formularios y validaciones

- [x] Todos los campos obligatorios están validados **en el servidor** (no solo en el navegador).
- [x] Los errores se muestran al lado del campo que falla y no se pierde lo que el usuario ya cargó. (En nuestro caso, decidimos que los campos como contraseña y mail se borren por completo, por lo que si, en esos casos el usuario debe volver a cargar los datos)
- [x] Emails, números y fechas se validan con su formato.
- [x] No se puede romper el sistema mandando datos vacíos, muy largos o con caracteres raros.
- [x] No se puede enviar el mismo formulario dos veces por doble clic.

## 8. Seguridad mínima

- [x] Las contraseñas están hasheadas en la base de datos, nunca en texto plano. (Si, con MD5)
- [x] Las páginas privadas no se pueden abrir sin estar logueado (probá pegando la URL directa en una ventana de incógnito).
- [x] Un usuario no puede ver ni editar datos de otro usuario cambiando el ID en la URL.
- [x] Las consultas usan sentencias preparadas (nada de concatenar SQL).
- [x] El contenido cargado por usuarios se escapa al mostrarlo (sin XSS).
- [x] El logout funciona y cierra realmente la sesión.

## 9. Visual y responsive

- [ ] Se ve bien en celular, tablet y escritorio.
- [ ] Nada se superpone, se corta ni requiere scroll horizontal.
- [x] Estilo consistente entre pantallas (colores, tipografías, botones).
- [x] Las imágenes tienen tamaño razonable y `alt`.
- [x] Las imágenes tiene título visible al pasar mouse sobre las mismas.
- [x] Contraste suficiente para leer los textos.

## 10. Última pasada antes de entregar

- [x] La consola del navegador no muestra errores en rojo.
- [x] No quedan `var_dump`, `console.log`, `dd()` ni mensajes de debug visibles.
- [x] El modo debug está apagado en producción.
- [x] Recorriste el sistema completo desde cero, en una ventana de incógnito, como si fueras un usuario nuevo.
- [x] Se lo hiciste probar a alguien ajeno al grupo y pudo usarlo sin ayuda.
- [x] La entrega incluye: URL de producción, URL del repositorio, usuarios de prueba e integrantes e Informe completo.
- [x] Entregado en tiempo y forma según lo pedido en la consigna.

---
## Informe final

## 11. Formato General

- [x] Posee carátula con datos de identificación del equipo como del trabajo
- [x] Posee Indice completo navegable

## 12. Audiencia

- [x] Definió la audiencia describiendo la misma
- [x] Clasificó la audiencia según las caracteristicas que define la Guía Web de Chile

## 13. Estructura del Sitio

- [x]  Realizó el Árbol Funcional de Contenido según la definición de la Guía Web de Chile.

## 14. Estructura del Sitio

- [x] Realizó los diagramas de estructura de todas las páginas.
- [x] Realizó los diagramas de flujo de todas las páginas con transacción.

## 15. Sistema de Navegación

- [x] Definió el sistema de navegación.
- [x] Listó todos los elementos de navegación del sitio clasificándolos en Textuales y Contextuales.

## 16. Diseño Visual

- [x] Realizó los bocetos o maquetas del sitio completo.

## 17. Presupuesto Económico 

- [x] Realizó el diagrama de Gantt con las actividades que deben realizar para llevar a cabo el proyecto.
- [x] Definió los costos en recursos humanos para el proyecto.
- [x] Citó las fuentes de donde obtuvo los diferentes valores monetarios que se considera en el presupuesto (ejemplo: valor hora hombre, costo de hosting, costo de amortizaciones según valores reales de máquinas, etc).
- [x] Realizó la estimación del tamaño del proyecto según Puntos de Función.
- [x] Calculó el costo y el precio del sitio web.
- [x] Armó y dejó plasmado en el informe un presupuesto modelo con la información que analizó para entregar a un cliente.
- [x] Los valores del presupuesto se citan de fuentes reales. Valor hora hombre, costo de hosting, amortizaciones y cualquier otro monto deben tener fuente
      verificable y fecha. Un número generado por IA sin fuente se considera un dato inventado e invalida la sección.
- [x] No se cargan credenciales, datos reales de usuarios ni volcados de la base de datos en herramientas públicas de IA. Vale como criterio profesional, no solo
      como regla de la materia.
- [x] Los diagramas, modelos y checklists los interpreta el grupo. Se puede usar IA para producirlos, pero la fundamentación tiene que ser propia y coherente con el
      sistema entregado.

## 18. Modelos 

- [x] Realizó y dejó plasmado en el informe el diagrama del MODELO lógico de Datos
- [x] Realizó y dejó plasmado en el informe el diagrama del MODELO Físico

## 19. Check List 

- [x] Realizó los check list de Usabilidad y fundamentó los items que no se cumplen o no aplican.
- [x] Realizó los check list de Accesibilidad y fundamentó los items que no se cumplen o no aplican.
- [x] Realizó los check list de Rapidez de Acceso y fundamentó los items que no se cumplen o no aplican.
- [x] Realizó los check list de Perceptibilidad y fundamentó los items que no se cumplen o no aplican.
- [x] Realizó los check list de Operabilidad y fundamentó los items que no se cumplen o no aplican.
- [x] Realizó los check list de Comprensibilidad y fundamentó los items que no se cumplen o no aplican.
- [x] Realizó los check list de Robustez y fundamentó los items que no se cumplen o no aplican.

## 20. Test de Validación de Estándares

- [ ] Realizó los test de validación de HTML.
- [x] Realizó los test de validación de CSS.
- [x] Realizó los test de validación de ACCESIBLIDAD.
- [ ] Pegó reportes de cada uno de los test de validación en el informe
- [x] Explicó lo mostrado en los reportes.
- [x] Realizó los cambios sugeridos por los test de validación. (Hay algunas Warnings de los tests que no fueron cambiadas por diferentes cuestiones, en su mayoria esteticas, y porque su )
- [x] Fundamentó los cambios realizados según lo solicitado por los test de validación.
- [x] En caso de haber tenido que realizar modificaciones, volvió a pasar los test, y pegó cada reporte.

--
## 21. Uso de herramientas de IA

El uso de herramientas de inteligencia artificial (Claude, ChatGPT, Copilot, generadores
de UI, etc.) *está permitido* en este trabajo. No está permitido entregar código que el
grupo no pueda explicar.

### Qué se evalúa

La entrega se evalúa sobre aquello que el grupo puede sostener, no sobre lo que está escrito.
**Cada integrante debe poder explicar cualquier línea del código entregado y cualquier
decisión del informe**, sin importar quién o qué la haya generado. Si no lo pueden
explicar, no deben entregarlo.

### Anexar al informe

El informe debe incluir una sección "Uso de herramientas de IA" (media carilla alcanza no debe ser extenso) con:

- [x] *Qué herramientas usaron* (nombre y versión aproximada).
- [x] *Para qué las usaron*: código, redacción del informe, diagramas, estimaciones,
      debugging, traducción, etc.
- [x] *Qué verificaron y cómo*: para el código y los datos generados, qué chequearon
      a mano, qué corrigieron y por qué.

Si el grupo no utilizó ninguna herramienta de IA, también debe declararlo.

*Si un ítem no aplica a tu proyecto, dejalo marcado  y aclaralo en el README. Fundamentar el por qué no aplica dicho item*

### Cuestiones Importantes
.En algunas ocasiones al presionar algún botón del sitio, este muestra una página en blanco con ciertos caracteres. Revisamos diferentes cuestiones y llegamos a la conclusión de que es un error del Host, pues:
- No hay fetch, AJAX, document.write, service workers, ni nada que renderice HTML del lado del cliente. Todo el sitio es HTML generado 100% en el servidor por PHP.
- Se probo pegarle directamente al sitio con ráfagas de pedidos concurrentes simulando "clicks aleatorios" a varias páginas, y siempre devolvió el HTML completo y correcto.
- Nunca se pudo reproducir en local.
Y concluimos en que es "aleatorio" y ligado a clickear rápido en distintas páginas (más pedidos simultáneos = más chance).
