<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selects Marca y Modelo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            color: #333;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        select:hover {
            border-color: #667eea;
        }

        select:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        select:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .loading {
            display: none;
            color: #667eea;
            font-size: 14px;
            margin-top: 5px;
            font-style: italic;
        }

        .loading.show {
            display: block;
        }

        .result {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            display: none;
        }

        .result.show {
            display: block;
        }

        .result h3 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .result p {
            color: #555;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🚗 Seleccionar Vehículo</h2>
        
        <div class="form-group">
            <label for="marca">Marca</label>
            <select id="marca" name="marca">
                <option value="">-- Seleccione una marca --</option>
            </select>
            <div class="loading" id="loading-marca">Cargando marcas...</div>
        </div>

        <div class="form-group">
            <label for="modelo">Modelo</label>
            <select id="modelo" name="modelo" disabled>
                <option value="">-- Primero seleccione una marca --</option>
            </select>
            <div class="loading" id="loading-modelo">Cargando modelos...</div>
        </div>

        <div class="result" id="result">
            <h3>Vehículo seleccionado:</h3>
            <p id="result-text"></p>
        </div>
    </div>

    <script>
        // Elementos del DOM
        const selectMarca = document.getElementById('marca');
        const selectModelo = document.getElementById('modelo');
        const loadingMarca = document.getElementById('loading-marca');
        const loadingModelo = document.getElementById('loading-modelo');
        const resultDiv = document.getElementById('result');
        const resultText = document.getElementById('result-text');

        // Función para cargar marcas desde PHP
        function cargarMarcas() {
            loadingMarca.classList.add('show');
            
            // Crear objeto XMLHttpRequest
            const xhr = new XMLHttpRequest();
            
            // Configurar la petición
            xhr.open('GET', 'get_marcas.php', true);
            
            // Manejar la respuesta
            xhr.onload = function() {
                loadingMarca.classList.remove('show');
                
                if (xhr.status === 200) {
                    // Insertar el HTML recibido directamente en el select
                    selectMarca.innerHTML = xhr.responseText;
                } else {
                    console.error('Error al cargar marcas:', xhr.status);
                    selectMarca.innerHTML = '<option value="">Error al cargar marcas</option>';
                }
            };
            
            // Manejar errores
            xhr.onerror = function() {
                loadingMarca.classList.remove('show');
                console.error('Error de conexión');
                selectMarca.innerHTML = '<option value="">Error de conexión</option>';
            };
            
            // Enviar la petición
            xhr.send();
        }

        // Función para cargar modelos según la marca seleccionada
        function cargarModelos(marcaId) {
            loadingModelo.classList.add('show');
            selectModelo.disabled = true;
            resultDiv.classList.remove('show');
            
            // Crear objeto XMLHttpRequest
            const xhr = new XMLHttpRequest();
            
            // Configurar la petición con el parámetro marca_id
            xhr.open('GET', 'get_modelos.php?marca_id=' + marcaId, true);
            
            // Manejar la respuesta
            xhr.onload = function() {
                loadingModelo.classList.remove('show');
                
                if (xhr.status === 200) {
                    // Insertar el HTML recibido directamente en el select
                    selectModelo.innerHTML = xhr.responseText;
                    selectModelo.disabled = false;
                } else {
                    console.error('Error al cargar modelos:', xhr.status);
                    selectModelo.innerHTML = '<option value="">Error al cargar modelos</option>';
                    selectModelo.disabled = false;
                }
            };
            
            // Manejar errores
            xhr.onerror = function() {
                loadingModelo.classList.remove('show');
                console.error('Error de conexión');
                selectModelo.innerHTML = '<option value="">Error de conexión</option>';
                selectModelo.disabled = false;
            };
            
            // Enviar la petición
            xhr.send();
        }

        // Event listener para cambio de marca
        selectMarca.addEventListener('change', function() {
            const marcaId = this.value;
            
            if (marcaId) {
                cargarModelos(marcaId);
            } else {
                selectModelo.disabled = true;
                selectModelo.innerHTML = '<option value="">-- Primero seleccione una marca --</option>';
                resultDiv.classList.remove('show');
            }
        });

        // Event listener para cambio de modelo
        selectModelo.addEventListener('change', function() {
            const marcaNombre = selectMarca.options[selectMarca.selectedIndex].text;
            const modeloNombre = this.options[this.selectedIndex].text;
            
            if (this.value) {
                resultText.textContent = `${marcaNombre} - ${modeloNombre}`;
                resultDiv.classList.add('show');
            } else {
                resultDiv.classList.remove('show');
            }
        });

        // Cargar marcas al cargar la página
        window.addEventListener('DOMContentLoaded', cargarMarcas);
    </script>
</body>
</html>