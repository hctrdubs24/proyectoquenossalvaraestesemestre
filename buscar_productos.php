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


$message = '';
$categoria_search = '';
if (empty($_REQUEST['categoria'])) {
    header('location: inventario.php');
}
if (!empty($_REQUEST['categoria'])) {
    $categoria_search = $_REQUEST['categoria'];
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

    <div class="col-md-12 mt-2">
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
                                                                            WHERE c.id = $categoria_search
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