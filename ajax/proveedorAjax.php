<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['rut']) || isset($_POST['rut_eliminar']) || isset($_POST['rut_actualizar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/proveedorControlador.php";
        $ins_proveedor = new proveedorControlador();

        //---------- Agregar un proveedor ------------//
        if (isset($_POST['rut']) && isset($_POST['nombre']) && isset($_POST['contacto']) && isset($_POST['direccion'])) {
            echo $ins_proveedor->agregar_proveedor_controlador();
        }

        //---------- Eliminar un proveedor ------------//
        if (isset($_POST['rut_eliminar'])) {
            echo $ins_proveedor->eliminar_proveedor_controlador();
        }

        //---------- Actualizar un proveedor ------------//
        if (isset($_POST['rut_actualizar'])) {
            echo $ins_proveedor->actualizar_proveedor_controlador();
        }
    } else {
        session_start(['name' => 'SCA']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }