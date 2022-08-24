<?php
include("database/db.php");

if (!empty($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $query = "SELECT * FROM categorias WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $name = $row['categoria'];
    }
}

if (isset($_POST['update_task'])) {
    $id = $_REQUEST['id'];
    $cat = $_POST['categoria'];
    $query = "UPDATE categorias set categoria = '$cat' WHERE id = $id";
    mysqli_query($conn, $query);
    #$_SESSION['message'] = 'Task Updated Succesfully';
    header('Location: categorias.php');
}

include("includes/header.php");
?>



<div class="container p-4">
    <div class="row">
        <h1>Actualizar categoria</h1>
        <div class="col-md-4 mx-auto">
            <div class="card card-body">
                <form action="editar_categoria.php?id=<?php echo $_REQUEST['id']; ?>" method="POST">
                    <div class="p-2">
                        <input type="number" name="id" id="" class="form-control" value="<?php echo $_REQUEST['id'] ?>" autofocus placeholder="Update Title" disabled>
                    </div>
                    <div class="p-2">
                        <input type="text" name="categoria" value="<?php echo $name ?>" class="form-control">
                    </div>
                    <div class="d-grid gap-2 p-2">
                        <input type="submit" class="btn btn-success btn-block" name="update_task" value="Actualizar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
include("includes/footer.php")
?>