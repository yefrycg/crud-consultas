<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['cliente_id']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/ventaControlador.php";
        $ins_venta = new ventaControlador();

        //---------- Agregar una venta ------------//
        // Agregar una venta
        if (isset($_POST['cliente_id']) && isset($_POST['vendedor_id']) && isset($_POST['detalles']) && isset($_POST['total'])) {
            $resultado = $ins_venta->finalizar_venta_controlador();
            echo $resultado;
        }

        //---------- Eliminar una venta ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_venta->eliminar_venta_controlador();
        }
    } else {
        session_start(['name' => 'SCA']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }