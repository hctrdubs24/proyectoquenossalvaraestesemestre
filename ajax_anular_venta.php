<?php

include('database/db.php');
session_start();
if (!empty($_POST)) {
    //Anaular venta
    if ($_POST['action'] == 'anularVenta') {
            $token = md5($_SESSION['email']);
            $query_del = mysqli_query($conn, "DELETE FROM detalle_temp WHERE token_user = '$token'");
            mysqli_close($conn);
            if ($query_del) {
                echo 'ok';
            }else{
                echo 'error';
            }
        exit;
    }
} else {
    echo 'post vacio';
}
