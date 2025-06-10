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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <style>
        body {
            background-image: url(https://img.freepik.com/vector-gratis/salvapantallas-abstracto-lluvia-pixeles_23-2148370794.jpg?semt=ais_hybrid&w=740);
            background-color:#e59786;
            color: black;
            font-family: Arial, sans-serif;
        }
        .login-box {
            color: #2980b9;
            width: 300px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border: 5px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(15, 255, 111, 0.1);
        }
        h2 {
            text-align: center;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Iniciar sesión</h2>
        <img src="https://i.pinimg.com/originals/45/e4/79/45e479ae0f8355a254862992bac33f5b.gif" alt="">
        <form method="post" action="login.php">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Clave" required>
            <input type="submit" value="Entrar">
            <p><a href="/">Volver al inicio</a></p>
        </form>
    </div>
</body>
</html>