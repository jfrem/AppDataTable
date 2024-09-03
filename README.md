# DataTables Configuration

**DataTables Configuration** es una solución para configurar y generar tablas dinámicas utilizando la biblioteca DataTables. Proporciona una configuración estándar para tablas interactivas con soporte para exportación de datos y personalización visual.

## Características

- **Generación de Identificadores Únicos:** Crea identificadores únicos para cada tabla con un prefijo opcional.
- **Configuración Común:** Incluye opciones para procesamiento, paginación, búsqueda, y exportación (CSV, Excel, impresión).
- **Personalización de Datos:** Usa badges para resaltar estados especiales en las celdas.
- **Integración con AJAX:** Configura tablas para obtener datos de un servidor mediante solicitudes AJAX.

## Instalación

1. **Clonar el repositorio:**

   ```bash
   git clone https://github.com/jfrem/AppDataTable.git
   
2. **Acceder al directorio del proyecto:**

   ```bash
   cd tu-repositorio
3. **Incluir archivos DataTables:**
   Asegúrate de incluir los archivos JavaScript y CSS de DataTables en tu HTML.
## Uso

1. **Configurar una tabla:**
   Define las columnas de la tabla y configura la tabla usando la función `setupTable`.
   ```bash
   const columns = [
    { data: 'Placa', name: 'Placa' },
    { data: 'Activo', name: 'Activo' },
    { data: 'Estatus', name: 'Estatus' },
    // Añadir más columnas según sea necesario
   ];
   setupTable('#miTabla', columns);
2. **Configurar AJAX para obtener datos:**
   Define la URL del servidor para obtener datos en formato JSON.
   
   ```bash
   const url = '/proyectos/MiProyecto/controller.php';
## Contribuciones

Las contribuciones son bienvenidas. Si deseas contribuir a este proyecto, sigue estos pasos:
1. **Haz un fork del repositorio.**
2. **Crea una nueva rama:**
   ```bash
   git checkout -b feature/nueva-funcionalidad
4. Realiza tus cambios y haz commits:
   ```bash
   git commit -am 'Añadir nueva funcionalidad'
5. Haz push a la rama:
   ```bash
   git push origin feature/nueva-funcionalidad
6. Abre un Pull Request en GitHub.
   
