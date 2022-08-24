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
    if(empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password']) || empty($_POST['rol_select'])){
        $_SESSION['message'] = 'Todos los campos son obligatorios';
    }else{
        $id_usuario = $_POST['id_usuario'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $rol = $_POST['rol_select'];
        $confirm_password = $_POST['confirm_password'];

        if ($password == $confirm_password) {
            $sql = mysqli_query($conn, "SELECT * FROM usuarios 
                                                    WHERE email = '$email' 
                                                        AND id != '$id_usuario'");
            $result = mysqli_fetch_array($sql);
            if($result > 0){
                $_SESSION['message'] = 'El usuario ya existe';
            }else{
                $sql_update = mysqli_query($conn, "UPDATE usuarios SET email = '$email', password = '$password', rol_id = '$rol' WHERE id = '$id_usuario'");
                if($sql_update){
                    $_SESSION['message'] = 'Usuario actualizado correctamente';
                }else{
                    $_SESSION['message'] = 'Error al crear el usuario';
                }
            }
        }
    }   
}

/*
    Mostrar datos 
*/
if(empty($_GET['id'])){
    header("Location: signup.php");
}
$id_user = $_GET['id'];
$sql = mysqli_query($conn, "SELECT u.id, u.email, u.password, (u.rol_id) as rol_id, (r.descripcion) as descrip_rol 
                                    FROM usuarios u 
                                        INNER JOIN roles r 
                                            ON u.rol_id = r.id 
                                                WHERE u.id=$id_user");
$result = mysqli_num_rows($sql);
if ($result == 0) {
    header("Location: signup.php");
}else{
    $option  = '';
    while ($data = mysqli_fetch_array($sql)) {
        $id_user = $data['id'];
        $email = $data['email'];
        $password = $data['password'];
        $rol_id = $data['rol_id'];
        $rol_descrip = $data['descrip_rol'];

        if ($rol_id == 1) {
            $option = '<option value="'.$rol_id.'" select>'.$rol_descrip.'</option>';
        }else if ($rol_id == 2) {
            $option = '<option value="'.$rol_id.'" select>'.$rol_descrip.'</option>';
        }else if ($rol_id == 3) {
            $option = '<option value="'.$rol_id.'" select>'.$rol_descrip.'</option>';
        }

        
    }
}



?>

<?php 
    include('includes/header.php');
?>


<div class="container p-4">
    <div class="row justify-content-md-center">
        <h1 class="col-md-4 mx-auto">Actualizar usuario <i class="fa-solid fa-wrench"></i></h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-5 card card-body mx-auto">
                <form action="update-user.php" method="POST" >
                    <div class="">
                        <input type="hidden" name="id_usuario" value="<?php echo $id_user ?>">
                    </div>
                    <div class="p-2">
                        <input type="text" name="email" value="<?php echo $email ?>" class="form-control" >
                    </div>
                    <div class="p-2">
                        <input type="password" name="password" value="<?php echo $password ?>" class="form-control">
                    </div>
                    <div class="p-2">
                        <input type="password" name="confirm_password" value="<?php echo $password ?>" class="form-control">
                    </div>
                    <div class="p-2 d-grid gap-2">
                        <?php
                            $query_rol = mysqli_query($conn, "SELECT * FROM roles");
                            $result_rol = mysqli_num_rows($query_rol);
                        ?>
                        <style>
                            .noPrimerHijo option:first-child{
                                display: none;
                            }
                        </style>
                        <select name="rol_select" class="form-select noPrimerHijo">
                            <?php
                                echo $option;
                                if ($result_rol > 0) {
                                    while ($rol_sql = mysqli_fetch_array($query_rol)) {
                            ?>
                                <option value="<?php echo $rol_sql['id'] ?>"><?php echo $rol_sql['descripcion'] ?></option> 
                            <?php
                                    }
                                }
                            ?>
                            
                        </select>
                    </div>
                    <div class="p-2 d-grid gap-2">
                        <input type="submit" value="Actualizar" class="btn btn-success btn-block" >
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
include('includes/footer.php');
?>

