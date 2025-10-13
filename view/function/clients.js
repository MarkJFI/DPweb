<<<<<<< HEAD
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
        registrarCliente();
    }
    if (tipo == "actualizar") {
        actualizarCliente();
    }

}

if (document.querySelector('#frm_client')) {
    // evita que se envie el formulario
    let frm_client = document.querySelector('#frm_client');
    frm_client.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}
async function registrarCliente() {
    try {
        //capturar campos de formulario (HTML)
        const formElem = document.getElementById('frm_client');
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
                    // redirigir a new-client después de confirmar
                    location.href = base_url + 'new-client';
                });
            } else {
                alert(json.msg);
                // redirigir a new-client
                location.href = base_url + 'new-client';
            }
            formElem.reset();
        } else {
            alert(json.msg);
        }
    } catch (e) {
        console.log("Error al registrar Cliente:" + e);
=======
function validar_form_client(tipo) {
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
  if (
    nro_documento == "" ||
    razon_social == "" ||
    telefono == "" ||
    correo == "" ||
    departamento == "" ||
    provincia == "" ||
    distrito == "" ||
    cod_postal == "" ||
    direccion == "" ||
    rol == ""
  ) {
    alert("Error, campos vacios");
    return;
  }
  if (tipo == "nuevo") {
    registrarClienteProveedor();
  }
  if (tipo == "actualizar") {
    actualizarClienteProveedor();
  }
}

if (document.querySelector("#frm_user")) {
  let frm = document.querySelector("#frm_user");
  frm.onsubmit = function (e) {
    e.preventDefault();
    validar_form_client("nuevo");
  };
}

async function registrarClienteProveedor() {
  try {
    const datos = new FormData(document.querySelector("#frm_user"));
    // Forzar rol Cliente desde el cliente también (defensa en profundidad)
    if (datos.has('rol')) {
      datos.set('rol', 'Cliente');
    } else {
      datos.append('rol', 'Cliente');
    }
    let respuesta = await fetch(
      base_url + "control/ClientsController.php?tipo=registrar",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    // Intentar parsear JSON, si falla mostrar texto crudo para depuración
    let text = await respuesta.text();
    let json;
    try {
      json = JSON.parse(text);
    } catch (e) {
      alert('Respuesta inválida del servidor al registrar (no JSON). Revisa la consola y el log de PHP.');
      console.error('Respuesta cruda registrarClienteProveedor:', text);
      return;
    }
    if (json.status) {
      alert(json.msg);
      // Si estamos en el formulario de creación (new-clients) redirigimos a la lista de clients para ver el nuevo registro
      if (window.location.pathname.indexOf('new-clients') !== -1 || window.location.pathname.indexOf('new-user') !== -1) {
        window.location.href = base_url + 'clients';
        return;
      }
      // resetear formulario
      const frm = document.getElementById("frm_user");
      if (frm) frm.reset();

      // Si la lista de clients está visible, insertar la nueva fila usando los datos devueltos por el servidor
      const contenedor = document.getElementById("content_clients");
      if (contenedor) {
        if (json.data) {
          // calcular número de fila (conteo actual + 1)
          const currentCount = contenedor.querySelectorAll('tr').length;
          const cont = currentCount + 1;
          const item = json.data;
          const estado = item.estado == 1 ? 'activo' : 'inactivo';
          const nueva_fila = document.createElement('tr');
          nueva_fila.id = 'fila' + item.id;
          nueva_fila.className = 'filas_tabla';
          nueva_fila.innerHTML = `
              <td>${cont}</td>
              <td>${item.nro_identidad}</td>
              <td>${item.razon_social}</td>
              <td>${item.correo}</td>
              <td>${item.rol}</td>
              <td>${estado}</td>
              <td>
                <a href="${base_url}edit-clientes/${item.id}" class="btn btn-primary btn-sm">Editar</a>
                <a href="#" onclick="if(confirm('¿Desea eliminar este registro?')) eliminar_cp(${item.id}); return false;" class="btn btn-danger btn-sm ms-1">Eliminar</a>
              </td>
          `;
          contenedor.appendChild(nueva_fila);
        } else {
          // fallback: refrescar la lista completa
          view_clients();
        }
      }
    } else {
      alert(json.msg);
>>>>>>> c3748858bd5ae4169b7dea2a5a5343ac4e2287b1
    }
}

async function view_clients() {
<<<<<<< HEAD
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_clients', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        json = await respuesta.json();
        contenidot = document.getElementById('content_clients');
        if (json.status) {
            let cont = 1;
            json.data.forEach(usuario => {
                if (usuario.estado == 1) {
                    estado = "activo";
                } else {
                    estado = "inactivo";
                }
                let nueva_fila = document.createElement("tr");
                nueva_fila.id = "fila" + usuario.id;
                nueva_fila.className = "filas_tabla";
                nueva_fila.innerHTML = `
                            <td>${cont}</td>
                            <td>${usuario.nro_identidad}</td>
                            <td>${usuario.razon_social}</td>
                            <td>${usuario.correo}</td>
                            <td>${usuario.rol}</td>
                            <td>${estado}</td>
                            <td>
                                <a href="`+ base_url + `edit-client/` + usuario.id + `">Editar</a>
                                <button class="btn btn-danger" onclick="fn_eliminar(` + usuario.id + `);">Eliminar</button>
                            </td>
                `;
                cont++;
                contenidot.appendChild(nueva_fila);
            });
        }
    } catch (error) {
        console.log('error en mostrar usuario ' + e);
=======
  try {
    let respuesta = await fetch(
      base_url + "control/ClientsController.php?tipo=ver_clientes",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
      }
    );
    json = await respuesta.json();
    const contenedor = document.getElementById("content_clients");
    // Limpiar contenido previo
    contenedor.innerHTML = "";
    if (json.status) {
      let cont = 1;
      json.data.forEach((item) => {
        let estado = item.estado == 1 ? "activo" : "inactivo";
  let nueva_fila = document.createElement("tr");
        nueva_fila.id = "fila" + item.id;
        nueva_fila.className = "filas_tabla";
        nueva_fila.innerHTML = `
              <td>${cont}</td>
              <td>${item.nro_identidad}</td>
              <td>${item.razon_social}</td>
              <td>${item.correo}</td>
              <td>${item.rol}</td>
              <td>${estado}</td>
              <td>
                <a href="${base_url}edit-clientes/${item.id}" class="btn btn-primary btn-sm">Editar</a>
                <a href="#" onclick="if(confirm('¿Desea eliminar este registro?')) eliminar_cp(${item.id}); return false;" class="btn btn-danger btn-sm ms-1">Eliminar</a>
              </td>
        `;
        cont++;
        contenedor.appendChild(nueva_fila);
      });
>>>>>>> c3748858bd5ae4169b7dea2a5a5343ac4e2287b1
    }
}
if (document.getElementById('content_clients')) {
    view_clients();
}

async function edit_client() {
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
// si existe el formulario de edición de cliente, prevenir envio y usar validar_form
if (document.querySelector('#frm_edit_client')) {
    let frm_edit_client = document.querySelector('#frm_edit_client');
    frm_edit_client.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}

async function actualizarCliente() {
    try {
        const form = document.getElementById('frm_edit_client');
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
            // después de actualizar, volver a la lista de clientes
            location.href = base_url + 'clients';
        }
    } catch (e) {
        console.log('Error al actualizar cliente: ' + e);
    }
<<<<<<< HEAD
=======
  );
  let json = await respuesta.json();
  if (!json.status) {
    alert("Ooops, ocurrio un error al actualizar, intentelo nuevamente");
    console.log(json.msg);
    return;
  } else {
    alert(json.msg);
    // despues de actualizar volver a la lista
    window.location.href = base_url + 'clients';
  }
>>>>>>> c3748858bd5ae4169b7dea2a5a5343ac4e2287b1
}
async function fn_eliminar(id) {
    if (window.confirm("Confirmar eliminar?")) {
        eliminar(id);
    }
}
async function eliminar(id) {
    try {
        const datos = new FormData();
        // el controlador espera el campo 'id'
        datos.append('id', id);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=eliminar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (!json.status) {
            alert("Oooooops, ocurrio un error al eliminar persona, intentelo mas tarde");
            console.log(json.msg);
            return;
        } else {
            alert(json.msg);
            // si estamos en la lista de clients, recargar la lista
            if (document.getElementById('content_clients')) {
                // limpiar tabla
                document.getElementById('content_clients').innerHTML = '';
                view_clients();
            } else {
                // redirigir a la lista en caso contrario
                location.href = base_url + 'clients';
            }
        }
    } catch (e) {
        console.log('Error al eliminar: ' + e);
    }
<<<<<<< HEAD
=======
  );
  json = await respuesta.json();
  if (!json.status) {
    alert("Ooooops, ocurrio un error al eliminar, intentelo mas tarde");
    console.log(json.msg);
    return;
  } else {
    alert(json.msg);
    // refrescar la lista en vez de recargar toda la pagina
    if (document.getElementById("content_clients")) {
      view_clients();
    } else {
      location.reload();
    }
  }
>>>>>>> c3748858bd5ae4169b7dea2a5a5343ac4e2287b1
}