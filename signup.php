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
    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password']) || empty($_POST['rol_select'])) {
        $_SESSION['message'] = 'Todos los campos son obligatorios';
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $rol = $_POST['rol_select'];
        $confirm_password = $_POST['confirm_password'];
        if ($password == $confirm_password) {
            $sql = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$email'");
            $result = mysqli_fetch_array($sql);
            if ($result > 0) {
                $_SESSION['message'] = 'El usuario ya existe';
            } else {
                $sql_insert = mysqli_query($conn, "INSERT INTO usuarios (email, password, rol_id) VALUES ('$email', '$password', '$rol')");
                if ($sql_insert) {
                    $_SESSION['message'] = 'Usuario creado correctamente';
                } else {
                    $_SESSION['message'] = 'Error al crear el usuario';
                }
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
        <h1>Registro de usuarios <i class="fa-solid fa-users"></i></h1>
    </div>
    <div class="row">

        <div class="col-md-4">
            <form action="signup.php" method="POST">
                <div class="p-2">
                    <input type="text" name="email" placeholder="Ingrese el usuario" class="form-control">
                </div>
                <div class="p-2">
                    <input type="password" name="password" placeholder="Ingrese la contraseña" class="form-control">
                </div>
                <div class="p-2">
                    <input type="password" name="confirm_password" placeholder="Confirme la contraseña" class="form-control">
                </div>
                <div class="p-2 d-grid gap-2">
                    <?php
                    $query_rol = mysqli_query($conn, "SELECT * FROM roles");
                    $result_rol = mysqli_num_rows($query_rol);
                    ?>
                    <select name="rol_select" class="form-select">
                        <option selected>Seleccione un cargo</option>
                        <?php
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
                    <button type="submit" class="btn btn-success btn-block"><i class="fa-solid fa-floppy-disk"></i> Registrar</button>
                </div>
            </form>
        </div>

        <div class="col-md-8 mt-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Contraseña</th>
                        <th>
                            <?php
                            $query_rol = mysqli_query($conn, "SELECT * FROM roles");
                            $result_rol = mysqli_num_rows($query_rol);
                            ?>
                            <select name="rol_search" id="rol_search" class="form-select">
                                <option selected>Rol del Usuario</option>
                                <?php
                                if ($result_rol > 0) {
                                    while ($rol_sql = mysqli_fetch_array($query_rol)) {
                                ?>
                                        <option value="<?php echo $rol_sql['id'] ?>"><?php echo $rol_sql['descripcion'] ?></option>
                                <?php
                                    }
                                }
                                ?>

                            </select>
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!--Listado de usuarios-->
                    <?php

                    /* Paginador */
                    $sql_registros = mysqli_query($conn, "SELECT COUNT(*) AS total_registros FROM usuarios;");
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

                    $query_select = mysqli_query($conn, "SELECT u.id, u.email, u.password, r.descripcion 
                                                                FROM usuarios u 
                                                                    INNER JOIN roles r 
                                                                        ON u.rol_id = r.id 
                                                                            LIMIT $desde, $por_pagina;");
                    $result_select = mysqli_num_rows($query_select);
                    if ($result_select > 0) {
                        while ($data = mysqli_fetch_array($query_select)) {
                    ?>
                            <tr>
                                <td style="display: none;"><?php echo $data['id'] ?></td>
                                <td><?php echo $data['email'] ?></td>
                                <td><?php echo $data['password'] ?></td>
                                <td><?php echo $data['descripcion'] ?></td>
                                <td>
                                    <a href="update-user.php?id=<?php echo $data['id'] ?>" class="w-100 btn btn-secondary">
                                        <i class="fas fa-marker"></i>
                                        Actualizar
                                    </a>
                                    <a href="delete-user.php?id=<?php echo $data['id'] ?>" class="mt-1 w-100 btn btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                    <?php
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
</div>



<?php
include("includes/footer.php")
?>