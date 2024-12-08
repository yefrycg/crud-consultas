<?php

    $peticionAjax = true;

    require_once "../config/APP.php";

    if (isset($_POST['nombre']) || isset($_POST['id_eliminar']) || isset($_POST['id_actualizar'])) {
        
        //---------- Instancia al controlador ------------//
        require_once "../controladores/categoriaControlador.php";
        $ins_categoria = new categoriaControlador();

        //---------- Agregar una categoria ------------//
        if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            echo $ins_categoria->agregar_categoria_controlador();
        }

        //---------- Eliminar una categoria ------------//
        if (isset($_POST['id_eliminar'])) {
            echo $ins_categoria->eliminar_categoria_controlador();
        }

        //---------- Actualizar una categoria ------------//
        if (isset($_POST['id_actualizar'])) {
            echo $ins_categoria->actualizar_categoria_controlador();
        }
    } else {
        session_start(['name' => 'SCA']);
        session_unset();
        session_destroy();
        header("Location: " . SERVER_URL . "login/");
        exit();
    }