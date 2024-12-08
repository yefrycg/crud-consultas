<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['id_categoria']) || isset($_POST['codigo_eliminar']) || isset($_POST['codigo_actualizar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/productoControlador.php";
        $ins_producto = new productoControlador();

        //---------- Agregar un producto ------------//
        if (isset($_POST['id_categoria']) && isset($_POST['nombre']) && isset($_POST['unidad_medida'])) {
            echo $ins_producto->agregar_producto_controlador();
        }

        //---------- Eliminar un producto ------------//
        if (isset($_POST['codigo_eliminar'])) {
            echo $ins_producto->eliminar_producto_controlador();
        }

        //---------- Actualizar un producto ------------//
        if (isset($_POST['codigo_actualizar'])) {
            echo $ins_producto->actualizar_producto_controlador();
        }
    } else {
        session_start(['name' => 'SCA']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }