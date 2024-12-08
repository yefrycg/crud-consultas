<!-- Panel administrativo -->
<div class="container-fluid">

    <!-- Cabecera de página -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel administrativo</h1>
    </div>

    <!-- Contenido en fila -->
    <div class="row">
        <?php
            require_once "./controladores/compraControlador.php";
            $ins_compra = new compraControlador();
            $total_compras = $ins_compra->obtener_cantidad_compras_controlador();
        ?>
        <!-- Tarjeta Compras -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>compras/" style="text-decoration: none;">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Compras
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_compras->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php
            require_once "./controladores/ventaControlador.php";
            $ins_venta = new ventaControlador();
            $total_ventas = $ins_venta->obtener_cantidad_ventas_controlador();
        ?>
        <!-- Tarjeta Ventas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>ventas/" style="text-decoration: none;">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Ventas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_ventas->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php 
        if ($_SESSION['rol_sca'] == "Administrador") {
            require_once "./controladores/categoriaControlador.php";
            $ins_categoria = new categoriaControlador();
            $total_categorias = $ins_categoria->obtener_cantidad_categorias_controlador();
        ?>
        <!-- Tarjeta Categorias -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>categorias/" style="text-decoration: none;">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Categorías</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_categorias->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>

        <?php if ($_SESSION['rol_sca'] == "Administrador") { 
            require_once "./controladores/productoControlador.php";
            $ins_producto = new productoControlador();
            $total_productos = $ins_producto->obtener_cantidad_productos_controlador();
        ?>
        <!-- Tarjeta Productos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>productos/" style="text-decoration: none;">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Productos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_productos->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>

        <?php
            require_once "./controladores/clienteControlador.php";
            $ins_cliente = new clienteControlador();
            $total_clientes = $ins_cliente->obtener_cantidad_clientes_controlador();
        ?>
        <!-- Tarjeta Clientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>clientes/" style="text-decoration: none;">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Clientes</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_clientes->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php if ($_SESSION['rol_sca'] == "Administrador") {
            require_once "./controladores/proveedorControlador.php";
            $ins_proveedor = new proveedorControlador();
            $total_proveedores = $ins_proveedor->obtener_cantidad_proveedores_controlador();
        ?>
        <!-- Tarjeta Proveedores -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>proveedores/" style="text-decoration: none;">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Proveedores</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_proveedores->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>

        <?php if ($_SESSION['rol_sca'] == "Administrador") {
            require_once "./controladores/usuarioControlador.php";
            $ins_usuario = new usuarioControlador();
            $total_usuarios = $ins_usuario->obtener_cantidad_usuarios_controlador();
        ?>
        <!-- Tarjeta Usuarios -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="<?php echo SERVER_URL; ?>usuarios/" style="text-decoration: none;">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Usuarios</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_usuarios->Cantidad ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>
    </div>
</div>