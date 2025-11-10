<?php
class viewModel{
    protected static function get_view($view){
    $white_list = ["home","products","users", "new-user", "categoria","edit-user", "new-products", "edit-products", "categoria-lista", "categoria-edit", "products-list", "clientes", "new-clients", "client-lista", "edit-client", "proveedor-lista", "new-proveedor", "new-cliente", "index", "ventas"];
        if (in_array($view, $white_list)) {
            if (is_file("./view/".$view.".php")) {
                $content = "./view/".$view.".php";
            }else {
                $content = "404";
            }
        }elseif ($view=="login") {
            $content = "login";
        }else {
            $content = "404";
        }
        return $content;
    }
}