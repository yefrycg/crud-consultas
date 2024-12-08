<!-- Categorias -->
<div class="container-fluid">

    <!-- Cabecera pagina -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Clientes</h1>

        <!-- Boton Añadir -->
        <a href="<?php echo SERVER_URL; ?>cliente-new/" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Añadir</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de clientes</h6>
        </div>

        <div class="card-body">
            
            <!-- Formulario de filtros -->
            <form id="filtroclientes" method="POST" action="<?php echo SERVER_URL; ?>clientes/">
                <div class="row">

                    <!-- Cédula -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_cedula">Cédula</label>
                            <select name="cedula" id="filtro_cedula" class="form-control">
                            <?php
                                require_once "./controladores/clienteControlador.php";
                                $ins_cliente = new clienteControlador();
                                $clientes = $ins_cliente->obtener_clientes_controlador();

                                if ($clientes) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($clientes as $cliente) {
                                        $selected = ($cliente->cedula == $_POST['cedula']) ? 'selected' : '';
                                        echo '<option value="' . $cliente->cedula . '" ' . $selected . '>' . $cliente->cedula . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay clientes disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_nombre">Nombre</label>
                            <select name="nombre" id="filtro_nombre" class="form-control">
                            <?php
                                require_once "./controladores/clienteControlador.php";
                                $ins_cliente = new clienteControlador();
                                $clientes = $ins_cliente->obtener_clientes_controlador();

                                if ($clientes) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($clientes as $cliente) {
                                        $selected = ($cliente->nombre == $_POST['nombre']) ? 'selected' : '';
                                        echo '<option value="' . $cliente->nombre . '" ' . $selected . '>' . $cliente->nombre . ' '. $cliente->apellidos .'</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay clientes disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_telefono">Télefono</label>
                            <input type="text" class="form-control" id="filtro_telefono" name="telefono" value="<?php echo isset($_POST['telefono']) ? $_POST['telefono'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filtro_direccion">Dirección</label>
                            <input type="text" class="form-control" id="filtro_direccion" name="direccion" value="<?php echo isset($_POST['direccion']) ? $_POST['direccion'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Filtro de orden -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar clientes de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="asc" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'asc') ? 'selected' : ''; ?>>A-Z</option>
                                <option value="desc" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'desc') ? 'selected' : ''; ?>>Z-A</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>clientes/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Clientes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Clientes</h6>
        </div>

        <div class="card-body">
            <?php
                require_once "./controladores/clienteControlador.php";
                $ins_cliente = new clienteControlador();
                $filtros = [
                    'cedula' => isset($_POST['cedula']) ? trim($_POST['cedula']) : '',
                    'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                    'telefono' => isset($_POST['telefono']) ? trim($_POST['telefono']) : '',
                    'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_cliente->paginar_cliente_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>