<?php

include('database/db.php');
session_start();
//Buscar la fcatura que se quiere anular.
if (!empty($_POST)) {
    if ($_POST['action'] == 'infoFactura') {
        if (!empty($_POST['noFactura'])) {
            $noFactura = $_POST['noFactura'];
            $query = mysqli_query($conn, "SELECT * FROM factura WHERE id_factura = '$noFactura' AND estatus = 1;");
            mysqli_close($conn);

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
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
