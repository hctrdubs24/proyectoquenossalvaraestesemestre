<?php

include('database/db.php');
session_start();
//Anular factura
if (!empty($_POST)) {
    if ($_POST['action'] == 'anularFactura') {
        if (!empty($_POST['noFactura'])) {
            $noFactura = $_POST['noFactura'];
            $query_anular = mysqli_query($conn, "CALL anular_factura($noFactura);");
            mysqli_close($conn);
            $result = mysqli_num_rows($query_anular);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query_anular);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error';
            exit;
        }
    }
} else {
    echo 'post vacio';
}
