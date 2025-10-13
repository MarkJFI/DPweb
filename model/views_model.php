<?php
class viewModel
{
    protected static function get_view($view)
    {
        // Lista blanca de vistas permitidas
        $white_list = [
            "login", "home", "producto", "users","edit-producto","clientes", "new-producto", "new-user", "edit-categoria","new-categoria", "edit-user", "categoria", "clients", "shops", "sales",
<<<<<<< HEAD
            "new-client", "edit-client", "proveedor", "new-proveedor", "edit-proveedor"
=======
            "new-clients", "edit-clientes"
>>>>>>> c3748858bd5ae4169b7dea2a5a5343ac4e2287b1
        ];

        if (in_array($view, $white_list)) {
            // Si existe el archivo de la vista
            if (is_file("./view/" . $view . ".php")) {
                $content = "./view/" . $view . ".php";
            } else {
                $content = "404"; // Vista no encontrada
            }
        } elseif ($view == "login") {
            $content = "login";
        } else {
            $content = "404";
        }

        return $content;
    }
}
?>
