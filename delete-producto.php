<?php
include("database/db.php");

#Eliminar producto
if (!empty($_POST)) {
    $idproducto_delete = $_POST['id_producto'];
    $query_delete = mysqli_query($conn, "UPDATE productos SET  estado_producto = 2 WHERE id = $idproducto_delete");
    if (!$query_delete) {
        $_SESSION['message'] = 'Error al eliminar el producto'; 
    }
    $_SESSION['message'] = 'Producto eliminado correctamente';
    header("Location: inventario.php");
}


#Roles
if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 2:
            header('Location: index.php');
            break;
        case 3:
            header('Location: index.php');
            break;
        default:
    }
}


#Confirmación del producto
if (empty($_REQUEST['id'])) {
    header("Location: inventario.php");
} else {
    $id_producto = $_REQUEST['id'];
    $sql = mysqli_query($conn, "SELECT p.nombre_producto, p.cantidad, c.categoria, p.foto 
                                    FROM productos p 
                                        INNER JOIN categorias c 
                                            ON p.id_cat = c.id 
                                                WHERE p.id = '$id_producto'");
    $result = mysqli_num_rows($sql);
    if ($result > 0) {
        while ($data = mysqli_fetch_array($sql)) {
            $nombre_productos = $data['nombre_producto'];
            $cantidad_producto = $data['cantidad'];
            $categoria_producto = $data['categoria'];
            $foto_producto = $data['foto'];
        }
    } else {
        header("Location: inventario.php");
    }
}

?>


<?php
include('includes/header.php');
?>


<div class="container p-4">
    <div class="row">
        <h1 class="col-md-4 mx-auto text-center">Eliminar producto</h1>
    </div>
    <div class="row">
        <h3 class="col-md-4 mx-auto text-center mb-4">¿Está seguro de eliminar el siguiente producto?</h3>
    </div>
    <div class="row col-md-12">
        <div class="col-md-4  mx-auto">
            <div class="card card-body ">
                <p class="mx-auto text-center fs-3">Producto: <span><?php echo $nombre_productos ?></span></p>
                <p class="mx-auto text-center fs-3">Cantidad disponible: <span><?php echo $cantidad_producto ?></span></p>
                <p class="mx-auto text-center fs-3">Categoría: <span><?php echo $categoria_producto ?></span></p>
                <p class="mx-auto text-center fs-3"> <span> <img src="img/uploads/<?php echo $foto_producto ?>" style="width: 130px;"> </span></p>
                <form action="" class="mx-auto" method="POST">
                    <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">
                    <a class="btn btn-info btn-block" href="inventario.php">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn btn-danger btn-block">
                </form>

            </div>

        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>