async function view_users() {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_usuarios', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        const textoRespuesta = await respuesta.text();
        let json;
        try {
            json = JSON.parse(textoRespuesta);
        } catch (parseError) {
            console.error('Error al parsear JSON en view_users:', parseError);
            console.error('Respuesta recibida:', textoRespuesta);
            const contenido = document.getElementById('content_users');
            if (contenido) {
                contenido.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error al leer la respuesta del servidor.</td></tr>`;
            }
            return;
        }

        const contenido = document.getElementById('content_users');
        if (!contenido) {
            console.error("No se encontró el contenedor #content_users");
            return;
        }

        contenido.innerHTML = '';

        if (json.status && Array.isArray(json.data)) {
            let cont = 1;
            json.data.forEach(user => {
                let fila = document.createElement('tr');
                fila.id = `fila_user_${user.id}`;
                fila.innerHTML = `
                    <td>${cont++}</td>
                    <td>${user.nro_identidad}</td>
                    <td>${user.razon_social}</td>
                    <td>${user.correo}</td>
                    <td>${user.rol}</td>
                    <td><span class="badge bg-success">Activo</span></td>
                    <td>
                        <a href="${base_url}edit-user/${user.id}" class="btn btn-sm btn-primary">Editar</a>
                        <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${user.id})">Eliminar</button>
                    </td>
                `;
                contenido.appendChild(fila);
            });
        } else {
            contenido.innerHTML = `<tr><td colspan="7" class="text-center">No hay usuarios disponibles.</td></tr>`;
        }
    } catch (error) {
        console.error("Error al mostrar usuarios:", error);
        const contenido = document.getElementById('content_users');
        if (contenido) {
            contenido.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error al cargar los usuarios.</td></tr>`;
        }
    }
}

async function iniciar_sesion() {
    const datos = new FormData(document.getElementById('frm_login'));
    try {
        const respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=iniciar_sesion', {
            method: 'POST',
            body: datos
        });
        const json = await respuesta.json();
        if (json.status) {
            window.location.href = base_url + 'home';
        } else {
            alert(json.msg);
        }
    } catch (error) {
        console.error('Error al iniciar sesión:', error);
        alert('Error de conexión al intentar iniciar sesión.');
    }
}

async function registrarUsuario() {
    const datos = new FormData(document.getElementById('frm_user'));
    try {
        const respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
            method: 'POST',
            body: datos
        });
        const json = await respuesta.json();
        if (json.status) {
            alert(json.msg);
            document.getElementById('frm_user').reset();
        } else {
            alert(json.msg);
        }
    } catch (error) {
        console.error('Error al registrar usuario:', error);
        alert('Error de conexión al registrar.');
    }
}

async function eliminarUsuario(id) {
    if (!confirm('¿Está seguro de que desea eliminar este usuario?')) {
        return;
    }
    const datos = new FormData();
    datos.append('id_persona', id);
    try {
        const respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=eliminar', {
            method: 'POST',
            body: datos
        });
        const json = await respuesta.json();
        if (json.status) {
            alert(json.msg);
            document.getElementById(`fila_user_${id}`).remove();
        } else {
            alert(json.msg);
        }
    } catch (error) {
        console.error('Error al eliminar usuario:', error);
        alert('Error de conexión al eliminar.');
    }
}


// Ejecutar la función para ver usuarios si el contenedor existe
if (document.getElementById('content_users')) {
    view_users();
}

// Manejar el envío del formulario de registro de usuario
const frmUser = document.getElementById('frm_user');
if (frmUser) {
    frmUser.addEventListener('submit', (e) => {
        e.preventDefault();
        registrarUsuario();
    });
}