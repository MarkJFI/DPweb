<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>login</title>
  <style>
    body {
      background-image: url('https://img.freepik.com/fotos-premium/manos-mujer-hermosa-telefono-tarjeta-credito-mujer-joven-bolsas-compras_217236-7963.jpg');
      background-repeat: no-repeat;
      background-size: cover;
    }

    h3 {
      color: white;
    }

    .login {

      width: 250px;
      margin: 100px auto;
      padding: 20px;
      border: 2px solid #e7dcdcff;
      border-radius: 100px;
      text-align: center;
      font-family: sans-serif;
      background-color: transparent;
    }

    input {
      display: block;
      width: 90%;
      margin: 10px auto;
      padding: 8px;
      border-radius: 20px;


    }

    button {
      background: linear-gradient(rgba(230, 175, 23, 1), rgb(238, 252, 252));
      width: 150px;
      height: 35px;
      border-radius: 20px;
    }
  </style>
  <script>
    const base_url = '<?= BASE_URL; ?>';
  </script>
</head>

<body>
  <div class="login">
    <h3>Iniciar Sesion</h3>
    <form id="frm_login">
      <center> <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjopiHq_3AR78yIAK34Wd5y4dUbvEBUHTs-DbnZnaqA8v8-nyFhVMCeLSVm0KF4VrFrKZrwl4pdXfiHhaYjaj1Spc3XbhuhCzJS8-OOLfb7m4kvuUr_fK15GSNDcobHeUkRNv8NuCpGkfyA/s900/Las-Grandes-Empresas-no-Despengan-en-La-Venta-Online.gif" alt="" width="250px" height="200px" </center>
        <input type="text" placeholder="Usuario" id="User" name="username">
        <input type="tex" placeholder="Contraseña" id="Contraseña" name="password">
        <button type="button" placeholder="Ingresar" onclick="iniciar_sesion();">Ingresar</button>
    </form>
    <br>
    <div class="forgot">
      <a href="#">¿Olvidaste tu contraseña?</a>
    </div>
  </div>
  <script src="<?= BASE_URL; ?>view/function/user.js"></script>
</body>

</html>