<?php
include("database/db.php");

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

if (!empty($_POST)) {
    $message = '';
    if (empty($_POST['cat_name'])) {
        $_SESSION['message'] = 'Todos los campos son obligatorios';
    } else {
        $cat = $_POST['cat_name'];
        $sql = mysqli_query($conn, "SELECT * FROM categorias WHERE categoria = '$cat'");
        $result = mysqli_fetch_array($sql);
        if ($result > 0) {
            $_SESSION['message'] = 'La categoria ya existe';
        } else {
            $sql_insert = mysqli_query($conn, "INSERT INTO categorias (categoria) VALUES ('$cat')");
            if ($sql_insert) {
                $_SESSION['message'] = 'Categoria creada correctamente';
            } else {
                $_SESSION['message'] = 'Error al crear la categoria';
            }
        }
    }
}
?>

<?php
include("includes/header.php");
?>

<script>
    alert('<?php echo $_SESSION['message'] ?>')
    <?php unset($_SESSION['message']) ?>
</script>


<div class="container p-4">
    <div class="row d-flex justify-content-between mb-3">
        <a style="margin-left: 17px;
        width: 150px;" href="index.php" class="btn btn-warning btn-block">
            <i class="fa-solid fa-home"></i>
            Menú principal
        </a>
    </div>
    <div class="row mb-2">
        <h1>Registro de categorías <i class="fa-solid fa-pen"></i></h1>
    </div>
    <div class="row">

        <div class="col-md-4">
            <form action="categorias.php" method="POST">
                <div class="p-2">
                    <input type="text" name="cat_name" placeholder="Ingrese la categoria" class="form-control" required>
                </div>
                <div class="p-2 d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa-solid fa-floppy-disk"></i> Registrar</button>
                </div>
            </form>
        </div>

        <div class="col-md-8 mt-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoria</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!--Listado de usuarios-->
                    <?php
                    $query_select = mysqli_query($conn, "SELECT * FROM categorias;");
                    $result_select = mysqli_num_rows($query_select);
                    if ($result_select > 0) {
                        while ($data = mysqli_fetch_array($query_select)) {
                    ?>
                            <tr>
                                <td style="display: none;"><?php echo $data['id'] ?></td>
                                <td><?php echo $data['id'] ?></td>
                                <td><?php echo $data['categoria'] ?></td>
                                <td>
                                    <a href="editar_categoria.php?id=<?php echo $data['id'] ?>" class="w-100 btn btn-secondary">
                                        <i class="fas fa-marker"></i>
                                        Actualizar
                                    </a>
                                    <!--<a href="eliminar_categoria.php?id=<?php echo $data['id'] ?>" class="mt-1 w-100 btn btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                        Eliminar
                                    </a>-->
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

        </div>

    </div>
</div>



<?php
include("includes/footer.php")
?>