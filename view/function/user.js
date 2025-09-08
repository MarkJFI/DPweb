function validar_form(tipo) {
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

    Swal.fire({
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
    });

    if (tipo == "nuevo") {
        registrarUsuario();
    }
    if (tipo == "actualizar") {
        actualizarUsuario();
    }
  

}



/*alert(nro_documento);*/
/*alert(".js successfull conexion");*/

if (document.querySelector('#frm_user')) {
    // evita que se envie el formulario
    let frm_user = document.querySelector('#frm_user');
    frm_user.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
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
                    <a href="${base_url}edit-user/${user.id}" class="btn btn-secondary">Editar</a>
            <button data-id="${user.id}" class="btn btn-eliminar btn-warning">Eliminar</button>
                </td>
            `;
            content_users.appendChild(fila);
        });

        // ELIMINAR
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', async function () {
                if (confirm('¿Está seguro de eliminar este usuario?')) {
                    const datos = new FormData();
                    datos.append('id', this.getAttribute('data-id'));
                    let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=eliminar', {
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: datos
                    });
                    let json = await respuesta.json();
                    alert(json.msg);
                    if (json.status) {
                        view_users();
                    }
                }
            });
        });



    } catch (error) {
        console.log(error)
    }
}


if (document.getElementById('content_users')) {
    view_users();
}

async function edit_user() {
    try {
        let id_persona = document.getElementById('id_persona').value;
        const datos = new FormData();
        datos.append('id_persona', id_persona);

        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        json = await respuesta.json();
        if (!json.status) {
            alert(json.msg);
            return;
        }
        document.getElementById('nro_identidad').value = json.data.nro_identidad;
        document.getElementById('razon_social').value = json.data.razon_social;
        document.getElementById('telefono').value = json.data.telefono;
        document.getElementById('correo').value = json.data.correo;
        document.getElementById('departamento').value = json.data.departamento;
        document.getElementById('provincia').value = json.data.provincia;
        document.getElementById('distrito').value = json.data.distrito;
        document.getElementById('cod_postal').value = json.data.cod_postal;
        document.getElementById('direccion').value = json.data.direccion;
        document.getElementById('rol').value = json.data.rol;



    } catch (error) {
        console.log('oops ocurrió un error' + error);
    }

}
// actualizar////////////////////////////////////
if (document.querySelector('#frm_edit_user')) {
    //evita que se envie el formulario
    let frm_user = document.querySelector('#frm_edit_user');
    frm_user.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}
async function actualizarUsuario() {
    const datos = new FormData(frm_edit_user);
    let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=actualizar', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        body: datos
    });
    json = await respuesta.json();
    if (!json.status) {
        alert("Ops, ocurrio un error al actualizar, contacte con el administrador");
        console.log(json.msg);
        return;
    } else {
        alert(json.msg);
    }
}



