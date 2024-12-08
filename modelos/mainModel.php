<?php

    if ($peticionAjax) {
        require_once "../config/SERVER.php";
    } else {
        require_once "./config/SERVER.php";
    }

    class mainModel {

        //---------- Función para conectar a BD ----------//
        protected static function conectar() {
            $conexion = new PDO(SGBD, USER_NAME, PASSWORD);

            $conexion->exec("SET CHARACTER SET utf8");
            
            return $conexion;
        }

        //---------- Función para ejecutar consultas simples ----------//
        protected static function ejecutar_consulta_simple($consulta) {

            $sql = self::conectar()->prepare($consulta);
            
            $sql->execute();
            
            return $sql;
        }

        // Función para paginar tablas
        protected static function paginador_tablas($pagina, $n_paginas, $url, $botones) {

            $tabla = '
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">';

            // Botón "Anterior"
            if ($pagina == 1) {
                $tabla .= '
                        <li class="page-item disabled">
                            <a class="page-link">Anterior</a>
                        </li>';
            } else {
                $tabla .= '
                        <li class="page-item">
                            <a class="page-link" href="' . $url . ($pagina - 1) . '/">Anterior</a>
                        </li>';
            }

            // Páginas intermedias
            $botones_izquierda = floor($botones / 2);
            $botones_derecha = $botones - $botones_izquierda - 1;

            $inicio = max(1, $pagina - $botones_izquierda);
            $fin = min($n_paginas, $pagina + $botones_derecha);

            // Ajustar inicio y fin si estamos cerca del final o inicio de las páginas
            if ($inicio == 1) {
                $fin = min($n_paginas, $botones);  // Mostrar las primeras páginas si estamos al principio
            } elseif ($fin == $n_paginas) {
                $inicio = max(1, $n_paginas - $botones + 1);  // Mostrar las últimas páginas si estamos al final
            }

            for ($i = $inicio; $i <= $fin; $i++) {
                if ($pagina == $i) {
                    $tabla .= '
                        <li class="page-item active">
                            <a class="page-link">' . $i . '</a>
                        </li>';
                } else {
                    $tabla .= '
                        <li class="page-item">
                            <a class="page-link" href="' . $url . $i . '/">' . $i . '</a>
                        </li>';
                }
            }

            // Botón "Siguiente"
            if ($pagina == $n_paginas) {
                $tabla .= '
                        <li class="page-item disabled">
                            <a class="page-link">Siguiente</a>
                        </li>';
            } else {
                $tabla .= '
                        <li class="page-item">
                            <a class="page-link" href="' . $url . ($pagina + 1) . '/">Siguiente</a>
                        </li>';
            }

            $tabla .= '
                    </ul>
                </nav>';
            return $tabla;
        }
    }