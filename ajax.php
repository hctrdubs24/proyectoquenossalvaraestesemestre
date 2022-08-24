<?php
include('./database/db.php');
#print_r($_POST);
session_start();
if (!empty($_POST)) {
    #extraer datos del producto
    if ($_POST['action'] == 'infoProducto') {
        $producto_id = $_POST['producto'];
        $query = mysqli_query($conn, "SELECT * FROM productos WHERE id = $producto_id AND 
                                            estado_producto = 1");
        mysqli_close($conn);
        $result = mysqli_num_rows($query);
        if ($result > 0) {
            $data = mysqli_fetch_assoc($query);
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            echo $json;
            exit;
        }
    } else {
        echo 'error';
        exit;
    }
}

exit;
