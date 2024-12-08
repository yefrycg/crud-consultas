<?php

    if ($peticionAjax) {
        require_once "../modelos/productoModelo.php";
    } else {
        require_once "./modelos/productoModelo.php";
    }

    class productoControlador extends productoModelo {

        //---------- Controlador para agregar un producto -------------//
        public function agregar_producto_controlador() {

            $id_categoria = $_POST['id_categoria'];
            $nombre = $_POST['nombre'];
            $unidad_medida = $_POST['unidad_medida'];

            //---------- Comprobar campos vacios ------------//
            if ($id_categoria == "" || $nombre == "" || $unidad_medida == "") {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No haz llenado todos los campos que son obligatorios",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando el nombre del producto -------------//
            $check_producto = mainModel::ejecutar_consulta_simple("SELECT nombre FROM producto WHERE nombre = '$nombre';");
            if ($check_producto->rowCount() > 0) {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El producto ingresado ya se encuentra registrado en el sistema",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_producto = [
                "Id_categoria"=>$id_categoria,
                "Nombre"=>$nombre,
                "Unidad_medida"=>$unidad_medida
            ];

            $agregar_producto = productoModelo::agregar_producto_modelo($datos_producto);

            if ($agregar_producto->rowCount() == 1) {
                $alerta = [
                    "Alerta"=>"limpiar",
                    "Titulo"=>"producto registrado",
                    "Texto"=>"Los datos del producto han sido registrados con éxito",
                    "Tipo"=>"success",
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No hemos podido registrar al producto",
                    "Tipo"=>"error",
                ];
                echo json_encode($alerta);
            }
            
        }

        //---------- Controlador para paginar productos -------------//
        public function paginar_producto_controlador($pagina, $registros, $url, $filtros) {

            $url = SERVER_URL . $url . "/";
            $tabla = "";
            
            $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
            $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
            
            $where = "WHERE 1=1"; // Condición base
            
            // Procesar filtros básicos
            if (isset($filtros['nombre']) && $filtros['nombre'] != "") {
                $where .= " AND producto.nombre LIKE '%" . $filtros['nombre'] . "%'";
            }
            if (isset($filtros['id_categoria']) && $filtros['id_categoria'] != "") {
                $where .= " AND producto.id_categoria = '" . $filtros['id_categoria'] . "'";
            }
            if (isset($filtros['precio_min']) && $filtros['precio_min'] != "") {
                $where .= " AND producto.precio_actual >= " . $filtros['precio_min'];
            }
            if (isset($filtros['precio_max']) && $filtros['precio_max'] != "") {
                $where .= " AND producto.precio_actual <= " . $filtros['precio_max'];
            }
            if (isset($filtros['unidad_medida']) && $filtros['unidad_medida'] != "") {
                $where .= " AND producto.unidad_medida = '" . $filtros['unidad_medida'] . "'";
            }
            if (isset($filtros['stock_min']) && $filtros['stock_min'] != "") {
                $where .= " AND producto.stock_actual >= " . $filtros['stock_min'];
            }
            if (isset($filtros['stock_max']) && $filtros['stock_max'] != "") {
                $where .= " AND producto.stock_actual <= " . $filtros['stock_max'];
            }
            
            // Filtros avanzados para stock y precio
            if (isset($filtros['stock']) && $filtros['stock'] != "") {
                $subWhere = str_replace("producto.", "p.", $where); // Adaptar alias para la subconsulta
                if ($filtros['stock'] == "Mayor") {
                    $where .= " AND producto.stock_actual = (SELECT MAX(p.stock_actual) FROM producto AS p " . $subWhere . ")";
                } else {
                    $where .= " AND producto.stock_actual = (SELECT MIN(p.stock_actual) FROM producto AS p " . $subWhere . ")";
                }
            }
            
            if (isset($filtros['precio']) && $filtros['precio'] != "") {
                $subWhere = str_replace("producto.", "p.", $where); // Adaptar alias para la subconsulta
                if ($filtros['precio'] == "Mayor") {
                    $where .= " AND producto.precio_actual = (SELECT MAX(p.precio_actual) FROM producto AS p " . $subWhere . ")";
                } else {
                    $where .= " AND producto.precio_actual = (SELECT MIN(p.precio_actual) FROM producto AS p " . $subWhere . ")";
                }
            }
            
            $consulta = "SELECT producto.*, categoria.nombre AS nombre_categoria 
            FROM producto 
            INNER JOIN categoria ON producto.id_categoria = categoria.id 
            $where 
            LIMIT $inicio, $registros;";
            
            $consulta_total = "SELECT COUNT(*) AS total FROM producto 
            INNER JOIN categoria ON producto.id_categoria = categoria.id 
            $where;";            

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
                            <th scope="col">Código</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Precio Actual</th>
                            <th scope="col">Unidad de Medida</th>
                            <th scope="col">Stock Actual</th>
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
                        <td>' . $rows['codigo'] . '</td>
                        <td>' . $rows['nombre'] . '</td>
                        <td>' . $rows['nombre_categoria'] . '</td>
                        <td>' . $rows['precio_actual'] . '</td>
                        <td>' . $rows['unidad_medida'] . '</td>
                        <td>' . $rows['stock_actual'] . '</td>
                        <td>
                            <a href="' . SERVER_URL . 'producto-update/' . $rows['codigo'] . '/" class="btn btn-small btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form class="FormularioAjax d-inline-block" action="' . SERVER_URL . 'ajax/productoAjax.php" method="POST" data-form="delete">
                                <input type="hidden" name="codigo_eliminar" value="' . $rows['codigo'] . '">
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
                        <td colspan="7">
                            <a href="' . $url . '1/" class="btn btn-primary">
                                Haga clic acá para recargar el listado
                            </a>
                        </td>
                    </tr>
                    ';
                } else {
                    $tabla .= '
                    <tr class="text-center">
                        <td colspan="7">No hay registros en el sistema</td>
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
                $tabla .= '<p class="text-left">Mostrando producto ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . '</p>';
                $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 10);
            }

            return $tabla;
        } // Fin

        //---------- Controlador eliminar un producto -----------------//
        public function eliminar_producto_controlador() {

            // Recibiendo codigo del producto
            $codigo = $_POST['codigo_eliminar'];

            //------------ Comprobando el producto en BD ------------//
            $check_producto = mainModel::ejecutar_consulta_simple("SELECT codigo FROM producto WHERE codigo = '$codigo';");
            if ($check_producto->rowCount() == 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'El producto que intenta eliminar no existe en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            //------------ Comprobando las compras y ventas asociadas ------------//
            $check_compras = mainModel::ejecutar_consulta_simple("SELECT cod_producto FROM compra_producto WHERE cod_producto = '$codigo' LIMIT 1;");
            $check_ventas = mainModel::ejecutar_consulta_simple("SELECT cod_producto FROM venta_producto WHERE cod_producto = '$codigo' LIMIT 1;");
            if ($check_compras->rowCount() > 0 && $check_ventas->rowCount() > 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No podemos eliminar este producto debido a que tiene compras y/o ventas asociadas',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            }

            $eliminar_producto = productoModelo::eliminar_producto_modelo($codigo);

            if ($eliminar_producto->rowCount() == 1) {
                $alerta = [
                    "Alerta" => 'recargar',
                    "Titulo" => 'producto eliminado',
                    "Texto" => 'El producto ha sido eliminado del sistema exitosamente',
                    "Tipo" => 'success'
                ];
            } else {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos podido eliminar al producto, intentelo nuevamente',
                    "Tipo" => 'error'
                ];
            }
            echo json_encode($alerta);
        } //Fin

        //---------- Controlador para obtener un producto -------------//
        public function obtener_producto_controlador($codigo) {
            return productoModelo::obtener_producto_modelo($codigo);
        } // Fin

        //---------- Controlador para obtener la cantidad de productos registrados -------------//
        public function obtener_cantidad_productos_controlador() {
            return productoModelo::obtener_cantidad_productos_modelo();
        } // Fin

        //---------- Controlador para obtener todos los productos -------------//
        public function obtener_productos_controlador() {
            return productoModelo::obtener_productos_modelo();
        } // Fin

        //---------- Controlador para actualizar los datos del producto -------------//
        public function actualizar_producto_controlador() {

            $codigo = $_POST['codigo_actualizar'];

            // Comprobar al producto en la BD
            $check_producto = mainModel::ejecutar_consulta_simple("SELECT * FROM producto WHERE codigo = '$codigo'");
            if ($check_producto->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => 'simple',
                    "Titulo" => 'Ocurrió un error inesperado',
                    "Texto" => 'No hemos encontrado al producto en el sistema',
                    "Tipo" => 'error'
                ];
                echo json_encode($alerta);
                exit();
            } else {
                $datos = $check_producto->fetch();
            }

            $id_categoria = $_POST['categoria_actualizar'];
            $nombre = $_POST['nombre_actualizar'];
            $unidad_medida = $_POST['unidad_medida_actualizar'];

            //---------- Comprobar campos vacios ------------//
            if ($nombre == "" || $unidad_medida == "" || $id_categoria == "") {
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
                "Codigo" => $codigo,
                "Id_categoria" => $id_categoria,
                "Nombre" => $nombre,
                "Unidad_medida" => $unidad_medida
            ];

            $actualizar_producto = productoModelo::actualizar_producto_modelo($datos);

            if ($actualizar_producto->rowCount() == 1) {
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