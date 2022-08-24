<?php

include('./database/db.php');

session_start();
if (!empty($_POST)) {
    //Buscar cliente
    if ($_POST['action'] == 'searchCliente') {
        if (empty($_POST['cliente'])) {
            echo 'error';
        } else {
            $rfc = $_POST['cliente'];
            $query = mysqli_query($conn, "SELECT * FROM cliente WHERE rfc LIKE '$rfc' and estatus = 1");
            mysqli_close($conn);
            $result = mysqli_num_rows($query);
            $data = '';
            if ($result > 0) {
                $data = mysqli_fetch_assoc($query);
            } else {
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
} else {
    echo 'post vacio';
}
exit;
