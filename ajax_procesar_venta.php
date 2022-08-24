<?php

include('database/db.php');
session_start();
if (!empty($_POST)) {
    #Procesar Venta
    if ($_POST['action'] == 'procesarVenta') {
        if (empty($_POST['codCliente'])) {
            $codCliente = 1;
        } else {
            $codCliente = $_POST['codCliente'];
        }

        $token = md5($_SESSION['email']);
        $usuario = $_SESSION['id'];
        $query = mysqli_query($conn, "SELECT * FROM detalle_temp WHERE token_user = '$token';");
        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $query_procesar = mysqli_query($conn, "CALL procesar_venta($usuario, $codCliente, '$token');");
            $result_detalle = mysqli_num_rows($query_procesar);
            if ($result_detalle > 0) {
                $data = mysqli_fetch_assoc($query_procesar);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
    mysqli_close($conn);
    exit;
} else {
    echo 'post vacio';
}
exit;