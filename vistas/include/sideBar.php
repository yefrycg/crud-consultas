<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo SERVER_URL; ?>panel/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Colmena Aristides</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Panel -->
    <li class="nav-item active">
        <a class="nav-link" href="<?php echo SERVER_URL; ?>panel/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Panel</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Compras Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-truck-loading"></i>
            <span>Compras</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?php echo SERVER_URL; ?>compra-new/">Añadir Compra</a>
                <a class="collapse-item" href="<?php echo SERVER_URL; ?>compras/">Listado de Compras</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Ventas Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Ventas</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Acciones:</h6>
                <a class="collapse-item" href="<?php echo SERVER_URL; ?>venta-new/">Añadir Venta</a>
                <a class="collapse-item" href="<?php echo SERVER_URL; ?>ventas/">Listado de Ventas</a>
            </div>
        </div>
    </li>

    <?php if ($_SESSION['rol_sca'] == "Administrador") { ?>
    <!-- Nav Item - Categorias -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo SERVER_URL; ?>categorias/">
            <i class="fas fa-fw fa-tags"></i>
            <span>Categorías</span></a>
    </li>

    <!-- Nav Item - Productos -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo SERVER_URL; ?>productos/">
            <i class="fas fa-fw fa-box"></i>
            <span>Productos</span></a>
    </li>
    <?php } ?>

    <!-- Nav Item - Clientes -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo SERVER_URL; ?>clientes/">
            <i class="fas fa-fw fa-users"></i>
            <span>Clientes</span></a>
    </li>

    <?php if ($_SESSION['rol_sca'] == "Administrador") { ?>
    <!-- Nav Item - Proveedores -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo SERVER_URL; ?>proveedores/">
            <i class="fas fa-fw fa-truck"></i>
            <span>Proveedores</span></a>
    </li>

    <!-- Nav Item - Usuarios -->
    <li class="nav-item">
    <a class="nav-link" href="<?php echo SERVER_URL; ?>usuarios/">
        <i class="fas fa-fw fa-user"></i>
        <span>Usuarios</span></a>
    </li>
    <?php } ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>