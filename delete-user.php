<?php
    include("database/db.php");

    #Eliminar usuario
    if (!empty($_POST)){
        $idusuario_delete = $_POST['id_usuario'];
        $query_delete = mysqli_query($conn, "DELETE FROM usuarios WHERE id = $idusuario_delete");
        if ($query_delete) {
            $_SESSION['message'] = 'Usuario eliminado correctamente';
            header("Location: signup.php");
        }else{
            $_SESSION['message'] = 'Error al eliminar';
        }
    }


    #Roles.
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


    #Confirmación del usuario
    if (empty($_REQUEST['id'])) {
        header("Location: signup.php");
    }else {
        $id_usuario = $_REQUEST['id'];
        $sql = mysqli_query($conn, "SELECT u.email, r.descripcion 
                                        FROM usuarios u 
                                            INNER JOIN roles r  
                                                ON u.rol_id = r.id 
                                                    WHERE u.id = $id_usuario");
        $result = mysqli_num_rows($sql);
        if($result > 0){
            while ($data = mysqli_fetch_array($sql)) {
                $nombre = $data['email'];
                $rol = $data['descripcion'];                
            }
        }else{
            header("Location: signup.php");
        }
    }

?>


<?php 
    include('includes/header.php');
?>

<div class="container p-4">
    <div class="row">
        <h1 class="col-md-4 mx-auto text-center">Eliminar usuario</h1>
    </div>
    <div class="row">
        <h3 class="col-md-4 mx-auto text-center mb-4">¿Está seguro de eliminar el siguiente usuario?</h3>
    </div>
    <div class="row col-md-12">
        <div class="col-md-4  mx-auto">
            <div class="card card-body ">
                <p class="mx-auto text-center fs-3">Usuario: <span><?php echo $nombre ?></span></p>
                <p class="mx-auto text-center fs-3">Cargo: <span><?php echo $rol ?></span></p>
                <form action="" class="mx-auto" method="POST">
                    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                    <a class="btn btn-info btn-block" href="signup.php">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn btn-danger btn-block">
                </form>

            </div>
            
        </div>
    </div>
</div>




<?php 
    include('includes/footer.php');
?>