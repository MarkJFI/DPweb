<?php
// Verificar si BASE_URL está definida
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/DPweb/');
}
?>

<style>
.client-form-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}

.form-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin: 20px auto;
    max-width: 1000px;
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.form-header h2 {
    color: #333;
    font-size: 28px;
    font-weight: 600;
    margin: 0;
}

.form-header .subtitle {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.form-group {
    flex: 1;
    min-width: 250px;
}

.form-group.full-width {
    flex: 100%;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    background-color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-group {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    min-width: 120px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.required {
    color: #dc3545;
}

@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .form-group {
        min-width: 100%;
    }
    
    .btn-group {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>

<!--inicio de cuerpo de pagina-->
<div class="client-form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>Registro de Cliente</h2>
            <p class="subtitle">Complete todos los campos para registrar un nuevo cliente</p>
        </div>
        
        <form id="frm_user" action="">
            <!-- Información Personal -->
            <div class="form-row">
                <div class="form-group">
                    <label for="nro_identidad" class="form-label">
                        Nro de Documento <span class="required">*</span>
                    </label>
                    <input type="number" class="form-control" id="nro_identidad" name="nro_identidad" required>
                </div>
                
                <div class="form-group">
                    <label for="razon_social" class="form-label">
                        Nombres y Apellidos <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                </div>
            </div>

            <!-- Contacto -->
            <div class="form-row">
                <div class="form-group">
                    <label for="telefono" class="form-label">
                        Teléfono <span class="required">*</span>
                    </label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                </div>
                
                <div class="form-group">
                    <label for="correo" class="form-label">
                        Correo Electrónico <span class="required">*</span>
                    </label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="form-row">
                <div class="form-group">
                    <label for="departamento" class="form-label">
                        Departamento <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" id="departamento" name="departamento" required>
                </div>
                
                <div class="form-group">
                    <label for="provincia" class="form-label">
                        Provincia <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" id="provincia" name="provincia" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="distrito" class="form-label">
                        Distrito <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" id="distrito" name="distrito" required>
                </div>
                
                <div class="form-group">
                    <label for="cod_postal" class="form-label">
                        Código Postal <span class="required">*</span>
                    </label>
                    <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                </div>
            </div>

            <!-- Dirección -->
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="direccion" class="form-label">
                        Dirección Completa <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>
            </div>

            <!-- Seguridad y Rol -->
            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">
                        Contraseña <span class="required">*</span>
                    </label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="rol" class="form-label">
                        Rol <span class="required">*</span>
                    </label>
                    <select class="form-select" name="rol" id="rol" required>
                        <option value="" disabled selected>Seleccione un rol</option>
                        <option value="Cliente">Cliente</option>
                    </select>
                </div>
            </div>

            <!-- Botones -->
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Cliente
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-eraser"></i> Limpiar Formulario
                </button>
                <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-danger">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
<!--fin de pie de pagina-->

<script src="<?php echo BASE_URL; ?>view/function/clients.js"></script>
</qodoArtifact>

<!--inicio de cuerpo de pagina-->
<div class="container-fluid">
    <div class="card">
        <h5 class="card-header">Registro de Cliente</h5>
        <form id="frm_user" action="">
            <div class="card-body">
                <div class="mb-3 row">
                    <label for="nro_identidad" class="col-sm-4 col-form-label">Nro de Documento :</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="nro_identidad" name="nro_identidad" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="razon_social" class="col-sm-4 col-form-label">Nombres y Apellidos :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="telefono" class="col-sm-4 col-form-label">Teléfono :</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="telefono" name="telefono" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="correo" class="col-sm-4 col-form-label">Correo Electrónico:</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="departamento" class="col-sm-4 col-form-label">Departamento:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="departamento" name="departamento" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="provincia" class="col-sm-4 col-form-label">Provincia:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="provincia" name="provincia" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="distrito" class="col-sm-4 col-form-label">Distrito:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="distrito" name="distrito" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cod_postal" class="col-sm-4 col-form-label">Código Postal:</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="direccion" class="col-sm-4 col-form-label">Dirección:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password" class="col-sm-4 col-form-label">Contraseña:</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>

                <select class="form-control" name="rol" id="rol" required>
                    <option value="" disabled selected>Seleccione un rol</option>
                    <option value="Cliente">Cliente</option>
                </select>
                <br>
                <button type="submit" class="btn btn-success">Registrar</button>
                <button type="reset" class="btn btn-secondary">Limpiar</button>
                <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-danger">Cancelar</a>

            </div>
        </form>
    </div>
</div>
<!--fin de pie de pagina-->

<script src="<?php echo BASE_URL; ?>view/function/clients.js"></script>