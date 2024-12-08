<?php

    if ($peticionAjax) {
        require_once "../modelos/proveedorModelo.php";
    } else {
        require_once "./modelos/proveedorModelo.php";
    }

    class proveedorControlador extends proveedorModelo {

        //---------- Controlador para agregar proveedor -------------//
        public function agregar_proveedor_controlador() {

            $rut = $_POST['rut'];
            $nombre = $_POST['nombre'];
            $contacto = $_POST['contacto'];
            $direccion = $_POST['direccion'];

            //---------- Comprobar campos vacios ------------//
            if ($rut == "" || $nombre == "" || $contacto == "" || $direccion == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No haz llenado todos los campos que son obligatorios",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando el rut del proveedor -------------//
            $check_proveedor = mainModel::ejecutar_consulta_simple("SELECT rut FROM proveedor WHERE rut = '$rut';");
            if ($check_proveedor->rowCount() > 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El proveedor ingresado ya se encuentra registrado en el sistema",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_proveedor = [
                "Rut"=>$rut,
                "Nombre"=>$nombre,
                "Contacto"=>$contacto,
                "Direccion"=>$direccion
            ];

            $agregar_proveedor = proveedorModelo::agregar_proveedor_modelo($datos_proveedor);

            if ($agregar_proveedor->rowCount() == 1) {
                $alerta = [
                    "Alerta"=>"limpiar",
                    "Titulo"=>"Proveedor registrado",
                    "Texto"=>"Los datos del proveedor han sido registrados con éxito",
                    "Tipo"=>"success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido registrar al proveedor",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
            }
            
        } //Fin

        //---------- Controlador para paginar proveedores -------------//
        public function paginar_proveedor_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = ""; // Inicializa como una cadena vacía

            // Procesar filtros
            $conditions = []; // Array para almacenar las condiciones
            
            if (isset($filtros['rut']) && $filtros['rut'] != "") {
                $conditions[] = "rut LIKE '%" . $filtros['rut'] . "%'";
            }
            if (isset($filtros['nombre']) && $filtros['nombre'] != "") {
                $conditions[] = "nombre LIKE '%" . $filtros['nombre'] . "%'";
            }
            if (isset($filtros['contacto']) && $filtros['contacto'] != "") {
                $conditions[] = "contacto LIKE '%" . $filtros['contacto'] . "%'";
            }
            if (isset($filtros['direccion']) && $filtros['direccion'] != "") {
                $conditions[] = "direccion LIKE '%" . $filtros['direccion'] . "%'";
            }
            
            // Solo añadir WHERE si hay condiciones
            if (!empty($conditions)) {
                $where = "WHERE " . implode(" AND ", $conditions);
            }
            
            // Ordenar por nombre (A-Z o Z-A)
            $orden = "";
            if (isset($filtros['orden']) && in_array($filtros['orden'], ['asc', 'desc'])) {
                $orden = "ORDER BY nombre " . strtoupper($filtros['orden']);
            }
            
            // Construir consultas finales
            $consulta = "SELECT * FROM proveedor $where $orden LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM proveedor $where;";

            $conexion = mainModel::conectar();

            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            $total = $conexion->query($consulta_total);
            $total = (int) $total->fetchColumn();

            $n_paginas = ceil($total / $registros);

            $tabla .= '
            <div class="table-responsive mb-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">RUT</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Contacto</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                    <tr>
                        <td>' . $rows['rut'] . '</td>
                        <td>' . $rows['nombre'] . '</td>
                        <td>' . $rows['contacto'] . '</td>
                        <td>' . $rows['direccion'] . '</td>
                        <td>
                            <a href="' . SERVER_URL . 'proveedor-update/' . $rows['rut'] . '/" class="btn btn-small btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/proveedorAjax.php" method="POST" data-form="delete">
                                <input type="hidden" name="rut_eliminar" value="' . $rows['rut'] . '">
                                <button type="submit" class="btn btn-small btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    ';
                    $contador++;
                }
                $reg_final = $contador - 1;
            } else {
                if ($total >= 1) {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="5">
                            <a href="' . $url . '1/" class="btn btn-primary">
                                Haga clic acá para recargar el listado
                            </a>
                        </td>
                    </tr>
                    ';
                } else {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="5">No hay registros en el sistema</td>
                    </tr>
                    ';
                }
            }

            $tabla .= '
                    </tbody>
                </table>
            </div>
            ';

            if ($total >= 1 && $pagina <= $n_paginas) {
                $tabla .= '<p class="text-left">Mostrando proveedor ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        } // Fin

        //---------- Controlador eliminar proveedor -----------------//
        public function eliminar_proveedor_controlador() {

            // Recibiendo RUT del proveedor
            $rut = $_POST['rut_eliminar'];

            //------------ Comprobando el proveedor en BD ------------//
            $check_proveedor = mainModel::ejecutar_consulta_simple("SELECT rut FROM proveedor WHERE rut = '$rut';");
            if ($check_proveedor->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El proveedor que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando las compras asociadas ------------//
            $check_compras = mainModel::ejecutar_consulta_simple("SELECT rut_proveedor FROM compra WHERE rut_proveedor = '$rut' LIMIT 1;");
            if ($check_compras->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar este proveedor debido a que tiene compras asociadas',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_proveedor = proveedorModelo::eliminar_proveedor_modelo($rut);

            if ($eliminar_proveedor->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Proveedor eliminado',
                    "Texto" => 'El proveedor ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar al proveedor, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        } //Fin

        //---------- Controlador para obtener proveedor -------------//
        public function obtener_proveedor_controlador($rut) {
            return proveedorModelo::obtener_proveedor_modelo($rut);
        } // Fin

        //---------- Controlador para obtener proveedor -------------//
        public function obtener_cantidad_proveedores_controlador() {
            return proveedorModelo::obtener_cantidad_proveedores_modelo();
        } // Fin

        //---------- Controlador para obtener todos los proveedores -------------//
        public function obtener_proveedores_controlador() {
            return proveedorModelo::obtener_proveedores_modelo();
        } // Fin
        
        //---------- Controlador para actualizar los datos del proveedor -------------//
        public function actualizar_proveedor_controlador() {
            
            $rut = $_POST['rut_actualizar'];

            // Comprobar al proveedor en la BD
            $check_proveedor = mainModel::ejecutar_consulta_simple("SELECT * FROM proveedor WHERE rut = '$rut'");
            if ($check_proveedor->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado al proveedor en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_proveedor->fetch();
            }

            $nombre = $_POST['nombre_actualizar'];
            $contacto = $_POST['contacto_actualizar'];
            $direccion = $_POST['direccion_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "" || $contacto == "" || $direccion == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Rut" => $rut,
                "Nombre" => $nombre,
                "Contacto" => $contacto,
                "Direccion" => $direccion,
            ];

            $actualizar_proveedor = proveedorModelo::actualizar_proveedor_modelo($datos);

            if ($actualizar_proveedor->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos han sido actualizados con éxito",
                    "Tipo" => "success",
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido actualizar los datos",
                    "Tipo" => "error",
                ];
            }
            echo json_encode($alerta);
        } //Fin
    }