async function view_proveedores() {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        // Leer la respuesta como texto primero para diagnosticar errores de JSON
        const textoRespuesta = await respuesta.text();
        console.log("Respuesta del servidor (raw):", textoRespuesta);

        let json;
        try {
            json = JSON.parse(textoRespuesta);
        } catch (parseError) {
            console.error('Error al parsear JSON en view_proveedores:', parseError);
            console.error('Respuesta recibida:', textoRespuesta);
            let contenido = document.getElementById('content_proveedor');
            if (contenido) {
                contenido.innerHTML = `
                    <tr><td colspan="7" class="text-center text-danger">Error: El servidor no devolvió una respuesta JSON válida.</td></tr>
                `;
            }
            return; // Detener la ejecución si el JSON es inválido
        }
        console.log("Datos recibidos:", json);

        let contenido = document.getElementById('content_proveedor');
        if (!contenido) {
            console.error("No se encontró el contenedor #content_proveedor");
            return;
        }

        contenido.innerHTML = '';

        if (json.status && Array.isArray(json.data)) {
            json.data.forEach(proveedor => {
                let fila = document.createElement('tr');
                // Ajustado para usar 'razon_social' y 'nro_identidad' del modelo Persona
                fila.innerHTML = `
                    <td>${proveedor.id}</td>
                    <td>${proveedor.nro_identidad}</td>
                    <td>${proveedor.razon_social}</td>
                    <td>${proveedor.correo}</td>
                    <td>${proveedor.rol}</td>
                    <td><span class="badge bg-success">Activo</span></td>
                    <td>
                        <a href="${base_url}edit-proveedor/${proveedor.id}" class="btn btn-sm btn-primary">Editar</a>
                        <button class="btn btn-sm btn-danger" onclick="eliminarProveedor(${proveedor.id})">Eliminar</button>
                    </td>
                `;
                contenido.appendChild(fila);
            });
        } else {
            contenido.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">No hay proveedores disponibles</td>
                </tr>
            `;
        }
    } catch (error) {
        console.error("Error al mostrar proveedores:", error);
        let contenido = document.getElementById('content_proveedor');
        if (contenido) {
            contenido.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">Error al cargar los proveedores. Intente nuevamente más tarde.</td>
                </tr>
            `;
        }
    }
}

async function eliminarProveedor(id) {
    // Implementa aquí la lógica para eliminar el proveedor
    console.log(`Eliminar proveedor con ID: ${id}`);
}

// Ejecutar la función al cargar la página
if (document.getElementById('content_proveedor')) {
    view_proveedores();
}