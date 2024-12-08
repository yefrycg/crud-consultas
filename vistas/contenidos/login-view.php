<!-- Login -->
<div class="d-flex flex-column min-vh-100">
    <div class="container my-auto">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-lg-6">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">¡Bienvenido!</h1>
                        </div>

                        <!-- Formulario de inicio de sesión -->
                        <form class="user" method="POST">
                            <!-- Nombre -->
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="nombre" id="nombre" placeholder="Ingrese su nombre de usuario..." required>
                            </div>

                            <!-- Contraseña -->
                            <div class="form-group">
                                <input type="password" class="form-control form-control-user" name="contrasena" id="contrasena" placeholder="Contraseña" required>
                            </div>

                            <!-- Botón iniciar sesión -->
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                <span class="text">Iniciar sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3 mt-auto">
        <p class="mb-0">© 2024 Colmena Arístides. Todos los derechos reservados.</p>
    </footer>
</div>

<?php
    if (isset($_POST['nombre']) && isset($_POST['contrasena'])) {
        require_once "./controladores/loginControlador.php";

        $ins_login = new loginControlador();
        echo $ins_login->iniciar_sesion_controlador();
    }
?>