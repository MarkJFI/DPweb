function validar_form(tipo) {
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;

    if (nombre == "" || detalle == "") {
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
        registrarCategoria();
    }
    if (tipo == "actualizar") {
        actualizarCategoria();
    }
}

let frm_category = document.querySelector('#frm_category'); 
if (frm_category) {
    frm_category.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}

async function registrarCategoria() {
    try {
        const datos = new FormData(frm_category);
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            alert(json.msg);
            document.getElementById('frm_category').reset();
        } else {
            alert(json.msg);
        }
    } catch (error) {
        console.log("Error al registrar Categoría:" + error);
    }
}

async function view_categories() {
    try {
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
        });

        let json = await respuesta.json();
        let content_categories = document.getElementById('content_categories');
        content_categories.innerHTML = '';

        json.forEach((category, index) => {
            let fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${index + 1}</td>
                <td>${category.nombre}</td>
                <td>${category.detalle}</td>
                <td>
                    <a href="${base_url}edit-categoria/${category.id}" class="btn btn-primary btn-sm rounded-pill">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <button data-id="${category.id}" class="btn btn-eliminar btn-danger btn-sm rounded-pill" style="background:#dc3545;">
                        Eliminar
                    </button>
                </td>
            `;
            content_categories.appendChild(fila);
        });

        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', async function () {
                if (confirm('¿Está seguro de eliminar esta categoría?')) {
                    const datos = new FormData();
                    datos.append('id', this.getAttribute('data-id'));
                    let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=eliminar', {
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: datos
                    });
                    let json = await respuesta.json();
                    alert(json.msg);
                    if (json.status) {
                        view_categories();
                    }
                }
            });
        });
    } catch (error) {
        console.log(error);
    }
}

if (document.getElementById('content_categories')) {
    view_categories();
}

async function edit_categoria() {
    try {
        let id_categoria = document.getElementById('id_categoria').value;
        const datos = new FormData();
        datos.append('id_categoria', id_categoria);

        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver', {
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
        document.getElementById('nombre').value = json.data.nombre;
        document.getElementById('detalle').value = json.data.detalle;
    } catch (error) {
        console.log('oops ocurrió un error' + error);
    }
}

let frm_edit_category = document.querySelector('#frm_edit_category');
if (frm_edit_category) {
    frm_edit_category.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}

async function actualizarCategoria() {
    const datos = new FormData(frm_edit_category);
    let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=actualizar', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        body: datos
    });
    json = await respuesta.json();
    if (!json.status) {
        alert("Ops, ocurrió un error al actualizar, contacte con el administrador");
        console.log(json.msg);
        return;
    } else {
        alert(json.msg);
    }
}