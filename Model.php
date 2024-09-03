<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';

class Model
{
    private $CNX;

    public function __construct()
    {
        $this->CNX = conexion::sql();
    }

    public function procesSql($data, $sql = null)
    {
        try {
            // Parámetros de la petición
            $draw = isset($data['draw']) ? intval($data['draw']) : 1;
            $start = isset($data['start']) ? intval($data['start']) : 0;
            $length = isset($data['length']) ? intval($data['length']) : 10;
            if ($length < 0) {
                $length = 9999999;
            }
            $searchValue = isset($data['search']['value']) ? $data['search']['value'] : '';
            $orderColumnIndex = isset($data['order'][0]['column']) ? intval($data['order'][0]['column']) : 0;
            $orderColumnName = isset($data['columns'][$orderColumnIndex]['data']) ? $data['columns'][$orderColumnIndex]['data'] : '';
            $orderDir = isset($data['order'][0]['dir']) ? $data['order'][0]['dir'] : 'asc';

            // Aquí, 'columns' proporciona información sobre las columnas
            $columns = isset($data['columns']) ? $data['columns'] : [];

            // Construcción de la consulta SQL
            $query = "SELECT * FROM (" . $sql . " ) S";

            // Contar los registros totales sin filtrar
            $countSql = "SELECT COUNT(*) AS total FROM ($query) AS total";
            $countStmt = $this->CNX->prepare($countSql);
            // print_r($countStmt);
            $countStmt->execute();
            $totalRecords = $countStmt->fetchColumn();

            // Obtener los nombres de las columnas
            $stmt = $this->CNX->prepare($query);
            $stmt->execute();
            $columns = [];
            foreach (range(0, $stmt->columnCount() - 1) as $index) {
                $columnMeta = $stmt->getColumnMeta($index);
                $columns[] = ['data' => $columnMeta['name'], 'title' => $columnMeta['name']];
            }

            // Aplicar filtros si se proporciona un valor de búsqueda
            $whereClauses = [];
            if (!empty($searchValue)) {
                foreach ($data['columns'] as $column) {
                    if (isset($column['searchable']) && $column['searchable'] == 'true') {
                        $columnData = trim($column['data']);
                        if (!empty($columnData)) {
                            $whereClauses[] = $column['data'] . " LIKE '%{$data['search']['value']}%'";
                        }
                    }
                }
            }

            // Determinar si la consulta original ya tiene una cláusula WHERE
            $separator = stripos($query, 'WHERE') === true ? 'AND' : 'WHERE';
            $whereSql = !empty($whereClauses) ? "$separator " . implode(' OR ', $whereClauses) : '';

            // Contar los registros filtrados
            $countFilteredSql = "SELECT COUNT(*) AS total FROM ($query $whereSql) AS filtered";
            $countFilteredStmt = $this->CNX->prepare($countFilteredSql);
            // print_r($countFilteredStmt);
            $countFilteredStmt->execute();
            $totalFilteredRecords = $countFilteredStmt->fetchColumn();

            // Agregar ordenamiento y límites para la paginación
            $query .= " $whereSql ORDER BY $orderColumnName $orderDir OFFSET :start ROWS FETCH NEXT :length ROWS ONLY";
            $stmt = $this->CNX->prepare($query);
            // print_r($stmt);
            $stmt->bindParam(':start', $start, PDO::PARAM_INT);
            $stmt->bindParam(':length', $length, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);

            // Retornar los resultados procesados
            return [
                "draw" => $draw,
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($totalFilteredRecords),
                "data" => $data,
                "columns" => $columns
            ];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public function getData($data)
    {
        $sql = "SELECT s.Id_Activo, s.Placa, s.Activo, s.Estatus, s.SitioActivo, s.Encargado, s.Sitio_Encargado,
               CASE s.Cto WHEN 1 THEN 'Cto Activo' ELSE 'Cto Inactivo' END AS AudContrato,
               CASE s.Sitio WHEN 1 THEN 'Sitio Ok' ELSE 'Sitio Errado' END AS AudUbicacion
        FROM (
            SELECT ActFijNitSecEnc, a.ActFijSec AS Id_Activo, a.ActFijCod AS Placa, a.ActFijNom AS Activo,
                   es.EsfActNom AS Estatus, s.SictraNom AS SitioActivo, a.ActFijEnc AS Encargado,
                   cto.sitioCto AS Sitio_Encargado,
                   CASE WHEN cto.NitSec IS NULL THEN 0 ELSE 1 END AS Cto,
                   CASE WHEN cto.SictraSec = a.SictraSec THEN 1 ELSE 0 END AS Sitio
            FROM ActivosFijos a
            LEFT JOIN estadofisicoactfij es ON es.EsfActSec = a.EsfActSec
            LEFT JOIN (
                SELECT n.NitSec, c.empnitcom AS Nombre, c.SictraSec, s.SictraNom AS sitioCto
                FROM Contrato c
                LEFT JOIN nit n ON n.NitSec = c.EmpNitSec
                LEFT JOIN SitioTrabajo s ON s.SictraSec = c.SictraSec
                WHERE c.ContrEstado = 'A'
                GROUP BY n.NitSec, c.empnitcom, c.SictraSec, s.SictraNom
            ) cto ON cto.Nombre = a.ActFijEnc
            LEFT JOIN SitioTrabajo s ON s.SictraSec = a.SictraSec
            WHERE a.EsfActSec <> '2' and isnull(ArSicTraSec,0)<=0
        ) s
        WHERE Cto = 0 OR Sitio = 0";
        return $this->procesSql($data, $sql);
    }
}
