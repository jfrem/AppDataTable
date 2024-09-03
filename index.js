const url = '/proyectos/AudActivos/controller.php';

// Función para generar un identificador único con un prefijo opcional
const uniqid = (prefix = '') => prefix + Math.random().toString(36).substr(2, 9);

// Configuración común para DataTable
const commonDataTableOptions = {
    processing: true,
    responsive: true,
    serverSide: true,
    scrollY: '45vh',
    scrollX: false,
    paging: true,
    language: {
        decimal: "",
        emptyTable: "No hay información",
        info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        infoEmpty: "Mostrando 0 a 0 de 0 Entradas",
        infoFiltered: "(Filtrado de _MAX_ entradas totales)",
        thousands: ",",
        lengthMenu: "Mostrar _MENU_ Filas",
        loadingRecords: `<i class="fa fa-spinner fa-spin fa-2x fa-fw" style="z-index: 1050; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>`,
        processing: `<i class="fa fa-spinner fa-spin fa-2x fa-fw text-primary" style="z-index: 1050; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>`,
        loadingRecordsError: "Error al cargar los datos :(",
        search: "Buscar:",
        zeroRecords: "Sin resultados encontrados",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    },
    dom: 'Blfrtip',
    buttons: ['csv', 'excel', 'print', {extend: 'colvis',text: 'Ocultar Columnas'}],
    lengthMenu: [
        [50, 100, 200, -1],
        [50, 100, 200, "Todos"]
    ],
};

// Función para configurar DataTable
const configDataTable = (tableSelector, columns) => {
    const tableId = uniqid('table_');

    const options = {
        ...commonDataTableOptions,
        ajax: {
            url: url,
            type: 'POST',
            dataType: 'json',
            data: d => ({ ...d, funcion: 'getData' }),
            error: (xhr, textStatus, errorThrown) => {
                console.error('Error al cargar los datos:', textStatus, errorThrown);
            }
        },
        columns: columns
    };

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

    const dataTable = $(`#${tableId}`).DataTable(options);
    $(`#${tableId}_wrapper .col-md-6:eq(0)`).append(dataTable.buttons().container());

    return tableId;
};

// Configuración de tablas específicas usando parámetros para columnas
const setupTable = (tableSelector, columns) => {
    return configDataTable(tableSelector, columns);
};

// Función específica para configurar la tabla de datos
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
    setupTable('#getData', columns);
};

$(document).ready(() => {
    getData();
});
