<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    if ($usuario == "brayan" && $clave == "1234") {
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
            background-image: url(https://i.pinimg.com/originals/f8/01/a3/f801a36277fef9657a41b4c5954506e1.gif);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: wheat;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(249, 2, 2, 0.99);
            text-align: center;
            width: 320px;
        }
        .imagen_container {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px auto;
            
        }
        .imagen_container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .emoji {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border 0.3s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border: 1px solid #8e44ad;
        }
        .btn {
            margin-top: 15px;
            background: rgb(255, 62, 65);
            color: #fff;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 30px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: rgb(0, 0, 0);
        }
        .extra {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
        }
        .extra a {
            color: rgb(0, 0, 0);
            text-decoration: none;
        }
        .extra a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 25px;
            font-size: 0.8rem;
            color: #aaa;
        }
    </style>
    <script>
        const base_url = '<?= BASE_URL; ?>';
    </script>
</head>

<body>
    <div class="login-container">
        <div class="imagen_container">
            <img src="https://cdn.dribbble.com/userupload/21193457/file/original-8c00e9c322223c8930eff042dc435445.gif" alt="Logo Animado">
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
            <p>Jesus Brayan Nuñez Meza 2025</p>

        </div>
    </div>
    <script src="<?= BASE_URL; ?>view/function/user.js"></script>
</body>

</html>