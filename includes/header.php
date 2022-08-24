<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/public/css/styles.css">
    <title>
        <?php
        $paginaTitle = $_SERVER['SCRIPT_NAME'];
        switch ($paginaTitle) {
            case '/xampp/htdocs/VSCodePHP/Login_php/inventario.php':
                echo 'Inventario - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/delete-producto.php':
                echo 'Eliminar producto - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/update-producto.php':
                echo 'Actualizar producto - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/login.php':
                echo 'Login - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/index.php':
                echo 'Inicio - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/signup.php';
                echo 'Usuarios - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/update-user.php';
                echo 'Actualizar usuario - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/delete-user.php';
                echo 'Eliminar usuario - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/ventas.php';
                echo 'Ventas - Abarrotes Bárbara';
                break;
            case '/xampp/htdocs/VSCodePHP/Login_php/corte_caja.php';
                echo 'Corte de caja - Abarrotes Bárbara';
                break;
            default:
                echo 'Abarrotes Bárbara';
                break;
        }
        ?>
    </title>
    <!-- Bootswatch 5 United Template 
        <link rel="stylesheet" href="https://bootswatch.com/5/united/bootstrap.min.css">
        <link rel="stylesheet" href="http://localhost:3000/xampp/htdocs/VSCodePHP/Login_php/assets/public/css/Bootswatch/CSS/bootstrap.min.css">
        -->

    <link rel="stylesheet" href="https://bootswatch.com/5/united/bootstrap.min.css">

    <!-- Font Awesome 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="http://localhost:3000/xampp/htdocs/VSCodePHP/Login_php/assets/public/css/Font_Awesome/CSS/all.min.css">
    
-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- jQuery 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="http://localhost:3000/xampp/htdocs/VSCodePHP/Login_php/assets/public/js/jquery/jquery-3.6.0.min.js"></script>    
-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


    <!--Datepicker
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    
    <link rel="stylesheet" href="http://localhost:3000/xampp/htdocs/VSCodePHP/Login_php/assets/public/css/Datepicker/CSS/bootstrap-datepicker.min.css">
    <script src="http://localhost:3000/xampp/htdocs/VSCodePHP/Login_php/assets/public/css/Datepicker/JS/bootstrap-datepicker.min.js"></script>

    -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


</head>

<body>
    <style>
        .paginador ul {
            display: flex;
            flex-direction: row;
        }

        .paginador ul li {
            margin: 2px;
            padding-left: 25px;
            padding-right: 25px;
            padding-top: 5px;
            padding-bottom: 5px;
            list-style: none;
            border: 1px solid gray;
            border-radius: 5px;
        }

        .pageSelected {
            background: #e95420;
            color: white;
        }
    </style>
    

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="index.php" class="navbar-brand">Abarrotes Bárbara</a>
            <?php
            $pagina = $_SERVER["SCRIPT_NAME"];
            //echo $_SERVER["SCRIPT_NAME"];
            if ($_SERVER['SCRIPT_NAME'] != '/Login_php/login.php') {
            ?>
                <a href="categorias.php" class="navbar-brand"><i class="fa-solid fa-gears"></i>Administración de categorias</a>
            <?php
            }
            ?>

        </div>
    </nav>
 