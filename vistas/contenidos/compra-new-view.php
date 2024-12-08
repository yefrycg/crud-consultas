<!-- Registro de compras -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Registrar Compra</h1>
    <div class="row gy-4">

        <!------Compra producto---->
        <div class="col-xl-8 mb-4">
            <div class="text-white bg-primary p-1 text-center">
                Detalles de la Compra
            </div>
            <div class="p-3 border border-3 border-primary">
                <div class="row">

                    <!-----Producto---->
                    <div class="col-12 mb-4">
                        <label for="producto_id">Busque un producto aquí:</label>
                        <div class="dropdown bootstrap-select">
                            <select name="producto_id" id="producto_id" class="form-control selectpicker" data-live-search="true" data-size="1" title="Busque un producto aquí" required>
                                <?php
                                require_once "./controladores/productoControlador.php";
                                require_once "./controladores/categoriaControlador.php";
                                $ins_producto = new productoControlador();
                                $ins_categoria = new categoriaControlador();
                                $productos = $ins_producto->obtener_productos_controlador();

                                if ($productos) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($productos as $producto) {
                                        $categoria_obj = $ins_categoria->obtener_categoria_controlador($producto->id_categoria)->fetch(PDO::FETCH_OBJ);
                                        $nombre_categoria = $categoria_obj ? $categoria_obj->nombre : "Sin categoría";

                                        echo '<option value="' . $producto->codigo . '"
                                        data-unidad="' . $producto->unidad_medida . '"
                                        data-categoria="' . $nombre_categoria . '">'
                                            . $producto->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay productos disponibles</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class="col-6 mb-4">
                        <label for="categoria">Categoría:</label>
                        <input disabled name="categoria" id="categoria" type="text" class="form-control">
                    </div>

                    <!-- Unidad de Medida -->
                    <div class="col-6 mb-4">
                        <label for="unidad">Unidad de medida:</label>
                        <input disabled name="unidad" id="unidad" type="text" class="form-control">
                    </div>

                    <!-----Cantidad---->
                    <div class="col-sm-4 mb-4">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="0" step="0.00" value="0.00" required>
                    </div>

                    <!-----Precio de compra---->
                    <div class="col-sm-4 mb-4">
                        <label for="precio_compra" class="form-label">Precio de compra:</label>
                        <input type="number" name="precio_compra" id="precio_compra" class="form-control" min="0" step="0.00" value="0.00" required>
                    </div>

                    <!-----Precio de venta---->
                    <div class="col-sm-4 mb-4">
                        <label for="precio_venta" class="form-label">Precio de venta:</label>
                        <input type="number" name="precio_venta" id="precio_venta" class="form-control" min="0" step="0.00" value="0.00" required>
                    </div>

                    <!-----botón para agregar---->
                    <div class="col-12 mb-4 text-end">
                        <button id="btn_agregar" class="btn btn-primary" type="submit">Agregar</button>
                    </div>

                    <!-----Tabla para el detalle de la compra---->
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="tabla_detalle" class="table table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white">#</th>
                                        <th class="text-white">Producto</th>
                                        <th class="text-white">Cantidad</th>
                                        <th class="text-white">Precio compra</th>
                                        <th class="text-white">Precio venta</th>
                                        <th class="text-white">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th colspan="4">Sumas</th>
                                        <th colspan="2"><span id="sumas">0</span></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th colspan="4">IGV %</th>
                                        <th colspan="2"><span id="igv">0</span></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th colspan="4">Total</th>
                                        <th colspan="2"> <input type="hidden" name="total" value="0" id="inputTotal"> <span id="total">0</span></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-----Compra---->
        <div class="col-xl-4">
            <div class="text-white bg-success p-1 text-center">
                Datos Generales
            </div>

            <!-- Formulario de compra -->
            <div class="p-3 border border-3 border-success">
                <form class="FormularioAjax" action="<?php echo SERVER_URL; ?>ajax/compraAjax.php" method="POST" data-form="save" autocomplete="off">
                    
                    <input type="hidden" name="rut_proveedor" id="rut_proveedor_hidden">
                    <input type="hidden" name="id_comprobante" id="id_comprobante_hidden">
                    <input type="hidden" name="detalles" id="detalles_hidden">
                    <input type="hidden" name="total" id="total_hidden">

                    <!--Proveedor-->
                    <div class="col-12 mb-4">
                        <label for="rut_proveedor">Proveedor:</label>
                        <div class="dropdown bootstrap-select">
                            <select name="rut_proveedor" id="rut_proveedor" class="form-control selectpicker" data-live-search="true" title="Selecciona" data-size="2" required>
                                <?php
                                require_once "./controladores/proveedorControlador.php";
                                $ins_proveedor = new proveedorControlador();
                                $proveedores = $ins_proveedor->obtener_proveedores_controlador();

                                if ($proveedores) {
                                    echo '<option value="">Seleccione</option>';
                                    foreach ($proveedores as $proveedor) {
                                        echo '<option value="' . $proveedor->rut . '">' . $proveedor->nombre . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No hay proveedores registrados</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!--Tipo de comprobante-->
                    <div class="col-12 mb-4">
                        <label for="comprobante_id">Comprobante:</label>
                        <div class="dropdown bootstrap-select">
                            <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker" title="Selecciona" required>
                                <option class="bs-title-option" value="">Seleccione</option>
                                <option value="Boleta">Boleta</option>
                                <option value="Factura">Factura</option>
                            </select>
                        </div>
                    </div>

                    <!--Impuesto---->
                    <div class="col-sm-6 mb-4">
                        <label for="impuesto" class="form-label">Impuesto(IGV):</label>
                        <input readonly value="19%" type="text" name="impuesto" id="impuesto" class="form-control border-success">
                    </div>

                    <!--Fecha--->
                    <div class="col-sm-6 mb-4">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input readonly type="date" name="fecha" id="fecha" class="form-control border-success" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                    
                    <!--Botones--->
                    <div class="col-12 mt-4 text-center">
                        <button type="submit" class="btn btn-success" id="guardar">Finalizar compra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let detalles = [];
    let subtotal = 0;
    const impuestoPorcentaje = 0.19;

    // Actualizar campos dinámicos al seleccionar un producto
    document.getElementById("producto_id").addEventListener("change", function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById("unidad").value = selectedOption.getAttribute("data-unidad") || "";
        document.getElementById("categoria").value = selectedOption.getAttribute("data-categoria") || "";
    });


    document.getElementById("btn_agregar").addEventListener("click", function() {
        const productoId = document.getElementById("producto_id").value;
        const cantidad = parseFloat(document.getElementById("cantidad").value);
        const precioCompra = parseFloat(document.getElementById("precio_compra").value);
        const precioVenta = parseFloat(document.getElementById("precio_venta").value);

        if (productoId && cantidad > 0 && precioCompra > 0 && precioVenta > 0) {
            const productoNombre = document.querySelector(`#producto_id option[value="${productoId}"]`).textContent;
            const subtotalItem = cantidad * precioCompra;

            detalles.push({
                productoId,
                productoNombre,
                cantidad,
                precioCompra,
                precioVenta,
                subtotalItem
            });

            subtotal += subtotalItem;
            actualizarTabla();
        } else {
            alert("Por favor, complete todos los campos correctamente.");
        }
    });

    function actualizarTabla() {
        const tabla = document.getElementById("tabla_detalle").getElementsByTagName("tbody")[0];
        tabla.innerHTML = ""; // Limpiar tabla

        detalles.forEach((detalle, index) => {
            const row = tabla.insertRow();
            row.innerHTML = `
            <td>${index + 1}</td>
            <td>${detalle.productoNombre}</td>
            <td>${detalle.cantidad}</td>
            <td>${detalle.precioCompra.toFixed(2)}</td>
            <td>${detalle.precioVenta.toFixed(2)}</td>
            <td>${detalle.subtotalItem.toFixed(2)}</td>
            <td><button class="btn btn-danger btn-sm" onclick="eliminarDetalle(${index})">Eliminar</button></td>
        `;
        });

        const impuesto = subtotal * impuestoPorcentaje;
        const total = subtotal + impuesto;

        document.getElementById("sumas").textContent = subtotal.toFixed(2);
        document.getElementById("igv").textContent = impuesto.toFixed(2);
        document.getElementById("total").textContent = total.toFixed(2);
        document.getElementById("inputTotal").value = subtotal.toFixed(2);
    }

    function eliminarDetalle(index) {
        subtotal -= detalles[index].subtotalItem;
        detalles.splice(index, 1);
        actualizarTabla();
    }

    document.getElementById("guardar").addEventListener("click", async function(e) {
        e.preventDefault();

        const rutProveedor = document.getElementById("rut_proveedor").value;
        const idComprobante = document.getElementById("comprobante_id").value;
        const total = document.getElementById("inputTotal").value;

        if (detalles.length === 0) {
            Swal.fire({
                title: "Error",
                text: "Debe agregar al menos un producto.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            return;
        }

        // Rellenar los campos ocultos del formulario
        document.getElementById("rut_proveedor_hidden").value = rutProveedor;
        document.getElementById("id_comprobante_hidden").value = idComprobante;
        document.getElementById("detalles_hidden").value = JSON.stringify(detalles);
        document.getElementById("total_hidden").value = total;

        // Configuración de la solicitud AJAX
        const form = document.querySelector(".FormularioAjax");
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: formData
            });

            const result = await response.json();

            if (result.Alerta === "limpiar") {
                Swal.fire({
                    title: result.Titulo,
                    text: result.Texto,
                    icon: result.Tipo,
                    confirmButtonText: "Aceptar"
                }).then(() => {
                    window.location.reload(); // Recargar la página después de éxito
                });
            } else {
                Swal.fire({
                    title: result.Titulo,
                    text: result.Texto,
                    icon: result.Tipo,
                    confirmButtonText: "Aceptar"
                });
            }
        } catch (error) {
            console.error("Error al procesar la solicitud:", error);
            Swal.fire({
                title: "Error inesperado",
                text: "No se pudo completar la solicitud. Intente nuevamente.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
        }
    });
</script>