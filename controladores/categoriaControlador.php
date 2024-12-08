<?php

    if ($peticionAjax) {
        require_once "../modelos/categoriaModelo.php";
    } else {
        require_once "./modelos/categoriaModelo.php";
    }

    class categoriaControlador extends categoriaModelo {

        //---------- Controlador para agregar categoria -------------//
        public function agregar_categoria_controlador() {

            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "") {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No haz llenado todos los campos que son obligatorios",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando nombre de la categoria -------------//
            $check_categoria = mainModel::ejecutar_consulta_simple("SELECT nombre FROM categoria WHERE nombre = '$nombre';");
            if ($check_categoria->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La categoría ingresada ya se encuentra registrada en el sistema",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos = [
                "Nombre" => $nombre,
                "Descripcion" => $descripcion
            ];

            $agregar_categoria = categoriaModelo::agregar_categoria_modelo($datos);

            if ($agregar_categoria->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Categoría registrada",
                    "Texto" => "Los datos de la categoría han sido registrados con éxito",
                    "Tipo" => "success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar la categoría",
                    "Tipo" => "error",
                ];
                echo json_encode($alerta);
            }
        } // Fin

        //---------- Controlador para paginar categorias -------------//
        public function paginar_categoria_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";

            $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

            $where = "WHERE id IS NOT NULL";

            if (!empty($filtros['id'])) {
                $where .= " AND id LIKE '%" . $filtros['id'] . "%'";
            }
            if (!empty($filtros['nombre'])) {
                $where .= " AND nombre LIKE '%" . $filtros['nombre'] . "%'";
            }
            if (!empty($filtros['descripcion'])) {
                $where .= " AND descripcion LIKE '%" . $filtros['descripcion'] . "%'";
            }
        
            $orderBy = "";
            if (!empty($filtros['orden'])) {
                $orderBy = " ORDER BY nombre " . $filtros['orden'];
            }
        
            $consulta = "SELECT * FROM categoria $where $orderBy LIMIT $inicio, $registros;";
            $consulta_total = "SELECT COUNT(*) FROM categoria $where;";

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
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
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
                        <td>' . $rows['id'] . '</td>
                        <td>' . $rows['nombre'] . '</td>
                        <td>' . $rows['descripcion'] . '</td>
                        <td>
                            <a href="' . SERVER_URL . 'categoria-update/' . $rows['id'] . '/" class="btn btn-small btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/categoriaAjax.php" method="POST" data-form="delete">
                                <input type="hidden" name="id_eliminar" value="' . $rows['id'] . '">
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
                        <td colspan="4">
                            <a href="' . $url . '1/" class="btn btn-primary">
                                Haga clic acá para recargar el listado
                            </a>
                        </td>
                    </tr>
                    ';
                } else {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="4">No hay registros en el sistema</td>
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
                $tabla .= '<p class="text-left">Mostrando categoría ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        } // Fin
        
        //---------- Controlador eliminar una categoria -----------------//
        public function eliminar_categoria_controlador() {

            // Recibiendo id de la categoria
            $id = $_POST['id_eliminar'];

            //------------ Comprobando la categoria en BD ------------//
            $check_categoria = mainModel::ejecutar_consulta_simple("SELECT id FROM categoria WHERE id = '$id';");
            if ($check_categoria->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'La categoría que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando los productos asociados ------------//
            $check_productos = mainModel::ejecutar_consulta_simple("SELECT id_categoria FROM producto WHERE id_categoria = '$id' LIMIT 1;");
            if ($check_productos->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar esta categoría debido a que tiene productos asociados',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando rol -----------------//
            session_start(['name' => 'SCA']);
            if ($_SESSION['rol_sca'] != "Administrador") {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No tienes los permisos necesarios para realizar esta acción',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_categoria = categoriaModelo::eliminar_categoria_modelo($id);

            if ($eliminar_categoria->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'Categoria eliminada',
                    "Texto" => 'La categoría ha sido eliminada del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar la categoría, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        } //Fin

        //---------- Controlador para obtener una categoria -------------//
        public function obtener_categoria_controlador($id) {
            return categoriaModelo::obtener_categoria_modelo($id);
        } // Fin

        //---------- Controlador para obtener la cantidad de categorias -------------//
        public function obtener_cantidad_categorias_controlador() {
            return categoriaModelo::obtener_cantidad_categorias_modelo();
        } // Fin

        //---------- Controlador para obtener todas las categorias -------------//
        public function obtener_categorias_controlador() {
            return categoriaModelo::obtener_categorias_modelo();
        } // Fin

        //---------- Controlador para actualizar los datos de la categoria -------------//
        public function actualizar_categoria_controlador() {
            
            $id = $_POST['id_actualizar'];

            // Comprobar la categoria en la BD
            $check_categoria = mainModel::ejecutar_consulta_simple("SELECT * FROM categoria WHERE id = '$id'");
            if ($check_categoria->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado la categoria en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_categoria->fetch();
            }

            $nombre = $_POST['nombre_actualizar'];
            $descripcion = $_POST['descripcion_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "") {
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
                "Id" => $id,
                "Nombre" => $nombre,
                "Descripcion" => $descripcion
            ];

            $actualizar_categoria = categoriaModelo::actualizar_categoria_modelo($datos);

            if ($actualizar_categoria->rowCount() == 1) {
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