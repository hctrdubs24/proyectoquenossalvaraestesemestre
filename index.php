<?php
include('includes/header.php');
include('database/db.php');
session_start();
$email = $_SESSION['email'];
//Buscar id
$sql = mysqli_query($conn, "SELECT * FROM usuarios WHERE email = '$email'");
        $result = mysqli_fetch_array($sql);
        //print_r($result);
$_SESSION['id'] = $result['id'];

if(!isset($_SESSION['rol'])){
    header('Location: login.php');
}else{
    if($_SESSION['rol'] == 1){
        ?>
        <div class="conainer p-4">
    <div class="row d-flex justify-content-between mb-3">
        <a style="margin-left: 60px;
        width: 150px;" href="logout.php" class="btn btn-danger btn-block">
            <i class="fa-solid fa-arrow-left-long"></i>
            Cerrar Sesión
        </a>
    </div>
    <div class="row">
        <div class="container overflow-hidden ">
        <div class="row gy-5 ps-5 pe-5">
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="signup.php" class="btn btn-light btn-block">
                        <h1>Usuarios <i class="fas fa-address-card"></i></h1>
                    </a>
                </div> 
            </div>
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="inventario.php" class="btn btn-light btn-block">
                        <h1>Listado de productos <i class="fa-solid fa-cart-flatbed"></i></h1>
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="ventas.php" class="btn btn-light btn-block">
                        <h1>Ventas <i class="fa-solid fa-cart-shopping"></i></h1>
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="corte_caja.php" class="btn btn-light btn-block">
                        <h1>Corte de caja <i class="fa-solid fa-clipboard"></i></i></h1>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    </div>
</div>

        <?php
    }
    if($_SESSION['rol'] == 2){
        ?>
<div class="conainer p-4">
    <div class="row d-flex justify-content-between mb-3">
        <a style="margin-left: 60px;
        width: 150px;" href="logout.php" class="btn btn-danger btn-block">
            <i class="fa-solid fa-arrow-left-long"></i>
            Cerrar Sesión
        </a>
    </div>
    <div class="row">
        <div class="container overflow-hidden ">
        <div class="row gy-5 ps-5 pe-5">
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="ventas.php" class="btn btn-light btn-block">
                        <h1>Ventas <i class="fa-solid fa-cart-shopping"></i></h1>
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="signup.php" class="btn btn-light btn-block">
                        <h1>Corte de caja <i class="fa-solid fa-clipboard"></i></i></h1>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    </div>
</div>

<?php
    }
    if($_SESSION['rol'] == 3){
        ?>
<div class="conainer p-4">
    <div class="row d-flex justify-content-between mb-3">
        <a style="margin-left: 60px;
        width: 150px;" href="logout.php" class="btn btn-danger btn-block">
            <i class="fa-solid fa-arrow-left-long"></i>
            Cerrar Sesión
        </a>
    </div>
    <div class="row">
        <div class="container overflow-hidden ">
        <div class="row gy-5 ps-5 pe-5">
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="inventario.php" class="btn btn-light btn-block">
                        <h1>Listado de productos <i class="fa-solid fa-cart-flatbed"></i></h1>
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="signup.php" class="btn btn-light btn-block">
                        <h1>Ventas <i class="fa-solid fa-cart-shopping"></i></h1>
                    </a>
                </div>
            </div>
            
            
        </div>
    </div>
    </div>
</div>
        <?php
    }
}
?>


<!--<div class="conainer p-4">
    <div class="row d-flex justify-content-between mb-3">
        <a style="margin-left: 60px;
        width: 150px;" href="logout.php" class="btn btn-danger btn-block">
            <i class="fa-solid fa-arrow-left-long"></i>
            Cerrar Sesión
        </a>
    </div>
    <div class="row">
        <div class="container overflow-hidden ">
        <div class="row gy-5 ps-5 pe-5">
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="signup.php" class="btn btn-light btn-block">
                        <h1>Usuarios <i class="fas fa-address-card"></i></h1>
                    </a>
                </div> 
            </div>
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="inventario.php" class="btn btn-light btn-block">
                        <h1>Listado de productos <i class="fa-solid fa-cart-flatbed"></i></h1>
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="signup.php" class="btn btn-light btn-block">
                        <h1>Ventas <i class="fa-solid fa-cart-shopping"></i></h1>
                    </a>
                </div>
            </div>
            <div class="col-6">
                <div style="height: 225px;" class="card border bg-light">
                    <a style="height: 100%;
                                width: 100%;
                                padding-top: 85px ;" 
                                href="signup.php" class="btn btn-light btn-block">
                        <h1>Corte de caja <i class="fa-solid fa-clipboard"></i></i></h1>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    </div>
</div>-->
<?php
include('includes/footer.php');
?>