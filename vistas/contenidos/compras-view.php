<!-- Compras -->
<div class="container-fluid">

    <!-- Cabecera página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Compras</h1>
        <a href="<?php echo SERVER_URL; ?>compra-new/" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Añadir</span>
        </a>
    </div>

    <!-- Filtro de Compras -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Compras</h6>
        </div>
        <div class="card-body">
            <form id="filtroCompras" method="POST" action="<?php echo SERVER_URL; ?>compras/">
                <div class="row">
                    <!-- ID de Compra -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Id</label>
                            <select name="id" id="filtro_id" class="form-control">
                            <?php
                                require_once "./controladores/compraControlador.php";
                                $ins_compra = new compraControlador();
                                $compras = $ins_compra->obtener_compras_controlador();

                                if ($compras) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($compras as $compra) {
                                        $selected = ($compra->id == $_POST['id']) ? 'selected' : '';
                                        echo '<option value="' . $compra->id . '" ' . $selected . '>' . $compra->id . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay compras disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Tipo de Comprobante -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_comprobante">Tipo de Comprobante</label>
                            <select class="form-control" id="filtro_comprobante" name="comprobante">
                                <option value="">Seleccione</option>
                                <option value="Boleta" <?php echo (isset($_POST['comprobante']) && $_POST['comprobante'] == 'Boleta') ? 'selected' : ''; ?>>Boleta</option>
                                <option value="Factura" <?php echo (isset($_POST['comprobante']) && $_POST['comprobante'] == 'Factura') ? 'selected' : ''; ?>>Factura</option>
                            </select>
                        </div>
                    </div>

                    <!-- Nombre del Proveedor -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_proveedor">Nombre del Proveedor</label>
                            <select name="proveedor" id="filtro_proveedor" class="form-control">
                            <?php
                                require_once "./controladores/proveedorControlador.php";
                                $ins_proveedor = new proveedorControlador();
                                $proveedores = $ins_proveedor->obtener_proveedores_controlador();

                                if ($proveedores) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($proveedores as $proveedor) {
                                        $selected = ($proveedor->nombre == $_POST['proveedor']) ? 'selected' : '';
                                        echo '<option value="' . $proveedor->nombre . '" ' . $selected . '>' . $proveedor->nombre . ' '. $proveedor->apellidos .'</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay proveedores disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_fecha_desde">Fecha Desde</label>
                            <input type="date" class="form-control" id="filtro_fecha_desde" name="fecha_desde" value="<?php echo isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_fecha_hasta">Fecha Hasta</label>
                            <input type="date" class="form-control" id="filtro_fecha_hasta" name="fecha_hasta" value="<?php echo isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Rango Total -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_total_desde">Total Desde</label>
                            <input type="number" class="form-control" id="filtro_total_desde" name="total_desde" step="0.01" value="<?php echo isset($_POST['total_desde']) ? $_POST['total_desde'] : ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_total_hasta">Total Hasta</label>
                            <input type="number" class="form-control" id="filtro_total_hasta" name="total_hasta" step="0.01" value="<?php echo isset($_POST['total_hasta']) ? $_POST['total_hasta'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Filtro por total -->
                    <div class="col-md-2">
                        <label for="filtro_total">Filtrar por total:</label>
                        <select name="total" id="filtro_total" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Menor" <?php echo (isset($_POST['total']) && $_POST['total'] == 'Menor') ? 'selected' : ''; ?>>Menor</option>
                            <option value="Mayor" <?php echo (isset($_POST['total']) && $_POST['total'] == 'Mayor') ? 'selected' : ''; ?>>Mayor</option>
                        </select>
                    </div>

                    <!-- Ordenar por Nombre -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_orden">Ordenar proveedores de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="ASC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'ASC') ? 'selected' : ''; ?>>A-Z</option>
                                <option value="DESC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'DESC') ? 'selected' : ''; ?>>Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>compras/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Compras -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Compras</h6>
        </div>
        <div class="card-body">
            <?php
                require_once "./controladores/compraControlador.php";
                $ins_compra = new compraControlador();

                $filtros = [
                    'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                    'comprobante' => isset($_POST['comprobante']) ? trim($_POST['comprobante']) : '',
                    'proveedor' => isset($_POST['proveedor']) ? trim($_POST['proveedor']) : '',
                    'fecha_desde' => isset($_POST['fecha_desde']) ? trim($_POST['fecha_desde']) : '',
                    'fecha_hasta' => isset($_POST['fecha_hasta']) ? trim($_POST['fecha_hasta']) : '',
                    'total_desde' => isset($_POST['total_desde']) ? trim($_POST['total_desde']) : '',
                    'total_hasta' => isset($_POST['total_hasta']) ? trim($_POST['total_hasta']) : '',
                    'total' => isset($_POST['total']) ? trim($_POST['total']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_compra->paginar_compra_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
        </div>
    </div>
</div>
