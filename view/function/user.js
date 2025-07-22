function validar_form() {
    let nro_documento = document.getElementById("nro_identidad").value;
    let razon_social = document.getElementById("razon_social").value;
    let telefono = document.getElementById("telefono").value;
    let correo = document.getElementById("correo").value;
    let departamento = document.getElementById("departamento").value;
    let provincia = document.getElementById("provincia").value;
    let distrito = document.getElementById("distrito").value;
    let cod_postal = document.getElementById("cod_postal").value;
    let direccion = document.getElementById("direccion").value;
    let rol = document.getElementById("rol").value;

    if (nro_identidad == "" || razon_social == "" || telefono == "" || correo == "" || departamento == "" || provincia == "" || distrito == "" || cod_postal == "" || direccion == "" || rol == "") {
        alert("ERROR: Campos vacios");
        return;
    }

    /*Swal.fire({
        title: '¡Procedemos a Registrar Tus datos!',
        text: 'Espere Por Favor.',
        icon: 'success',
        confirmButtonText: 'Aceptar',
        background: '#1e1e2f',
        color: '#ffffff',
        confirmButtonColor: '#ff6f61',
        iconColor: '#00ffcc',
        customClass: {
            popup: 'rounded-pill shadow border border-light'
        }
    });*/
    registrarUsuario();
}
/*alert(nro_documento);*/
/*alert(".js successfull conexion");*/

if (document.querySelector('#frm_user')) {
    // evita que se envie el formulario
    let frm_user = document.querySelector('#frm_user');
    frm_user.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
    }
}
/*----------------------------------------------------------------------*/
async function registrarUsuario() {
    try {
        //capturar campos de formulario (HTML)
        const datos = new FormData(frm_user);
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        // validamos que json.status de igual true
        if (json.status) { //true
            alert(json.msg);
            document.getElementById('frm_user').reset();
        } else {
            alert(json.msg);
        }


    } catch (error) {
        console.log("Error al registrar Usuario:" + error);
    }
}


async function iniciar_sesion() {
    let usuario = document.getElementById("usuario").value;
    let password = document.getElementById("password").value;
    if (usuario == "" || password == "") {
        alert("Error, campos vacios!");
        return;

    }
    try {
        const datos = new FormData(frm_login);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=iniciar_sesion', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();

        if (json.status) {
            location.replace(base_url + 'new-user');
        } else {
            alert(json.msg);
        }

    } catch (error) {
        console.log(error);
    }
}
async function view_users() {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_usuarios', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
        });
        let json = await respuesta.json();
        let content_users = document.getElementById('content_users');
        content_users.innerHTML = '';

        json.forEach((user, index) => {
            let fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${index + 1}</td>
                <td>${user.nro_identidad}</td>
                <td>${user.razon_social}</td>
                <td>${user.correo}</td>
                <td>${user.rol}</td>
                <td>${user.estado}</td>
                <td>
                    <a href="`+ base_url+`edit_user/`+user.id+`">Editar</a>
                </td>
            `;
            content_users.appendChild(fila);
        });

    } catch (error) {
        console.log(error)
    }
}


if (document.getElementById('content_users')) {
    view_users();
}


