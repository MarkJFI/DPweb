<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>login</title>
  <style>
    body {
      background-image: url('https://i.pinimg.com/736x/55/74/54/557454f9d762c6b6c9efdaec66b4dbb9.jpg');
      background-repeat: no-repeat;
      background-size: cover;
    }

    h3 {
      color: white;
    }

    .login {

      width: 350px;
      margin: 250px auto;
      padding: 50px;
      border: 1px solid #131212ff;
      border-radius: 100px;
      text-align: center;
      font-family: sans-serif;
      background-color: transparent;
    }

    input {
      display: block;
      width: 100%;
      margin: 20px auto;
      padding: 10px;
      border-radius: 20px;


    }

    button {
      background: #78f503cb;
      width: 160px;
      height: 40px;
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
        <input type="password" placeholder="Contraseña" id="Contraseña" name="password">
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