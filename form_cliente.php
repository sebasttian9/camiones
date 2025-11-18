<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-title {
            color: #333;
            margin-bottom: 25px;
            font-weight: bold;
        }
        .required {
            color: red;
        }
    </style>
</head>
<body>

<!-- Main Content -->
<div class="container my-5" id="catalogo">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="form-container">
                <h2 class="form-title text-center">Registro de Cliente</h2>
                
                <form id="formCliente" action="procesar_cliente.php" method="POST">
                    
                    <!-- Nombre Cliente -->
                    <div class="mb-3">
                        <label for="nombre_cliente" class="form-label">
                            Nombre del Cliente <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required>
                    </div>

                    <!-- RUT -->
                    <div class="mb-3">
                        <label for="rut" class="form-label">
                            RUT <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" id="rut" name="rut" placeholder="12345678-9" required>
                        <div class="form-text">Formato: 12345678-9</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Contraseña <span class="required">*</span>
                        </label>
                        <input type="password" class="form-control" id="password" name="password" 
                               pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$" required>
                        <div class="form-text">Mínimo 6 caracteres, debe contener letras y números</div>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">
                            Estado <span class="required">*</span>
                        </label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="">Seleccione un estado</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="direccion" class="form-label">
                            Dirección <span class="required">*</span>
                        </label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="3" required></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="reset" class="btn btn-secondary me-md-2">
                            Limpiar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Registrar Cliente
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Validación adicional de contraseña con JavaScript
    document.getElementById('formCliente').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
        
        if (!regex.test(password)) {
            e.preventDefault();
            alert('La contraseña debe tener mínimo 6 caracteres y contener letras y números');
            return false;
        }
    });

    // Validación de RUT chileno (opcional)
    document.getElementById('rut').addEventListener('blur', function() {
        const rut = this.value.replace(/\./g, '');
        if (rut && !validarRUT(rut)) {
            this.setCustomValidity('RUT inválido');
        } else {
            this.setCustomValidity('');
        }
    });

    function validarRUT(rut) {
        if (!/^[0-9]+-[0-9kK]{1}$/.test(rut)) return false;
        
        const tmp = rut.split('-');
        let digv = tmp[1]; 
        const rutNum = tmp[0];
        
        if (digv == 'K') digv = 'k';
        
        return (dv(rutNum) == digv);
    }

    function dv(T) {
        let M = 0, S = 1;
        for (; T; T = Math.floor(T / 10))
            S = (S + T % 10 * (9 - M++ % 6)) % 11;
        return S ? S - 1 : 'k';
    }
</script>

</body>
</html>