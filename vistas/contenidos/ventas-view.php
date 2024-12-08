<?php
if ($_SESSION['rol_sca'] != "Administrador") {
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>

<!-- Ventas -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 text-gray-800 mr-3">Ventas</h1>

        <!-- Boton añadir -->
        <a href="<?php echo SERVER_URL; ?>venta-new/" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Añadir</span>
        </a>
    </div>

    <!-- Filtro de Ventas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Búsqueda de Ventas</h6>
        </div>

        <div class="card-body">
            <form id="filtroVentas" method="POST" action="<?php echo SERVER_URL; ?>ventas/">
                <div class="row">

                    <!-- ID de Venta -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_id">Id</label>
                            <select name="id" id="filtro_id" class="form-control">
                            <?php
                                require_once "./controladores/ventaControlador.php";
                                $ins_venta = new ventaControlador();
                                $ventas = $ins_venta->obtener_ventas_controlador();

                                if ($ventas) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($ventas as $venta) {
                                        $selected = ($venta->id == $_POST['id']) ? 'selected' : '';
                                        echo '<option value="' . $venta->id . '" ' . $selected . '>' . $venta->id . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay ventas disponibles</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <!-- Tipo de Comprobante -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_comprobante">Comprobante</label>
                            <select class="form-control" id="filtro_comprobante" name="comprobante">
                                <option value="">Seleccione</option>
                                <option value="Boleta" <?php echo (isset($_POST['comprobante']) && $_POST['comprobante'] == 'Boleta') ? 'selected' : ''; ?>>Boleta</option>
                                <option value="Factura" <?php echo (isset($_POST['comprobante']) && $_POST['comprobante'] == 'Factura') ? 'selected' : ''; ?>>Factura</option>
                            </select>
                        </div>
                    </div>

                    <!-- Nombre del Cliente -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_cliente">Cliente</label>
                            <select name="cliente" id="filtro_cliente" class="form-control">
                            <?php
                                require_once "./controladores/clienteControlador.php";
                                $ins_cliente = new clienteControlador();
                                $clientes = $ins_cliente->obtener_clientes_controlador();

                                if ($clientes) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($clientes as $cliente) {
                                        $selected = ($cliente->nombre == $_POST['cliente']) ? 'selected' : '';
                                        echo '<option value="' . $cliente->nombre . '" ' . $selected . '>' . $cliente->nombre . ' '. $cliente->apellidos .'</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay clientes disponibles</option>';
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

                    <!-- Nombre del Vendedor -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="filtro_vendedor">Vendedor</label>
                            <select name="vendedor" id="filtro_vendedor" class="form-control">
                            <?php
                                require_once "./controladores/usuarioControlador.php";
                                $ins_usuario = new usuarioControlador();
                                $usuarios = $ins_usuario->obtener_usuarios_controlador();

                                if ($usuarios) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($usuarios as $usuario) {
                                        $selected = ($usuario->nombre == $_POST['vendedor']) ? 'selected' : '';
                                        echo '<option value="' . $usuario->nombre . '" ' . $selected . '>' . $usuario->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay productos disponibles</option>';
                                }
                            ?>
                            </select>
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

                    <!-- Filtro por total (Mayor, Menor) -->
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
                            <label for="filtro_orden">Ordenar Clientes de:</label>
                            <select class="form-control" id="filtro_orden" name="orden">
                                <option value="">Seleccione</option>
                                <option value="ASC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'ASC') ? 'selected' : ''; ?>>A-Z</option>
                                <option value="DESC" <?php echo (isset($_POST['orden']) && $_POST['orden'] == 'DESC') ? 'selected' : ''; ?>>Z-A</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones (Buscar, Reestablecer) -->
                    <div class="col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Buscar</button>
                        <a href="<?php echo SERVER_URL; ?>ventas/" class="btn btn-secondary">Restablecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Ventas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Ventas</h6>
        </div>
        
        <div class="card-body">
            <?php
                require_once "./controladores/ventaControlador.php";
                $ins_venta = new ventaControlador();

                $filtros = [
                    'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                    'comprobante' => isset($_POST['comprobante']) ? trim($_POST['comprobante']) : '',
                    'cliente' => isset($_POST['cliente']) ? trim($_POST['cliente']) : '',
                    'fecha_desde' => isset($_POST['fecha_desde']) ? trim($_POST['fecha_desde']) : '',
                    'fecha_hasta' => isset($_POST['fecha_hasta']) ? trim($_POST['fecha_hasta']) : '',
                    'vendedor' => isset($_POST['vendedor']) ? trim($_POST['vendedor']) : '',
                    'total_desde' => isset($_POST['total_desde']) ? trim($_POST['total_desde']) : '',
                    'total_hasta' => isset($_POST['total_hasta']) ? trim($_POST['total_hasta']) : '',
                    'total' => isset($_POST['total']) ? trim($_POST['total']) : '',
                    'orden' => isset($_POST['orden']) ? trim($_POST['orden']) : ''
                ];

                echo $ins_venta->paginar_venta_controlador($pagina[1], 10, $pagina[0], $filtros);
            ?>
    </div>
</div>