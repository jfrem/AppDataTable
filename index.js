/**
 * URL del endpoint del servidor para obtener los datos de la tabla.
 * Esta URL es el punto de entrada para las solicitudes AJAX que obtendrán datos para la tabla.
 */
const url = '/proyectos/AudActivos/controller.php';

/**
 * Función para generar un identificador único con un prefijo opcional.
 * @param {string} prefix - Prefijo opcional que se añadirá al identificador único.
 * @returns {string} - Un identificador único generado.
 */
const uniqid = (prefix = '') => prefix + Math.random().toString(36).substr(2, 9);

/**
 * Configuración común para las opciones de DataTable.
 * Estas opciones definen cómo se comporta y muestra la tabla.
 */
const commonDataTableOptions = {
    processing: true, // Muestra un mensaje de procesamiento mientras se cargan los datos.
    responsive: true, // Hace que la tabla se adapte a diferentes tamaños de pantalla.
    serverSide: true, // Indica que la tabla debe cargar los datos desde el servidor.
    scrollY: '45vh', // Establece la altura de la tabla con una vista vertical fija.
    scrollX: false, // Desactiva el desplazamiento horizontal.
    paging: true, // Activa la paginación.
    language: {
        decimal: "",
        emptyTable: "No hay información", // Mensaje cuando la tabla está vacía.
        info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas", // Información de la paginación.
        infoEmpty: "Mostrando 0 a 0 de 0 Entradas", // Mensaje cuando no hay entradas.
        infoFiltered: "(Filtrado de _MAX_ entradas totales)", // Información sobre el filtrado.
        thousands: ",", // Separador de miles.
        lengthMenu: "Mostrar _MENU_ Filas", // Menú para seleccionar el número de filas a mostrar.
        loadingRecords: `<i class="fa fa-spinner fa-spin fa-2x fa-fw" style="z-index: 1050; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>`, // Icono de carga.
        processing: `<i class="fa fa-spinner fa-spin fa-2x fa-fw text-primary" style="z-index: 1050; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>`, // Icono de procesamiento.
        loadingRecordsError: "Error al cargar los datos :(", // Mensaje de error al cargar los datos.
        search: "Buscar:", // Texto del campo de búsqueda.
        zeroRecords: "Sin resultados encontrados", // Mensaje cuando no se encuentran resultados.
        paginate: {
            first: "Primero", // Texto para el botón de la primera página.
            last: "Último", // Texto para el botón de la última página.
            next: "Siguiente", // Texto para el botón de la siguiente página.
            previous: "Anterior" // Texto para el botón de la página anterior.
        }
    },
    dom: 'Blfrtip', // Define la disposición de los elementos de la tabla.
    buttons: ['csv', 'excel', 'print', { extend: 'colvis', text: 'Ocultar Columnas' }], // Botones para exportar y ocultar columnas.
    lengthMenu: [
        [50, 100, 200, -1], // Opciones de número de filas por página.
        [50, 100, 200, "Todos"] // Etiquetas para las opciones anteriores.
    ],
};

/**
 * Configura y genera una tabla DataTable con las opciones proporcionadas.
 * @param {string} tableSelector - Selector del elemento HTML donde se insertará la tabla.
 * @param {Array} columns - Definición de las columnas para la tabla.
 * @returns {string} - El ID único generado para la tabla.
 */
const configDataTable = (tableSelector, columns) => {
    // Genera un ID único para la tabla.
    const tableId = uniqid('table_');

    // Configura las opciones específicas para la tabla.
    const options = {
        ...commonDataTableOptions,
        ajax: {
            url: url, // URL del endpoint para obtener los datos.
            type: 'POST', // Tipo de solicitud HTTP.
            dataType: 'json', // Tipo de datos esperados de la respuesta.
            data: d => ({ ...d, funcion: 'getData' }), // Datos adicionales enviados en la solicitud.
            error: (xhr, textStatus, errorThrown) => {
                console.error('Error al cargar los datos:', textStatus, errorThrown); // Manejo de errores.
            }
        },
        columns: columns // Definición de las columnas de la tabla.
    };

    // Crea el HTML de la tabla y lo inserta en el selector especificado.
    $(tableSelector).html(
        `<table id="${tableId}" class="table table-sm align-middle mb-0 bg-white">
            <thead class="bg-light">
                <tr>
                    ${columns.map(column => `<th>${column.name}</th>`).join('')}
                </tr>
            </thead>
            <tbody></tbody>
        </table>`
    );

    // Inicializa DataTable con las opciones configuradas.
    const dataTable = $(`#${tableId}`).DataTable(options);

    // Agrega los botones al contenedor de la tabla.
    $(`#${tableId}_wrapper .col-md-6:eq(0)`).append(dataTable.buttons().container());

    return tableId;
};

/**
 * Configura la tabla con el selector y las columnas proporcionadas.
 * @param {string} tableSelector - Selector del elemento HTML donde se insertará la tabla.
 * @param {Array} columns - Definición de las columnas para la tabla.
 * @returns {string} - El ID único generado para la tabla.
 */
const setupTable = (tableSelector, columns) => {
    return configDataTable(tableSelector, columns);
};

/**
 * Define las columnas para la tabla y configura la tabla utilizando esas columnas.
 */
const getData = () => {
    const columns = [
        { data: 'Placa', name: 'Placa' },
        { data: 'Activo', name: 'Activo' },
        { data: 'Estatus', name: 'Estatus' },
        { data: 'SitioActivo', name: 'Sitio Activo' },
        { data: 'Encargado', name: 'Encargado' },
        { data: 'Sitio_Encargado', name: 'Sitio Encargado' },
        {
            data: 'AudContrato', name: 'Aud Contrato',
            render: (data, type, row) =>
                `<span class="badge ${data === 'Cto Inactivo' ? 'badge-danger' : 'badge-success'} rounded-pill d-inline">${data}</span>`
        },
        {
            data: 'AudUbicacion', name: 'Aud Ubicacion',
            render: (data, type, row) =>
                `<span class="badge ${data === 'Sitio Errado' ? 'badge-danger' : 'badge-success'} rounded-pill d-inline">${data}</span>`
        }
    ];
    // Configura la tabla con el selector '#getData' y las columnas definidas.
    setupTable('#getData', columns);
};

// Inicializa la tabla cuando el documento esté listo.
$(document).ready(() => {
    getData();
});
