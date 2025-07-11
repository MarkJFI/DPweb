
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
    if (nro_documento == "" || razon_social == "" || telefono == "" || correo == "" || departamento == "" || provincia == "" || distrito == "" || cod_postal == "" || direccion == "" || rol == "") {

        Swal.fire({
            icon: "error",
            title: "Error: campos vacios",
            text: "Por favor, complete",
            footer: '<a href="#">Why do I have this issue?</a>'
        });

        return;

    }
    registrarUsuario();
}

if (document.querySelector("#frm_user")) {
    //evitar que se envie el formulario
    let frm_user = document.querySelector("#frm_user");
    frm_user.onsubmit = function (e) {
        e.preventDefault();
        validar_form();

    }

}
async function registrarUsuario() {
    try {
        //capturar campos de formularios(HTML)
        const datos = new FormData(frm_user);
        //enviar datos al controlador
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        // validamos que json.status sea = true
        if (json.status == true) { //true
            alert(json.msg);
            document.getElementById('frm_user').reset();
        } else {
            alert(json.msg);
        }


    } catch (e) {
        console.log("Error al registrar Usuario:" + e);
    }

}
async function iniciar_sesion() {
    let usuario = document.getElementById("User").value;
    let password = document.getElementById("Contraseña").value;
    if (usuario == "" || password == "") {
        alert("Error, campos vacios");

    }
    try {
        const datos = new FormData(frm_login);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=iniciar_sesion', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
    } catch (error) {
        console.log(error);

    }

}