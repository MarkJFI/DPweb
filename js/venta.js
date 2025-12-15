document.addEventListener('DOMContentLoaded', function() {
    const buscarBtn = document.querySelector('button[data-bs-target="#clienteModal"]');
    const dniInput = document.getElementById('dni');
    const nombreClienteInput = document.getElementById('nombreCliente');
    const clienteResultado = document.getElementById('clienteResultado');

    buscarBtn.addEventListener('click', function() {
        const dni = dniInput.value.trim();
        if (!dni) {
            clienteResultado.textContent = 'Por favor, ingrese un DNI.';
            return;
        }

        fetch('control/VentaController.php?tipo=buscarCliente', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'dni=' + encodeURIComponent(dni)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                nombreClienteInput.value = data.nombre;
                clienteResultado.textContent = 'Cliente encontrado: ' + data.nombre;
            } else {
                nombreClienteInput.value = '';
                clienteResultado.textContent = data.msg;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            clienteResultado.textContent = 'Error al buscar el cliente.';
        });
    });
});
