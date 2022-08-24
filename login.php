<?php

include("database/database.php");
function cerrar()
{
    session_destroy();
}
//session_start();

if (isset($_GET¨['cerrar_sesion'])) {
    session_unset();
    session_destroy();
}

if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 1:
            header('Location: index.php');
            break;
        case 2:
            header('Location: index.php');
            break;
        case 3:
            header('Location: index.php');
            break;
        default:
    }
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $id = 0;
    $db = new Database();
    $query = $db->connect()->prepare("SELECT * FROM usuarios WHERE email = :email and password = :password");
    $query->execute(['email' => $email, 'password' => $password]);
    $row = $query->fetch(PDO::FETCH_NUM);
    if ($row == true) {
        $rol = $row[3];
        $_SESSION['rol'] = $rol;
        $_SESSION['email'] = $email;
        $_SESSION['id'] = $id;
        switch ($_SESSION['rol']) {
            case 1:
                header('Location: index.php');
                break;
            case 2:
                header('Location: index.php');
                break;
            case 3:
                header('Location: index.php');
                break;
            default:
        }
    } else {
        $message = 'El usuario o contraseña son incorrectos';
    }
}


?>

<?php
include("includes/header.php");
?>

<div class="container p-4 login-cont">
    <?php if (!empty($message)) { ?>
        <script>
            alert('<?= $message ?>');
        </script>
    <?php
        $message = '';
    }
    ?>
    <div class="row justify-content-md-center">
        <h1 class="col-md-4 mx-auto text-center">Login</h1>
    </div>
    <div class="row">
        <div class="col-md-5 p-4">
            <div class="col-md-12 card card-body mx-auto">
                <form action="login.php" method="POST">
                    <div class="p-2">
                        <input type="text" name="email" placeholder="Ingrese su email" class="form-control" autofocus>
                    </div>
                    <div class="p-2">
                        <input type="password" name="password" placeholder="Ingrese su contraseña" class="form-control">
                    </div>
                    <div class="p-2 d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-block"><i class="fa-solid fa-door-open"></i> Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-7">
            <div class="mx-auto text-center">
                <p>
                    <img src="img/icon/abaBarLogin.png" style="width: 90%;">
                </p>
            </div>
        </div>

    </div>
</div>

<?php
include("includes/footer.php");
?>