<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>login</title>
  <style>
    body {
      background-image: url('https://i.pinimg.com/originals/01/09/37/0109373718318dbe77401316e87cdc4d.gif');
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
      border: 1px solid #ccc;
      border-radius: 8px;
      text-align: center;
      font-family: sans-serif;
      background-color: transparent;
    }

    input {
      display: block;
      width: 90%;
      margin: 10px auto;
      padding: 8px;


    }

    button {
      background: linear-gradient(rgb(118, 207, 95), rgb(238, 252, 252));
      width: 200px;
      height: 40px;
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
      <center> <img src="https://arrobamedellin.edu.co/wp-content/uploads/2021/03/arrobita-v2.gif" alt="" width="250px" height="200px" </center>
        <input type="text" placeholder="Usuario" id="User" name="username">
        <input type="tex" placeholder="Contraseña" id="Contraseña" name="password">
        <button type="button" placeholder="Ingresar" onclick="iniciar_sesion();">Ingresar </button>
    </form>
    <div class="forgot">
      <a href="#">¿Olvidaste tu contraseña?</a>
    </div>
  </div>
  <script src="<?= BASE_URL; ?>view/function/user.js"></script>
</body>

</html>