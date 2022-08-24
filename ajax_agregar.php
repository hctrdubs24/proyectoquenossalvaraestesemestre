<?php

include('database/db.php');
session_start();
if (!empty($_POST)) {
    if ($_POST['action'] == 'addProduct') {
        if (!empty($_POST['cantidad_agregar']) || !empty($_POST['producto_id']) || !empty($_POST['action'])) {
            $cantidad = $_POST['cantidad_agregar'];
            $producto_id = $_POST['producto_id'];
            $query_insert = mysqli_query($conn, "INSERT INTO entradas (id_producto, cantidad) VALUES ($cantidad, $producto_id);");
            if ($query_insert) {
                //Ejecutar procedimiento almacenado
                $query_upd = mysqli_query($conn, "CALL actualizar_producto_cantidad($cantidad, $producto_id);");
                $result_pro = mysqli_num_rows($query_upd);
                if ($result_pro > 0) {
                    $data = mysqli_fetch_assoc($query_upd);
                    //$data['producto_id'] = $producto_id;
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                echo 'error';
            }
            mysqli_close($conn);
        } else {
            echo 'error';
        }
        exit;
    }
} else {
    echo 'post vacio';
}
