<?php
require_once 'Model.php';

/**
 * Clase que maneja la lógica del controlador para interactuar con el modelo y gestionar las solicitudes de datos.
 */
class Controller
{
    private $Model;

    /**
     * Constructor de la clase. Inicializa el modelo.
     */
    public function __construct()
    {
        $this->Model = new Model(); // Crea una instancia de la clase Model para manejar la lógica de datos.
    }

    /**
     * Método para obtener datos procesados del modelo y enviarlos como una respuesta JSON.
     */
    public function getData()
    {
        try {
            // Llama al método getData del modelo para obtener datos según los parámetros enviados por POST.
            $data = $this->Model->getData($_POST);
            // Envía los datos como JSON, con formato bonito para facilitar la lectura.
            echo json_encode($data, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            // En caso de error, envía un código de respuesta HTTP 500 (Error del servidor) y un mensaje de error en formato JSON.
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}

// Verifica si el parámetro 'funcion' está presente en la solicitud POST.
if (isset($_POST['funcion'])) {
    // Llama al método especificado en el parámetro 'funcion' de la clase Controller.
    call_user_func(array(new Controller, $_POST['funcion']));
}
