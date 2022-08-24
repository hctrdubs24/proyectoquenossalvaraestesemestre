<?php
include('database/db.php');
include('includes/header.php');

if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 2:
            header('Location: index.php');
            break;
        default:
    }
}

if (!empty($_POST)) {
    $message = '';
    if (empty($_POST['name_product']) || empty($_POST['precio']) || empty($_POST['cantidad']) || empty($_POST['categoria_select']) || $_POST['precio'] <= 0 || $_POST['cantidad'] <= 0) {
        $message = 'Existen campos obligatorios';
    } else {
        $name_product = $_POST['name_product'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $caducidad = $_POST['caducidad'];
        $categoria = $_POST['categoria_select'];

        $foto = $_FILES['file'];
        $nombre_foto = $foto['name'];
        $path_foto = $foto['full_path'];
        $tipo_foto = $foto['type'];
        $url_temp = $foto['tmp_name'];
        $tamaño_foto = $foto['size'];

        $imgProducto = 'img_producto.png'; /*Nombre por si no se carga una imagen*/
        if ($nombre_foto != '') {
            $destino = 'img/uploads/';
            $img_nombre = 'img_' . md5(date('d-m-y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src = $destino . $imgProducto;
        }

        if (empty($caducidad)) {
            $caducidad = 'N/A';
        }


        $sql = mysqli_query($conn, "SELECT * FROM productos WHERE nombre_producto = '$name_product'");
        $result = mysqli_fetch_array($sql);
        if ($result > 0) {
            $message = 'El producto ya existe';
        } else {
            $sql_insert = mysqli_query($conn, "INSERT INTO productos (nombre_producto, precio, cantidad, fecha_caducidad, id_cat, foto) VALUES 
                    ('$name_product', '$precio', '$cantidad', '$caducidad', '$categoria', '$imgProducto')");
            if ($sql_insert) {
                if ($nombre_foto != '') {
                    move_uploaded_file($url_temp, $src);
                }
                $_SESSION['message'] = 'Producto registrado correctamente';
            } else {
                $_SESSION['message'] = 'Error al registrar el producto';
            }
        }
    }
}
?>

<script>
    alert('<?php echo $_SESSION['message'] ?>')
    <?php unset($_SESSION['message']) ?>
</script>


<div class="container p-1">
    <div class="row d-flex justify-content-between mb-3">
        <a style="margin-left: 17px;
        width: 150px;" href="index.php" class="btn btn-warning btn-block">
            <i class="fa-solid fa-home"></i>
            Menú principal
        </a>
    </div>
    <div class="row mb-2">
        <h1>Registro de productos <i class="fa-solid fa-boxes-stacked"></i></h1>
    </div>
    <div class="row">

        <div class="col-md-3">

            <form action="inventario.php" method="POST" enctype="multipart/form-data">
                <div class="p-2">
                    <input type="text" name="name_product" placeholder="Ingrese nombre del producto" class="form-control" required>
                </div>
                <div class="p-2 input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" name="precio" class="form-control" placeholder="Ingrese el precio del producto" required>
                    <span class="input-group-text">.00</span>
                </div>
                <div class="p-2">
                    <input type="number" name="cantidad" placeholder="Ingrese la cantidad" class="form-control" required>
                </div>
                <div class="input-group date p-2" id="datepicker">
                    <input type="text" class="form-control" name="caducidad" placeholder="Ingrese la fecha de caducidad">
                    <span class="input-group-append">
                        <span style="height: 100%;" class="input-group-text bg-white">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </span>
                </div>
                <div class="p-2 d-grid gap-2">
                    <?php
                    $query_cat = mysqli_query($conn, "SELECT * FROM categorias");
                    $result_cat = mysqli_num_rows($query_cat);
                    ?>
                    <select name="categoria_select" class="form-select" required>
                        <option selected value="">Seleccione una categoría</option>
                        <?php
                        if ($result_cat > 0) {
                            while ($cat_sql = mysqli_fetch_array($query_cat)) {
                        ?>
                                <option value="<?php echo $cat_sql['id'] ?>"><?php echo $cat_sql['categoria'] ?></option>
                        <?php
                            }
                        }
                        ?>

                    </select>
                </div>
                <div class="p-2">
                    <label for="file" class="form-label">Ingrese la imagen del producto</label>
                    <div class="photo">
                        <label for="foto">Foto</label>
                        <div class="prevPhoto">
                            <span class="delPhoto notBlock">X</span>
                            <label for="foto"></label>
                        </div>
                        <div class="upimg">
                            <input type="file" name="file" id="foto">
                        </div>
                        <div id="form_alert"></div>
                    </div>
                </div>
                <div class="p-2 d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa-solid fa-floppy-disk"></i> Registrar</button>
                </div>
            </form>
        </div>


        <div class="col-md-9 mt-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre del producto</th>
                        <th>Precio</th>
                        <th>Cantidad disponible</th>
                        <th>Fecha de caducidad</th>
                        <th style="width: 150px;">
                            <?php
                            $query_cat = mysqli_query($conn, "SELECT * FROM categorias");
                            $result_cat = mysqli_num_rows($query_cat);
                            ?>
                            <select name="search_categoria" id="search_categoria" class="form-select">
                                <option selected value="">Categoría</option>
                                <?php
                                if ($result_cat > 0) {
                                    while ($cat_sql = mysqli_fetch_array($query_cat)) {
                                ?>
                                        <option value="<?php echo $cat_sql['id'] ?>"><?php echo $cat_sql['categoria'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Listado de productos -->
                    <?php

                    /* Paginador */
                    $sql_registros = mysqli_query($conn, "SELECT COUNT(*) AS total_registros FROM productos WHERE estado_producto = 1;");
                    $result_registros = mysqli_fetch_array($sql_registros);
                    $total_registros = $result_registros['total_registros'];

                    $por_pagina = 5;
                    if (empty($_GET['pagina'])) {
                        $pagina = 1;
                    } else {
                        $pagina = $_GET['pagina'];
                    }

                    $desde = ($pagina - 1) * $por_pagina;
                    $total_paginas = ceil($total_registros / $por_pagina);

                    $query_select = mysqli_query($conn, "SELECT p.id, p.nombre_producto, p.precio, p.cantidad, p.estado_producto, p.fecha_caducidad, p.foto, c.categoria 
                                                                FROM productos p 
                                                                    INNER JOIN categorias c 
                                                                        ON p.id_cat = c.id 
                                                                            LIMIT $desde, $por_pagina;");
                    $result_select = mysqli_num_rows($query_select);
                    if ($result_select > 0) {
                        while ($data = mysqli_fetch_array($query_select)) {

                            if ($data['estado_producto'] == 1) {
                    ?>
                                <tr class="row<?php echo $data['id'] ?>">
                                    <td><?php echo $data['id'] ?></td>
                                    <td><?php echo $data['nombre_producto'] ?></td>
                                    <td class="celPrecio"><?php echo $data['precio'] ?></td>
                                    <td class="celExistencia"><?php echo $data['cantidad'] ?></td>
                                    <td><?php echo $data['fecha_caducidad'] ?></td>
                                    <td><?php echo $data['categoria'] ?></td>
                                    <td><img src="img/uploads/<?php echo $data['foto'] ?>" style="width: 110px;"></td>
                                    <td>
                                        <a href="#" product="<?php echo $data['id']; ?>" class="cod_producto_enviar link_add add_product btn btn-success mt-1 w-100" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="fas fa-plus"></i>
                                            Agregar
                                        </a>

                                        <a href="update-producto.php?id=<?php echo $data['id'] ?>" class="w-100 mt-1 btn btn-secondary">
                                            <i class="fas fa-marker"></i>
                                            Actualizar

                                        </a>
                                        <a href="delete-producto.php?id=<?php echo $data['id'] ?>" class="w-100 mt-1 btn btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                            Eliminar

                                        </a>
                                    </td>
                                </tr>
                    <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="paginador">
                <ul>
                    <?php
                    if ($pagina != 1) {
                    ?>
                        <li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i> </a></li>
                        <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fas fa-fa-step-forward"></i> </a></li>
                    <?php
                    }

                    for ($i = 1; $i <= $total_paginas; $i++) {
                        if ($i == $pagina) {
                            echo '<li class="pageSelected">' . $i . '</li>';
                        } else {
                            echo '<li><a href="?pagina=' . $i . '"> ' . $i . '</a></li>';
                        }
                    }

                    if ($pagina != $total_paginas) {
                    ?>
                        <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fas fa-step-backward"></i> </a></li>
                        <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-step-forward"></i> </a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <style>
        .prevPhoto {
            display: flex;
            justify-content: space-between;
            width: 160px;
            height: 150px;
            border: 1px solid #CCC;
            position: relative;
            cursor: pointer;
            background: url(../images/uploads/user.png);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            margin: auto;
        }

        .prevPhoto label {
            cursor: pointer;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
        }

        .prevPhoto img {
            width: 100%;
            height: 100%;
        }

        .upimg,
        .notBlock {
            display: none !important;
        }

        .errorArchivo {
            font-size: 16px;
            font-family: arial;
            color: #cc0000;
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }

        .delPhoto {
            color: #FFF;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flex;
            display: -o-flex;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            background: red;
            position: absolute;
            right: -10px;
            top: -10px;
            z-index: 10;
        }

        #tbl_list_productos img {
            width: 50px;
        }

        .imgProductoDelete {
            width: 175px;
        }
    </style>

    <div style="text-align: center;" class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-box-open"></i> Agregar productos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="limpiar();"></button>
                </div>
                <form action="" name="form_add_product" method="POST" onsubmit="event.preventDefault(); sendDataProduct();" id="form_add_product">
                    <div class="modal-body">
                        <h6 class="nameProducto">Producto: </h6>
                        <div class="mb-3">
                            <label for="txtCantidad" class="col-form-label">Cantidad:</label>
                            <input type="number" class="form-control" name="cantidad_agregar" id="txtCantidad" placeholder="Cantidad por agregar" required>
                            <input type="hidden" name="producto_id" id="producto_id" value="" required>
                            <input type="hidden" name="action" value="addProduct" required>
                        </div>
                        <div class="alert " id="alertAddProduct">

                        </div>

                        <button type="button" onclick="limpiar();" class="btn btn-danger" data-bs-dismiss="modal"> <i class="fas fa-ban"></i> Cerrar</button>
                        <button type="submit" class="btn btn-success addProduct"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include('includes/footer.php')
?>