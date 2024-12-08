<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['cedula']) || isset($_POST['cedula_eliminar']) || isset($_POST['cedula_actualizar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/clienteControlador.php";
        $ins_cliente = new clienteControlador();

        //---------- Agregar un cliente ------------//
        if (isset($_POST['cedula']) && isset($_POST['nombre']) && isset($_POST['apellidos']) && isset($_POST['telefono']) && isset($_POST['direccion'])) {
            echo $ins_cliente->agregar_cliente_controlador();
        }

        //---------- Eliminar un cliente ------------//
        if (isset($_POST['cedula_eliminar'])) {
            echo $ins_cliente->eliminar_cliente_controlador();
        }

        //---------- Actualizar un cliente ------------//
        if (isset($_POST['cedula_actualizar'])) {
            echo $ins_cliente->actualizar_cliente_controlador();
        }
    } else {
        session_start(['name' => 'SCA']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }