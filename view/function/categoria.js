function validar_form() {
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;

    if (nombre == "" || detalle == "" ) {
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
    registrarCategoria();
}
/*alert(nro_documento);*/
/*alert(".js successfull conexion");*/

if (document.querySelector('#frm_categoria')) {
    // evita que se envie el formulario
    let frm_categoria = document.querySelector('#frm_categoria');
    frm_categoria.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
    }
}
/*----------------------------------------------------------------------*/
async function registrarCategoria(params) {
    try {
        //capturar campos de formulario (HTML)
        const datos = new FormData(frm_categoria);
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        // validamos que json.status de igual true
        if (json.status) { //true
            alert(json.msg);
            document.getElementById('frm_categoria').reset();
        } else {
            alert(json.msg);
        }

    } catch (error) {
        console.log("Error al registrar Categoria:" + error);
    }
}