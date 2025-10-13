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
  let contenedor = document.getElementById("content_proveedores");
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
              <td>${estado}</td>
              <td>
                <a href="` +
          base_url +
          `edit-client/` +
          item.id +
          `" class="btn btn-primary btn-sm">Editar</a>
                <a href="#" onclick="if(confirm('¿Desea eliminar este registro?')) eliminar_cp(${item.id}); return false;" class="btn btn-danger btn-sm ms-1">Eliminar</a>
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
    // despues de actualizar volver a la lista
    window.location.href = base_url + 'clients';
  }
}

async function eliminar_cp(id) {
  let datos = new FormData();
  datos.append("id_persona", id);
  let respuesta = await fetch(
    base_url + "control/ClientsController.php?tipo=eliminar",
    {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      body: datos,
    }
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
}