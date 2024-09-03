<?php
require_once 'Model.php';

class Controller
{
    private $Model;

    public function __construct()
    {
        $this->Model = new Model();
    }

    public function getData()
    {
        try {
            $data = $this->Model->getData($_POST);
            echo json_encode($data, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}
if (isset($_POST['funcion'])) {
    call_user_func(array(new controller, $_POST['funcion']));
}
