
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    if ($usuario == "mark" && $clave == "1234") {
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
                <h2>Bienvenido, $usuario!</h2>
              </div>";
    } else {
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif; color:red;'>
                <h3>Usuario o clave incorrectos.</h3>
              </div>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    body {
        background-image: url('https://smart-lighting.es/wp-content/uploads/2019/09/0-tienda-balenciaga.jpg');
        background-size: cover;
        background-position: center;
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-container {
        background: rgba(255, 255, 255, 0.15);
        padding: 40px 35px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        text-align: center;
        width: 340px;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        animation: fadeIn 1s ease-in-out;
    }

    .imagen_container {
        width: 110px;
        height: 110px;
        margin: 0 auto 20px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 0 20px rgba(255, 62, 65, 0.7);
        animation: float 3s ease-in-out infinite;
    }

    .imagen_container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    h2 {
        margin-bottom: 20px;
        color: #fff;
        letter-spacing: 1px;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: none;
        border-radius: 8px;
        outline: none;
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    input[type="text"]::placeholder,
    input[type="password"]::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        background: rgba(255, 255, 255, 0.4);
        box-shadow: 0 0 8px rgba(255, 62, 65, 0.8);
    }

    .btn {
        margin-top: 20px;
        background: linear-gradient(135deg, rgb(255, 62, 65), rgb(255, 120, 0));
        color: #fff;
        border: none;
        padding: 12px;
        width: 100%;
        border-radius: 30px;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    .btn:hover {
        background: linear-gradient(135deg, rgb(0, 0, 0), rgb(50, 50, 50));
        transform: scale(1.05);
    }

    .extra {
        margin-top: 20px;
        font-size: 0.9rem;
        color: #f1f1f1;
    }

    .extra a {
        color: #ff3e41;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .extra a:hover {
        color: #fff;
        text-decoration: underline;
    }

    .footer {
        margin-top: 25px;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
    }

    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
</style>

    <script>
        const base_url = '<?= BASE_URL; ?>';
    </script>
</head>

<body>
    <div class="login-container">
        <div class="imagen_container">
            <img src="https://unityvox.com/logo/loader.gif" alt="Logo Animado">
        </div>
        <h2>Iniciar sesión</h2>
        <form id="frm_login">
            <input type="text" placeholder="Usuario" required id="usuario" name="usuario">
            <input type="password" placeholder="Contraseña" required id="password" name="password">
            <button class="btn" type="button" onclick="iniciar_sesion();">iniciar sesion</button>
        </form>
        <div class="extra">
            <!--
            <p>¿Olvidaste tu contraseña? <a href="#">Recupérala</a></p>
            <p>¿Nuevo personal? <a href="#">Regístrate</a></p>-->
        </div>
        <div class="footer">
            <p>Mark Figueroa 2025</p>

        </div>
    </div>
    <script src="<?= BASE_URL; ?>view/function/user.js"></script>
</body>

</html>