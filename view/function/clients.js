async function view_clients() {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_clientes', {
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
            console.error('Error al parsear JSON:', parseError);
            console.error('Respuesta recibida:', textoRespuesta);
            let contenido = document.getElementById('content_clientes');
            if (contenido) {
                contenido.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger">Error: El servidor no devolvió una respuesta JSON válida.</td>
                    </tr>
                `;
            }
            return; // Detener la ejecución si el JSON es inválido
        }

        console.log("Datos recibidos:", json);

        let contenido = document.getElementById('content_clientes');
        if (!contenido) {
            console.error("No se encontró el contenedor #content_clientes");
            return;
        }

        contenido.innerHTML = ''; // Limpiar contenido existente

        if (json.status && Array.isArray(json.data)) {
            json.data.forEach(cliente => {
                let fila = document.createElement('tr');
                // Asumiendo que los clientes tienen campos como nro_identidad, razon_social, correo, telefono
                fila.innerHTML = `
                    <td>${cliente.id}</td>
                    <td>${cliente.nro_identidad}</td>
                    <td>${cliente.razon_social}</td>
                    <td>${cliente.correo}</td>
                    <td>${cliente.telefono}</td>
                    <td><span class="badge bg-success">Activo</span></td>
                    <td>
                        <a href="${base_url}edit-cliente/${cliente.id}" class="btn btn-sm btn-primary">Editar</a>
                        <button class="btn btn-sm btn-danger" onclick="eliminarCliente(${cliente.id})">Eliminar</button>
                    </td>
                `;
                contenido.appendChild(fila);
            });
        } else {
            contenido.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">No hay clientes disponibles</td>
                </tr>
            `;
        }
    } catch (error) {
        console.error("Error al obtener clientes:", error);
        let contenido = document.getElementById('content_clientes');
        if (contenido) {
            contenido.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger">Error al cargar los clientes. Intente nuevamente más tarde.</td>
                </tr>
            `;
        }
    }
}

async function eliminarCliente(id) {
    console.log(`Eliminar cliente con ID: ${id}`);
    // Aquí iría la lógica para eliminar el cliente, probablemente con otra llamada fetch
}

// Ejecutar la función al cargar la página si el contenedor existe
if (document.getElementById('content_clientes')) {
    view_clients();
}