<?php
require_once __DIR__ . "/../config/config.php";
mysqli_report(MYSQLI_REPORT_OFF);

class Conexion
{
    public function connect()
    {
        $mysql = new mysqli(BD_HOST, BD_USER, BD_PASSWORD, BD_NAME);
        $mysql->set_charset(BD_CHARSET);

        if ($mysql->connect_errno) {
            echo json_encode([
                'status' => false,
                'msg' => 'Error de conexión BD'
            ]);
            exit;
        }
        return $mysql;
    }
}
