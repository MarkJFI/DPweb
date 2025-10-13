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
    let respuesta = await fetch(
      base_url + "control/ClientsController.php?tipo=registrar",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    let json = await respuesta.json();
    if (json.status) {
      alert(json.msg);
      // Obtener rol desde el form data (puede ser Cliente o Proveedor)
      const rol = datos.get('rol') || '';
      document.getElementById("frm_user").reset();
      // Redirigir a la lista correspondiente si es proveedor o cliente
      if (rol.toLowerCase() === 'proveedor') {
        window.location.href = base_url + 'proveedor-lista';
        return;
      }
      // Por defecto ir a la lista de clientes
      window.location.href = base_url + 'clientes';
      return;
    } else {
      alert(json.msg);
    }
  } catch (e) {
    console.log("Error al registrar:" + e);
  }
}

async function view_clients() {
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
    // limpiar contenido previo
    contenedor.innerHTML = '';
    if (json.status) {
      let cont = 1;
  json.data.forEach((item) => {
  const estado = item.estado == 1 ? 'activo' : 'inactivo';
        const nueva_fila = document.createElement("tr");
        nueva_fila.id = `fila${item.id}`;
        nueva_fila.className = "filas_tabla";
        nueva_fila.innerHTML = `
              <td>${cont}</td>
              <td>${item.nro_identidad}</td>
              <td>${item.razon_social}</td>
              <td>${item.correo}</td>
              <td>${estado}</td>
              <td>
                <a href="${base_url}edit-client/${item.id}" class="btn btn-primary btn-sm">Editar</a>
                <a href="#" onclick="eliminar_cp(${item.id}); return false;" class="btn btn-danger btn-sm ms-1">Eliminar</a>
              </td>
        `;
        cont++;
        contenedor.appendChild(nueva_fila);
      });
    }
  } catch (error) {
    console.log("error en mostrar clientes " + error);
  }
}

async function view_proveedores() {
  try {
    let respuesta = await fetch(
      base_url + "control/ClientsController.php?tipo=ver_proveedores",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
      }
    );
    json = await respuesta.json();
    const contenedor = document.getElementById("content_proveedores");
    contenedor.innerHTML = '';
    if (json.status) {
      let cont = 1;
      json.data.forEach((item) => {
        const estado = item.estado == 1 ? "activo" : "inactivo";
        const nueva_fila = document.createElement("tr");
        nueva_fila.id = `fila${item.id}`;
        nueva_fila.className = "filas_tabla";
        nueva_fila.innerHTML = `
              <td>${cont}</td>
              <td>${item.nro_identidad}</td>
              <td>${item.razon_social}</td>
              <td>${item.correo}</td>
              <td>${estado}</td>
              <td>
                <a href="${base_url}edit-client/${item.id}" class="btn btn-primary btn-sm">Editar</a>
                <a href="#" onclick="eliminar_cp(${item.id}); return false;" class="btn btn-danger btn-sm ms-1">Eliminar</a>
              </td>
        `;
        cont++;
        contenedor.appendChild(nueva_fila);
      });
    }
  } catch (error) {
    console.log("error en mostrar proveedores " + error);
  }
}

if (document.getElementById("content_clients")) {
  view_clients();
}
if (document.getElementById("content_proveedores")) {
  view_proveedores();
}

async function edit_client() {
  try {
    let id_persona = document.getElementById("id_persona").value;
    const datos = new FormData();
    datos.append("id_persona", id_persona);

    let respuesta = await fetch(
      base_url + "control/ClientsController.php?tipo=ver",
      {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        body: datos,
      }
    );
    let json = await respuesta.json();
    if (!json.status) {
      alert(json.msg);
      return;
    }
    document.getElementById("nro_identidad").value = json.data.nro_identidad;
    document.getElementById("razon_social").value = json.data.razon_social;
    document.getElementById("telefono").value = json.data.telefono;
    document.getElementById("correo").value = json.data.correo;
    document.getElementById("departamento").value = json.data.departamento;
    document.getElementById("provincia").value = json.data.provincia;
    document.getElementById("distrito").value = json.data.distrito;
    document.getElementById("cod_postal").value = json.data.cod_postal;
    document.getElementById("direccion").value = json.data.direccion;
    document.getElementById("rol").value = json.data.rol;
  } catch (error) {
    console.log("oops, ocurrio un error" + error);
  }
}

if (document.querySelector("#frm_edit_client")) {
  let frm = document.querySelector("#frm_edit_client");
  frm.onsubmit = function (e) {
    e.preventDefault();
    validar_form_client("actualizar");
  };
}

async function actualizarClienteProveedor() {
  const datos = new FormData(document.querySelector("#frm_edit_client"));
  let respuesta = await fetch(
    base_url + "control/ClientsController.php?tipo=actualizar",
    {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      body: datos,
    }
  );
  let json = await respuesta.json();
  if (!json.status) {
    alert("Ooops, ocurrio un error al actualizar, intentelo nuevamente");
    console.log(json.msg);
    return;
  } else {
    alert(json.msg);
  }
}

async function eliminar_cp(id) {
  // Confirm before deleting
  if (!confirm('¿Desea eliminar este registro?')) return;
  try {
    const datos = new FormData();
    datos.append('id_persona', id);
    const respuesta = await fetch(base_url + 'control/ClientsController.php?tipo=eliminar', {
      method: 'POST',
      body: datos
    });
    const text = await respuesta.text();
    let json;
    try {
      json = JSON.parse(text);
    } catch (e) {
      console.error('Respuesta no JSON al eliminar:', text);
      alert('Error del servidor al eliminar. Revisa la consola.');
      return;
    }
    if (!json.status) {
      alert(json.msg || 'Error al eliminar');
      console.log('Eliminar respuesta:', json);
      return;
    }
    alert(json.msg || 'Eliminado');
    // remove row from table if present
    const fila = document.getElementById('fila' + id);
    if (fila) fila.remove();
  } catch (err) {
    console.error('Error al eliminar:', err);
    alert('Error de red al eliminar, revisa la consola');
  }
}
