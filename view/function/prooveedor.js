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
    if (nro_documento == "" || razon_social == "" || telefono == "" || correo == "" || departamento == "" || provincia == "" || distrito == "" || cod_postal == "" || direccion == "" || rol == "") {
        Swal.fire({
            title: "Error campos vacios!",
            icon: "Error",
            draggable: true
        });
        return;
    }
    if (tipo == "nuevo") {
        registrarProveedor();
    }
    if (tipo == "actualizar") {
        actualizarProveedor();
    }

}

if (document.querySelector('#frm_proveedor')) {
    // evita que se envie el formulario
    let frm_proveedor = document.querySelector('#frm_proveedor');
    frm_proveedor.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}
async function registrarProveedor() {
    try {
        //capturar campos de formulario (HTML)
        const formElem = document.getElementById('frm_proveedor');
        const datos = new FormData(formElem);
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        // validamos que json.status sea = True
        if (json.status) { //true
            // usar Swal si está disponible para mejor UX
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: json.msg,
                    icon: 'success',
                    allowOutsideClick: false
                }).then(() => {
                    // redirigir a new-proveedor después de confirmar
                    location.href = base_url + 'new-proveedor';
                });
            } else {
                alert(json.msg);
                // redirigir a new-proveedor
                location.href = base_url + 'new-proveedor';
            }
            formElem.reset();
        } else {
            alert(json.msg);
        }
    } catch (e) {
        console.log("Error al registrar Proveedor:" + e);
    }
}

async function view_proveedores() {
    try {
        const respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        const json = await respuesta.json();
        const contenidot = document.getElementById('content_proveedor');
        if (!contenidot) return;

        contenidot.innerHTML = '';

        if (json.status && Array.isArray(json.data)) {
            let cont = 1;
            json.data.forEach(usuario => {

                // Indicador visual del estado (círculo verde o rojo)
                const estadoHtml = usuario.estado == 1
                    ? '<span title="Activo" style="display:inline-block;width:16px;height:16px;background:#198754;border-radius:50%;"></span>'
                    : '<span title="Inactivo" style="display:inline-block;width:16px;height:16px;background:#dc3545;border-radius:50%;"></span>';

                // Crear fila
                const nueva_fila = document.createElement("tr");
                nueva_fila.id = "fila" + usuario.id;
                nueva_fila.className = "filas_tabla";

                // Contenido de la fila
                nueva_fila.innerHTML = `
          <td>${cont}</td>
          <td>${usuario.nro_identidad}</td>
          <td>${usuario.razon_social}</td>
          <td>${usuario.correo}</td>
          <td>${usuario.rol}</td>
          <td class="text-center">${estadoHtml}</td>
          <td>
            <a href="${base_url}edit-proveedor/${usuario.id}" 
               class="btn btn-primary btn-sm rounded-pill">
              <i class="bi bi-pencil-square"></i> Editar
            </a>
            <button onclick="fn_eliminar(${usuario.id});" 
                    class="btn btn-eliminar btn-danger btn-sm rounded-pill ms-1" 
                    style="background:#dc3545;">
              <i class="bi bi-trash"></i> Eliminar
            </button>
          </td>
        `;

                contenidot.appendChild(nueva_fila);
                cont++;
            });
        }

    } catch (e) {
        console.error('Error al mostrar proveedores:', e);
    }
}

// Ejecutar la función si existe la tabla
if (document.getElementById('content_proveedor')) {
    view_proveedores();
}


async function edit_proveedor() {
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
        console.log('oops, ocurrió un error ' + error);
    }
}
// si existe el formulario de edición de proveedor/cliente, prevenir envío
if (document.querySelector('#frm_edit_proveedor')) {
    let frm_edit_proveedor = document.querySelector('#frm_edit_proveedor');
    frm_edit_proveedor.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}

async function actualizarProveedor() {
    try {
        const form = document.getElementById('frm_edit_proveedor');
        const datos = new FormData(form);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (!json.status) {
            alert("Oooooops, ocurrio un error al actualizar, intentelo nuevamente");
            console.log(json.msg);
            return;
        } else {
            alert(json.msg);
            // volver a la lista de proveedores
            location.href = base_url + 'proveedor';
        }
    } catch (e) {
        console.log('Error al actualizar proveedor: ' + e);
    }
}
async function fn_eliminar(id) {
    if (window.confirm("Confirmar eliminar?")) {
        eliminar(id);
    }
}


// Eliminar proveedor
async function eliminar(id) {
    if (!confirm("¿Estás seguro de eliminar este usuario?")) return;
    try {
        const datos = new FormData();
        datos.append('id', id);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=eliminar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (!json.status) {
            alert(" Ocurrió un error al eliminar la persona. Inténtelo más tarde.");
            console.log(json.msg);
        } else {
            alert(" " + json.msg);
            if (document.getElementById('content_proveedor')) {
                document.getElementById('content_proveedor').innerHTML = '';
                view_proveedores();
            } else {
                location.href = base_url + 'proveedor';
            }
        }
    } catch (e) {
        console.log('Error al eliminar: ' + e);
    }
}
