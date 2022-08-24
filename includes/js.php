<script>
    //Función buscar registros de la venta en tiempo real
    $(document).ready(function() {
        //Datepicker
        $(function() {
            $("#datepicker").datepicker();
        });

        //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
        $("#foto").on("change", function() {
            let uploadFoto = document.getElementById("foto").value,
                foto = document.getElementById("foto").files,
                nav = window.URL || window.webkitURL,
                contactAlert = document.getElementById("form_alert");

            if (uploadFoto != "") {
                let type = foto[0].type,
                    name = foto[0].name;
                if (type != "image/jpeg" && type != "image/jpg" && type != "image/png") {
                    contactAlert.innerHTML =
                        '<p class="errorArchivo">El archivo no es válido.</p>';
                    $("#img").remove();
                    $(".delPhoto").addClass("notBlock");
                    $("#foto").val("");
                    return false;
                } else {
                    contactAlert.innerHTML = "";
                    $("#img").remove();
                    $(".delPhoto").removeClass("notBlock");
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    $(".prevPhoto").append("<img id='img' src=" + objeto_url + ">");
                    $(".upimg label").remove();
                }
            } else {
                alert("No selecciono foto");
                $("#img").remove();
            }
        });

        $(".delPhoto").click(function() {
            $("#foto").val("");
            $(".delPhoto").addClass("notBlock");
            $("#img").remove();
        });

        //Busqueda de productos si e sque no se ha procesado la venta
        let userEmail = "<?php echo $_SESSION['email'] ?>";
        searchForDetalle(userEmail);

        // Mostar formulario de registro de cliente.
        $("#btn_new_cliente_form").click(function(e) {
            e.preventDefault();
            $("#nom_cliente").removeAttr("disabled");
            $("#tel_cliente").removeAttr("disabled");
            $("#dir_cliente").removeAttr("disabled");
            $("#div_registro_cliente").slideDown();
            $("#div_registro_cliente_total").slideDown();
            $("#btn_new_cliente").slideDown();
        });

        //Buscar producto
        $("#txt_cod_producto").keyup(function(e) {
            e.preventDefault();
            let producto = $(this).val(),
                action = "infoProducto";
            if (producto != "") {
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {
                        action: action,
                        producto: producto,
                    },
                    async: true,
                    success: function(response) {
                        if (response != "error") {
                            const info = JSON.parse(response);
                            $("#txt_nombre_producto").html(info.nombre_producto);
                            $("#txt_existencia").html(info.cantidad);
                            $("#txt_cant_producto").val("1");
                            $("#txt_precio").html(info.precio);
                            $("#txt_precio_total").html(info.precio);
                            //Activar campo de cantidad
                            $("#txt_cant_producto").removeAttr("disabled");
                            //Mostrar botón de agregar
                            $("#add_product_venta").slideDown();
                        } else {
                            $("#txt_nombre_producto").html("-");
                            $("#txt_existencia").html("-");
                            $("#txt_cant_producto").val("0");
                            $("#txt_precio").html("$0.00");
                            $("#txt_precio_total").html("$0.00");
                            //Desactivar campo de cantidad
                            $("#txt_cant_producto").attr("disabled", "disabled");
                            //Mostrar botón de agregar
                            $("#add_product_venta").slideUp();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    },
                });
            }
        });

        //Validar cantidad del producto antes de agregar
        $("#txt_cant_producto").keyup(function(e) {
            e.preventDefault();
            let precio_total = parseFloat($(this).val() * $("#txt_precio").html()),
                existencia = parseInt($("#txt_existencia").html());
            $("#txt_precio_total").html("$" + precio_total);
            //Ocultar botón de agregar si la cantidad es menor que 1
            if (
                $(this).val() < 1 ||
                isNaN($(this).val()) ||
                $(this).val() > existencia
            ) {
                $("#add_product_venta").slideUp();
            } else {
                $("#add_product_venta").slideDown();
            }
        });

        //Botón de "agregar", agregar producto al detalle de la venta
        $("#add_product_venta").click(function(e) {
            e.preventDefault();
            if ($("#txt_cant_producto").val() > 0) {
                let codproducto = $("#txt_cod_producto").val(),
                    cantidad = $("#txt_cant_producto").val(),
                    action = "addProductDetalle";
                $.ajax({
                    type: "POST",
                    url: "ajax_venta_detalles.php",
                    async: true,
                    data: {
                        action: action,
                        producto: codproducto,
                        cantidad: cantidad,
                    },
                    success: function(response) {
                        if (response != "error") {
                            const info = JSON.parse(response);
                            $("#detalle_venta").html(info.detalle);
                            $("#detalle_totales").html(info.totales);

                            $("#txt_cod_producto").val("");
                            $("#txt_nombre_producto").html("-");
                            $("#txt_existencia").html("-");
                            $("#txt_cant_producto").val("0");
                            $("#txt_precio").html("0.00");
                            $("#txt_precio_total").html("0.00");
                            //Desactivar campo de cantidad
                            $("#txt_cant_producto").attr("disabled", "disabled");
                            //Ocultar botón de agregar
                            $("#add_product_venta").slideUp();
                        } else {
                            console.log("No data");
                        }
                        viewProcesar();
                    },
                    error: function(error) {
                        //console.log(error);
                    },
                });
            }
        });

        //Anular venta
        $("#btn_anular_venta").click(function(e) {
            e.preventDefault();
            let rows = $("#detalle_venta tr").length;
            if (rows > 0) {
                let action = "anularVenta";
                $.ajax({
                    type: "POST",
                    url: "ajax_anular_venta.php",
                    async: true,
                    data: {
                        action: action,
                    },
                    success: function(response) {
                        if (response != "error") {
                            location.reload();
                        }
                    },
                });
            }
        });

        //Buscar cliente.
        $("#rfc_cliente").keyup(function(e) {
            e.preventDefault();
            let cl = $(this).val(),
                action = "searchCliente";
            $.ajax({
                type: "POST",
                async: true,
                url: "ajax_buscar_cliente.php",
                data: {
                    action: action,
                    cliente: cl,
                },
                success: function(response) {
                    if (response == 0) {
                        $("#idcliente").val("");
                        $("#nom_cliente").val("");
                        $("#dir_cliente").val("");
                        $("#tel_cliente").val("");
                        $("#div_registro_cliente_total").slideDown();
                        $("#btn_new_cliente_form").slideDown();
                    } else {
                        const data = JSON.parse(response);
                        $("#idcliente").val(data.id);
                        $("#nom_cliente").val(data.nombre);
                        $("#dir_cliente").val(data.direccion);
                        $("#tel_cliente").val(data.tel);
                        $("#div_registro_cliente_total").slideDown();
                        $("#btn_new_cliente").slideUp();
                        $("#btn_new_cliente_form").slideUp();

                        $("#nom_cliente").attr("disabled", "disabled");
                        $("#tel_cliente").attr("disabled", "disabled");
                        $("#dir_cliente").attr("disabled", "disabled");
                    }
                },
            });
        });

        //Crear cliente en ventas para la factura UnU
        $("#form_new_cliente_venta").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                async: true,
                url: "ajax_agregar_cliente.php",
                data: $("#form_new_cliente_venta").serialize(),
                success: function(response) {
                    console.log(response);
                    if (response != "error") {
                        $("#idcliente").val(response);
                        $("#nom_cliente").attr("disabled", "disabled");
                        $("#tel_cliente").attr("disabled", "disabled");
                        $("#dir_cliente").attr("disabled", "disabled");
                        $("#btn_new_cliente").slideUp();
                        $("#btn_new_cliente_form").slideUp();
                    }
                },
            });
        });

        //facturar venta.
        $("#btn_facturar_venta").click(function(e) {
            e.preventDefault();
            let rows = $("#detalle_venta tr").length;
            if (rows > 0) {
                const action = "procesarVenta",
                    codCliente = $("#idcliente").val();
                $.ajax({
                    type: "POST",
                    url: "ajax_procesar_venta.php",
                    async: true,
                    data: {
                        action: action,
                        codCliente: codCliente,
                    },
                    success: function(response) {
                        if (response != "error") {
                            const info = JSON.parse(response);
                            console.log(info);
                            generarPDF(info.id_cliente, info.id_factura);
                            location.reload();
                        } else {
                            console.log("no data");
                        }
                    },
                });
            }
        });

        //Modal form anular factura
        $(".anular_factura").click(function(e) {
            e.preventDefault();
            let noFactura = $(this).attr("fac"),
                action = "infoFactura";
            $.ajax({
                type: "POST",
                url: "ajax_anular_factura.php",
                data: {
                    noFactura: noFactura,
                    action: action,
                },
                async: true,
                success: function(response) {
                    if (response != "error") {
                        const info = JSON.parse(response);
                        $("#nofactura").html("No." + info.id_factura);
                        $("#totalFactura").html("Total: $" + info.total_factura);
                        $("#fechaFactura").html("Fecha: " + info.fecha);
                        $("#no_factura").val(info.id_factura);
                    }
                },
            });
        });

        //Ver factura
        $(".view_factura").click(function(e) {
            e.preventDefault();
            let codCliente = $(this).attr("cl"),
                noFactura = $(this).attr("f");
            generarPDF(codCliente, noFactura);
        });

        //Agregar productos desde modal
        $(".add_product").click(function(e) {
            e.preventDefault();
            const producto = $(this).attr("product"),
                action = "infoProducto";
            $.ajax({
                url: "ajax.php",
                type: "POST",
                async: true,
                data: {
                    action: action,
                    producto: producto,
                },
                success: function(response) {
                    if (response != "error") {
                        const info = JSON.parse(response);
                        $("#producto_id").val("" + info.id);
                        $(".nameProducto").html("Producto: " + info.nombre_producto);
                    }
                },
                error: function(error) {
                    console.log(error);
                },
            });
        });

        //Búsqueda de productos por medio de la categoría.
        $("#search_categoria").change(function(e) {
            e.preventDefault();
            let sistema = getUrl();
            location.href = sistema + "buscar_productos.php?categoria=" + $(this).val();
        });

        //Búsqueda de usuario por medio de la categoría.
        $("#rol_search").change(function(e) {
            e.preventDefault();
            let sistema = getUrl();
            location.href = sistema + "buscar_usuario.php?categoria=" + $(this).val();
        });
    }); //Fin del ready.

    //Búsqueda de productos por medio de la categoría.
    function getUrl() {
        let loc = window.location,
            pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf("/") + 1);
        return loc.href.substring(
            0,
            loc.href.length -
            ((loc.pathname + loc.search + loc.hash).length - pathName.length)
        );
    }

    //mantener datos si es que se cambia de pestaña y si aún no se ha finalizado la venta unu
    function searchForDetalle(emailCajero) {
        let action = "searchForDetalle",
            user = emailCajero;
        $.ajax({
            type: "POST",
            url: "ajax_buscar_detalle.php",
            async: true,
            data: {
                action: action,
                user: user,
            },
            success: function(response) {
                if (response != "error") {
                    const info = JSON.parse(response);
                    $("#detalle_venta").html(info.detalle);
                    $("#detalle_totales").html(info.totales);
                } else {
                    console.log("No data");
                }
                viewProcesar();
            },
        });
    }

    //Eliminar detalles del producto.
    function del_product_datalle(correlativo) {
        let action = "delProductoDetalle",
            id_detalle = correlativo;
        $.ajax({
            type: "POST",
            url: "ajax_eliminar_detalle.php",
            async: true,
            data: {
                action: action,
                id_detalle: id_detalle,
            },
            success: function(response) {
                if (response != "error") {
                    const info = JSON.parse(response);
                    $("#detalle_venta").html(info.detalle);
                    $("#detalle_totales").html(info.totales);

                    $("#txt_cod_producto").val("");
                    $("#txt_nombre_producto").html("-");
                    $("#txt_existencia").html("-");
                    $("#txt_cant_producto").val("0");
                    $("#txt_precio").html("0.00");
                    $("#txt_precio_total").html("0.00");
                    //Desactivar campo de cantidad
                    $("#txt_cant_producto").attr("disabled", "disabled");
                    //Ocultar botón de agregar
                    $("#add_product_venta").slideUp();
                } else {
                    $("#detalle_venta").html("");
                    $("#detalle_totales").html("");
                }
                viewProcesar();
            },
        });
    }

    //Mostrar/Ocultar botón de procesar.
    function viewProcesar() {
        if ($("#detalle_venta tr").length > 0) {
            $("#btn_facturar_venta").show();
        } else {
            $("#btn_facturar_venta").hide();
        }
    }

    function generarPDF(cliente, factura) {
        //Calcular posición x, y para centrar la ventana.
        let ancho = 1000,
            alto = 800,
            x = parseInt(window.screen.width / 2 - ancho / 2),
            y = (x = parseInt(window.screen.height / 2 - alto / 2));
        $url = "factura/generaFactura.php?cl=" + cliente + "&f=" + factura;
        console.log($url);
        window.open(
            $url,
            "Factura",
            "left=" +
            x +
            ",top=" +
            y +
            ",height=" +
            alto +
            ",width=" +
            ancho +
            ",scrollbar=si,location=no,resizabe=si,menubar=no"
        );
    }

    //FUnciones modal agregar productos
    const txtCantidad = $("#txtCantidad"),
        alertAddProd = $("#alertAddProduct");

    function limpiar() {
        txtCantidad.val("");
        alertAddProd.html("");
        alertAddProd.removeClass("alert-success");
        location.reload();
    }

    function sendDataProduct() {
        const cantidad_agregar = $("#txtCantidad").val(),
            action = "addProduct",
            producto_id = $("#producto_id").val();
        $.ajax({
            url: "ajax_agregar.php",
            type: "POST",
            async: true,
            data: {
                //$('#form_add_product').serialize(),
                action: action,
                cantidad_agregar: cantidad_agregar,
                producto_id: producto_id,
            },
            success: function(response) {
                if (response == "error") {
                    alertAddProd.addClass("alert-danger");
                    alertAddProd.html("<p>Error al agregar el producto</p>");
                } else {
                    const info = JSON.parse(response);
                    $(".row" + info.producto_id + " .celExistencia").html(
                        "<p>" + info.nueva_existencia + "</p>"
                    );
                    alertAddProd.html("");
                    txtCantidad.val("");
                    alertAddProd.addClass("alert-success");
                    alertAddProd.html("<p>Producto agregado correctamente</p>");
                }
            },
            error: function(error) {
                console.log(error);
            },
        });
    }

    function anularFactura() {
        let noFactura = $("#no_factura").val(),
            action = "anularFactura";
        $.ajax({
            type: "POST",
            url: "ajax_anular_fac_procedimiento.php",
            data: {
                action: action,
                noFactura: noFactura,
            },
            async: true,
            success: function(response) {
                if (response == "error") {
                    $("#alertAnularFactura").addClass("alert-danger");
                    $("#alertAnularFactura").html("Error al anular la factura.");
                } else {
                    $("#alertAnularFactura").addClass("alert-success");
                    $("#alertAnularFactura").html("Factura anulada correctamente.");
                    $("#form_anular_factura .btn_ok").remove();
                }
            },
        });
    }
</script>