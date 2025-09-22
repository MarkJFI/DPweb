function validar_form() {
    let nombre = document.getElementById("nombre")?.value || '';
    let detalle = document.getElementById("detalle")?.value || '';

    if (nombre == "" || detalle == "") {
        alert("ERROR: Campos vacios");
        return;
    }
    registrarCategoria();
}
if (document.querySelector('#frm_categoria')) {
    // evita que se envie el formulario
    let frm_categoria = document.querySelector('#frm_categoria');
    frm_categoria.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
    }
}
async function registrarCategoria() {
    try {
        const frm_categoria = document.querySelector('#frm_categoria');
        const datos = new FormData(frm_categoria);
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            alert(json.msg);
            window.location.href = base_url + 'categoria-lista';
        } else {
            alert(json.msg);
        }

    } catch (error) {
        console.log("Error al registrar Categoria:" + error);
    }
}

async function view_categorias() {
    try {
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        const json = await respuesta.json();
        const cont = document.getElementById('content_categorias');
        if (!cont) return;
        if (json && json.status) {
            let html = '';
            if (Array.isArray(json.data) && json.data.length > 0) {
                json.data.forEach((cat, idx) => {
                    html += `<tr>
                        <td>${idx + 1}</td>
                        <td>${cat.nombre || ''}</td>
                        <td>${cat.detalle || ''}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="${base_url}categoria-edit/${cat.id}">Editar</a>
                            <button class="btn btn-danger btn-sm" onclick="eliminar_categoria(${cat.id})">Eliminar</button>
                        </td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="4" class="text-center">No hay categorías registradas</td></tr>';
            }
            cont.innerHTML = html;
        } else {
            cont.innerHTML = '<tr><td colspan="4" class="text-center">Error al obtener categorías</td></tr>';
        }
    } catch (e) {
        const cont = document.getElementById('content_categorias');
        if (cont) cont.innerHTML = '<tr><td colspan="4" class="text-center">Error al cargar categorías</td></tr>';
        console.log('Error al listar categorias', e);
    }
}
if (document.getElementById('content_categorias')) {
    view_categorias();
}

async function edit_categoria() {
    try {
        const id = document.getElementById('id_categoria')?.value;
        if (!id) return;
        const fd = new FormData();
        fd.append('id_categoria', id);
        let resp = await fetch(base_url + 'control/CategoriaController.php?tipo=ver', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: fd
        });
        const json = await resp.json();
        if (json.status) {
            document.getElementById('nombre').value = json.data.nombre;
            document.getElementById('detalle').value = json.data.detalle;
        }
    } catch (e) {
        console.log('Error al cargar categoria', e);
    }
}

if (document.querySelector('#frm_edit_categorie')) {
    const frm = document.querySelector('#frm_edit_categorie');
    frm.onsubmit = async function(e){
        e.preventDefault();
        const datos = new FormData(frm);
        let resp = await fetch(base_url + 'control/CategoriaController.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        const json = await resp.json();
        if (json.status) {
            alert(json.msg);
            window.location.href = base_url + 'categoria-lista';
        } else {
            alert(json.msg || 'Error al actualizar');
        }
    }
}

async function eliminar_categoria(id){
    if (!confirm('¿Eliminar categoría?')) return;
    const fd = new FormData();
    fd.append('id_categoria', id);
    let resp = await fetch(base_url + 'control/CategoriaController.php?tipo=eliminar', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        body: fd
    });
    const json = await resp.json();
    if (json.status) {
        alert(json.msg);
        view_categorias();
    } else {
        alert(json.msg || 'Error al eliminar');
    }
}