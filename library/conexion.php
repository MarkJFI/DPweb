<?php
require_once __DIR__ . "/../config/config.php";

class Conexion
{
    public function connect()
    {
        $mysql = new mysqli(BD_HOST, BD_USER, BD_PASSWORD, BD_NAME);
        $mysql->set_charset(BD_CHARSET);
        date_default_timezone_set("America/Lima");

        if ($mysql->connect_errno) {
            die(json_encode([
                'status' => false,
                'msg' => 'Error de conexión a la base de datos'
            ]));
        }

        return $mysql;
    }
}
