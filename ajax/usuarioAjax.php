<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['nombre'])  || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();

        //---------- Agregar un usuario ------------//
        if (isset($_POST['nombre']) && isset($_POST['contrasena']) && isset($_POST['rol'])) {
            echo $ins_usuario->agregar_usuario_controlador();
        }

        //---------- Eliminar un usuario ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_usuario->eliminar_usuario_controlador();
        }

        //---------- Actualizar un usuario ------------//
        if (isset($_POST['id_actualizar'])) {
            echo $ins_usuario->actualizar_usuario_controlador();
        }
    } else {
        session_start(['name' => 'SCA']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }