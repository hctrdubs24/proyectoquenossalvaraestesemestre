<?php

include('./database/db.php');
session_start();
if (!empty($_POST)) {
    //Registrar cliente ventas
    if ($_POST['action'] == 'addCliente') {
        $rfc = $_POST['rfc_cliente'];
        $nombre = $_POST['nom_cliente'];
        $telefono = $_POST['tel_cliente'];
        $direccion = $_POST['dir_cliente'];

        $query_insert
            = mysqli_query($conn, "INSERT INTO cliente (rfc, nombre, tel, direccion) 
                                                        VALUES ('$rfc', '$nombre', '$telefono', '$direccion')");
        if ($query_insert) {
            $cod_cliente = mysqli_insert_id($conn);
            $msg = $cod_cliente;
        } else {
            $msg = 'error';
        }
        mysqli_close($conn);
        echo $msg;
        exit;
    }
} else {
    echo 'post vacio';
}
exit;
