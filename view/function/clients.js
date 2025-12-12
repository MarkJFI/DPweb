// clients.js - funciones para listar, editar, crear, actualizar y eliminar clientes

function validar_form(tipo) {
  const fields = ['nro_identidad', 'razon_social', 'telefono', 'correo', 'departamento', 'provincia', 'distrito', 'cod_postal', 'direccion', 'rol'];
  for (let f of fields) {
    const el = document.getElementById(f);
    if (!el || el.value.trim() === '') {
      alert('Error: campos vacíos o faltantes');
      return;
    }
  }
  if (tipo === 'nuevo') return registrarCliente();
  if (tipo === 'actualizar') return actualizarCliente();
}

// vincular formularios
if (document.querySelector('#frm_client')) {
  document.querySelector('#frm_client').addEventListener('submit', function (e) {
    e.preventDefault(); validar_form('nuevo');
  });
}
if (document.querySelector('#frm_user')) {
  document.querySelector('#frm_user').addEventListener('submit', function (e) {
    e.preventDefault(); validar_form('nuevo');
  });
}

async function registrarCliente() {
  try {
    const form = document.querySelector('#frm_client') || document.querySelector('#frm_user');
    if (!form) return console.warn('Formulario no encontrado para registrar cliente');
    const datos = new FormData(form);
    // Forzar rol cliente por seguridad
    if (datos.has('rol')) datos.set('rol', 'Cliente'); else datos.append('rol', 'Cliente');
    const res = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
      method: 'POST', body: datos
    });
    const json = await res.json();
    alert(json.msg);
    if (json.status) {
      form.reset();
      // si estamos en página de creación redirigir a lista
      if (window.location.pathname.indexOf('new-client') !== -1 || window.location.pathname.indexOf('new-clients') !== -1) {
        window.location.href = base_url + 'clients';
      } else if (document.getElementById('content_clients')) {
        view_clients();
      }
    }
  } catch (e) {
    console.error('Error al registrar cliente:', e);
  }
}

async function view_clients() {
  try {
    const res = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_clients', { method: 'POST' });
    const json = await res.json();
    const cont = document.getElementById('content_clients');
    if (!cont) return;
    cont.innerHTML = '';

    if (json.status && Array.isArray(json.data)) {
      let i = 1;
      json.data.forEach(item => {

        // Indicador visual del estado verde y rojo
        const estadoHtml = item.estado == 1
          ? '<span title="Activo" style="display:inline-block;width:16px;height:16px;background:#198754;border-radius:50%;"></span>'
          : '<span title="Inactivo" style="display:inline-block;width:16px;height:16px;background:#dc3545;border-radius:50%;"></span>';

        const tr = document.createElement('tr');
        tr.id = 'fila' + item.id;
        tr.className = 'filas_tabla';

        tr.innerHTML = `
          <td>${i}</td>
          <td>${item.nro_identidad}</td>
          <td>${item.razon_social}</td>
          <td>${item.correo}</td>
          <td>${item.rol}</td>
          <td class="text-center">${estadoHtml}</td>
          <td>
            <a href="${base_url}edit-client/${item.id}" 
               class="btn btn-primary btn-sm rounded-pill">
              <i class="bi bi-pencil-square"></i> Editar
            </a>
            <button class="btn btn-eliminar btn-danger btn-sm rounded-pill ms-1" 
                    data-id="${item.id}" 
                    style="background:#dc3545;">
              <i class="bi bi-trash"></i> Eliminar
            </button>
          </td>
        `;

        cont.appendChild(tr);
        i++;
      });

      // Listeners para eliminar
      cont.querySelectorAll('button[data-id]').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.getAttribute('data-id');
          if (confirm('¿Desea eliminar este registro?')) eliminar(id);
        });
      });
    }

  } catch (e) {
    console.error('Error al obtener clientes:', e);
  }
}


if (document.getElementById('content_clients')) view_clients();

async function edit_client() {
  try {
    const id_persona = document.getElementById('id_persona') ? document.getElementById('id_persona').value : null;
    if (!id_persona) return console.warn('id_persona no encontrado');
    const datos = new FormData(); datos.append('id_persona', id_persona);
    const res = await fetch(base_url + 'control/UsuarioController.php?tipo=ver', { method: 'POST', body: datos });
    const json = await res.json();
    if (!json.status) { alert(json.msg); return; }
    const data = json.data;
    ['nro_identidad', 'razon_social', 'telefono', 'correo', 'departamento', 'provincia', 'distrito', 'cod_postal', 'direccion', 'rol'].forEach(k => {
      const el = document.getElementById(k);
      if (el && data[k] !== undefined) el.value = data[k];
    });
  } catch (e) { console.error('Error en edit_client:', e); }
}

if (document.querySelector('#frm_edit_client')) {
  document.querySelector('#frm_edit_client').addEventListener('submit', function (e) {
    e.preventDefault(); validar_form('actualizar');
  });
}

async function actualizarCliente() {
  try {
    const form = document.getElementById('frm_edit_client');
    if (!form) return console.warn('Formulario de edición no encontrado');
    const datos = new FormData(form);
    const res = await fetch(base_url + 'control/UsuarioController.php?tipo=actualizar', { method: 'POST', body: datos });
    const json = await res.json();
    alert(json.msg);
    if (json.status) window.location.href = base_url + 'clients';
  } catch (e) { console.error('Error al actualizar cliente:', e); }
}

async function eliminar(id) {
  try {
    const datos = new FormData(); datos.append('id', id);
    const res = await fetch(base_url + 'control/UsuarioController.php?tipo=eliminar', { method: 'POST', body: datos });
    const json = await res.json();
    alert(json.msg);
    if (json.status) {
      if (document.getElementById('content_clients')) view_clients(); else window.location.href = base_url + 'clients';
    }
  } catch (e) { console.error('Error al eliminar:', e); }
}
