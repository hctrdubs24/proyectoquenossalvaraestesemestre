<?php
include('./database/db.php');

#Roles
if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 2:
            header('Location: index.php');
            break;
        default:
    }
}



if (!empty($_POST)) {
    if (
        empty($_POST['name_producto']) || empty($_POST['precio']) || empty($_POST['categoria_select']) || $_POST['precio'] <= 0
        || empty($_POST['id_producto']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove'])
    ) {
        $_SESSION['message'] = 'Existen campos obligatorios';
    } else {
        $id_producto_post = $_POST['id_producto'];
        $name_product = $_POST['name_producto'];
        $precio = $_POST['precio'];
        $caducidad = $_POST['caducidad'];
        $categoria = $_POST['categoria_select'];
        $imgProducto = $_POST['foto_actual'];
        $imgRemove = $_POST['foto_remove'];

        $foto = $_FILES['file'];
        $nombre_foto = $foto['name'];
        $path_foto = $foto['full_path'];
        $tipo_foto = $foto['type'];
        $url_temp = $foto['tmp_name'];
        $tamaño_foto = $foto['size'];

        $upd = '';
        if ($nombre_foto != '') {
            $destino = 'img/uploads/';
            $img_nombre = 'img_' . md5(date('d-m-y H:m:s'));
            $imgProducto = $img_nombre . '.jpg';
            $src = $destino . $imgProducto;
        } else {
            if ($_POST['foto_actual'] != $_POST['foto_remove']) {
                $imgProducto = 'img_producto.png';
            }
        }

        if (empty($caducidad)) {
            $caducidad = 'N/A';
        }

        $sql_update = mysqli_query($conn, "UPDATE productos SET nombre_producto = '$name_product', precio = $precio, 
                                            fecha_caducidad = '$caducidad', foto = '$imgProducto', id_cat = '$categoria' 
                                                WHERE id = $id_producto_post");
        if ($sql_update) {
            /*if (($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove'])) {
                unlink('img/uploads/' . $_POST['foto_actual']);
            }

            if ($nombre_foto != '') {
                move_uploaded_file($url_temp, $src);
            }*/
            $_SESSION['message'] = 'Producto actualizado correctamente';
        } else {
            $_SESSION['message'] = 'Error al actualizar el producto';
        }
    }
}

#Validar producto
if (empty($_REQUEST['id'])) {
    header('Location: inventario.php');
} else {
    $id_producto = $_REQUEST['id'];
    if (!is_numeric($id_producto)) {
        header('Location: inventario.php');
    }

    $query_producto = mysqli_query($conn, "SELECT p.id, p.nombre_producto, p.fecha_caducidad, p.cantidad,  p.precio, p.foto, p.id_cat, c.categoria
                                                FROM productos p 
                                                    INNER JOIN categorias c 
                                                        ON p.id_cat = c.id 
                                                            WHERE p.id = $id_producto 
                                                                AND p.estado_producto = 1");
    $result_producto = mysqli_num_rows($query_producto);

    $foto = '';
    $classRemove = 'notBlock';
    if ($result_producto > 0) {
        $data_producto = mysqli_fetch_assoc($query_producto);
        if ($data_producto['foto'] != 'img_producto.png') {
            $classRemove = '';
            $foto = '<img id="img" src="img/uploads/' . $data_producto['foto'] . '" alt="Producto" style="width: 150px;">';
        }
    } else {
        header('Location: inventario.php');
    }
}
?>


<?php
include('./includes/header.php');
?>

<div class="container p-4">
    <div class="row justify-content-md-center">
        <h1 class="col-md-4 mx-auto">Actualizar Producto <i class="fa-solid fa-wrench"></i></h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-5 card card-body mx-auto">
                <!--Aqui-->
                <form action="update-producto.php" method="POST">
                    <div class="">
                        <input type="hidden" name="id_producto" value="<?php echo $data_producto['id'] ?>">
                    </div>
                    <div class="">
                        <input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $data_producto['foto'] ?>">
                    </div>
                    <div class="">
                        <input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $data_producto['foto'] ?>">
                    </div>
                    <div class="p-2">
                        <input type="text" name="name_producto" value="<?php echo $data_producto['nombre_producto'] ?>" class="form-control">
                    </div>
                    <div class="p-2">
                        <label class="form-control">Cantidad disponible: <?php echo $data_producto['cantidad'] ?></label>
                    </div>
                    <div class="p-2">
                        <input type="number" name="precio" value="<?php echo $data_producto['precio'] ?>" class="form-control">
                    </div>
                    <div class="p-2 d-grid gap-2">
                        <div class="input-group date p-2" id="datepicker">
                            <input type="text" class="form-control" name="caducidad" value="<?php echo $data_producto['fecha_caducidad'] ?>">
                            <span class="input-group-append">
                                <span style="height: 100%;" class="input-group-text bg-white">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                        </div>

                        <style>
                            .noPrimerHijo option:first-child {
                                display: none;
                            }

                            .notBlock {
                                display: none !important;
                            }
                        </style>

                        <select name="categoria_select" class="form-select noPrimerHijo">
                            <option selected value="<?php echo $data_producto['id_cat'] ?>"><?php echo $data_producto['categoria'] ?></option>
                            <?php
                            $query_cat = mysqli_query($conn, "SELECT * FROM categorias");
                            $result_cat = mysqli_num_rows($query_cat);
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
                        <label for="file" class="form-label">Imagen del producto</label>
                        <p style="text-align: center;">
                            <?php echo $foto ?>
                        </p>
                    </div>

                    <!--
                    <div class="p-2">
                        <label for="file" class="form-label">Ingrese la imagen del producto</label>
                        <div class="photo">
                            <label for="foto">Foto</label>
                            <div class="prevPhoto">
                                <span class="delPhoto <?php echo $classRemove ?>">X</span>
                                <label for="foto"></label>
                                <?php echo $foto ?>
                            </div>
                            <div class="upimg">
                                <input type="file" name="file" id="foto">
                            </div>
                            <div id="form_alert"></div>
                        </div>
                    </div>


                        -->

                    <div class="p-2 d-grid gap-2">
                        <input type="submit" value="Actualizar" class="btn btn-success btn-block">
                    </div>
                </form>
            </div>
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



<?php
include('./includes/footer.php');
?>