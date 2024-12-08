<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['rut_proveedor']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/compraControlador.php";
        $ins_compra = new compraControlador();

        //---------- Agregar una compra ------------//
        if (isset($_POST['rut_proveedor']) && isset($_POST['id_comprobante']) && isset($_POST['detalles']) && isset($_POST['total'])) {
            $resultado = $ins_compra->finalizar_compra_controlador();
            echo $resultado;
        }

        //---------- Eliminar una compra ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_compra->eliminar_compra_controlador();
        }
    } else {
        session_start(['name' => 'SCA']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }