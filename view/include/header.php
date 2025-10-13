<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>view/bootstrap/css/bootstrap.min.css">
    <script>
        const base_url = '<?php echo BASE_URL; ?>';
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <!--ESTILO DE PAGINA -->
    <style>
        
        body {
            background: linear-gradient(135deg, #1295f3ff 0%, #000000ff 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(90deg, #1976d2 0%, #0b0d0eff 100%) !important;
            box-shadow: 0 2px 12px rgba(25, 118, 210, 0.12);
        }

        .navbar-brand.styled-logo {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: bold;
            color: #fff !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            transition: color 0.3s, transform 0.3s;
        }

        .navbar-brand.styled-logo:hover {
            color: #004faaff !important;
            transform: scale(1.07);
            text-decoration: none;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: color 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #bbdefb !important;
            text-decoration: underline;
        }

        .collapse {
            background: transparent;
        }

        .card-header {
            color: #fff;
            background-color: #1976d2;
            font-weight: bold;
        }

        .dropdown-menu {
            background: #e3f2fd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.07);
        }

        .dropdown-item {
            color: #1976d2;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: #057ddfff;
            color: #0d47a1;
        }

        .titulo-usuarios {
            background: linear-gradient(90deg, #d8eefdff 0%, #5f87a8ff 100%);
            border: 2px solid #1976d2;
            box-shadow: 0 4px 16px rgba(25, 118, 210, 0.15);
            color: #1976d2 !important;
            font-size: 2rem;
            letter-spacing: 1px;
            padding: 0.75em 2em;
            margin-bottom: 1em;
            display: inline-block;
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            background: #fff;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }
    </style>
    <!--ESTILO DE PAGINA -->



    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand styled-logo" href="#">JB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>new-user">Registrar</a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>users">Users</a>
                    </li>
   
                    <!--<li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>new-producto">new-producto</a>
                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>producto">producto</a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>categoria">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>clients">Clients</a>
                    </li>

                    <!--<li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>new-client">Registrar clientes</a>
                    </li>-->



                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>proveedor">Proveedores</a>
                    </li>

                    <!--<li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>new-proveedor">Registrar proveedores</a>
                    </li>-->


                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>shops">Shops</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>sales">Sales</a>
                    </li>

                </ul>
                <form class="d-flex" role="search">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </nav>
    