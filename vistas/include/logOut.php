<script>
    let btn_salir = document.querySelector(".btn-exit-sistem");

    btn_salir.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estas listo para irte?',
            text: 'Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                let url = '<?php echo SERVER_URL; ?>ajax/loginAjax.php';
                let token = '<?php echo $_SESSION['token_sca']; ?>';
                let nombre = '<?php echo $_SESSION['nombre_sca']; ?>';
                
                let datos = new FormData();
                datos.append("token", token);
                datos.append("nombre", nombre);

                fetch(url,{
                    method: 'POST',
                    body: datos
                }).then(respuesta => respuesta.json())
                .then(respuesta => {
                    return alertas_ajax(respuesta);
                });
            }
        });
    });
</script>