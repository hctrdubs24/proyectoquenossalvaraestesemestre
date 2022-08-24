<?php

include('./database/db.php');

session_start();
if (!empty($_POST)) {
    //Agregar producto al detalle
    if ($_POST['action'] == 'addProductDetalle') {
        if (empty($_POST['producto']) || empty($_POST['cantidad'])) {
            echo 'error';
        } else { 
            $codproducto = $_POST['producto'];
            $cantidad = $_POST['cantidad'];
            $token = md5($_SESSION['email']);

            $query_iva = mysqli_query($conn, 'SELECT IVA FROM configuracion');
            $result_iva = mysqli_num_rows($query_iva);

            $query_detalle_temp = mysqli_query($conn, "CALL add_detalle_temp($codproducto, $cantidad, '$token')");
            $result = mysqli_num_rows($query_detalle_temp);

            $detalleTabla = '';
            $sub_total = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if ($result > 0) {
                if ($result_iva > 0) {
                    $info_iva = mysqli_fetch_assoc($query_iva);
                    $iva = $info_iva['IVA'];
                }

                while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
                    $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                    $sub_total = round($sub_total + $precioTotal, 2);
                    $total = round($total + $precioTotal, 2);

                    $detalleTabla .= '
                                        <tr>
                                            <td>
                                                <p id="codProd">' . $data['id_product'] . '</p>
                                            </td>
                                            <td colspan="2">
                                                <p>' . $data['nombre_producto'] . '</p>
                                            </td>
                                            <td>
                                                <p>' . $data['cantidad'] . '</p>
                                            </td>
                                            <td>
                                                <p id="precio">' . $data['precio_venta'] . '</p>
                                            </td>
                                            <td>
                                                <p id="total"> ' . $precioTotal . ' </p>
                                            </td>
                                            <td class="">
                                                <a href="#" onclick="event.preventDefault(); del_product_datalle(' . $data['id'] . ');" style="border: solid rgb(190, 48, 37) 1px; color: rgb(190, 48, 37);" class="btn link_delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                    Eliminar
                                                </a>
                                            </td>
                                        </tr>
                    ';
                }

                $impuesto = round($sub_total * ($iva / 100), 2);
                $tl_sniva = round($sub_total - $impuesto, 2);
                $total = round($tl_sniva + $impuesto, 2);

                $detalleTotales = '
                                    <tr>
                                        <td colspan="5">Subtotal: </td>
                                        <td colspan="2"><span>$' . $tl_sniva . '</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">IVA (16%):</td>
                                        <td colspan="2"><span>' . $impuesto . '</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Total:</td>
                                        <td colspan="2"><span>$' . $total . '</span></td>
                                    </tr>
                ';

                $arrayData['detalle'] = $detalleTabla;
                $arrayData['totales'] = $detalleTotales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            } else {
                echo 'error';
            }
            mysqli_close($conn);
        }
        exit;
    }
}else{
    echo 'post vacio';
}
