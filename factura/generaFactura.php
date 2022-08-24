<?php

//print_r($_REQUEST);
//exit;
//echo base64_encode('2');
//exit;

//session_start();
/*if (empty($_SESSION['active'])) {
	header('location: ../');
}*/
include '../database/db.php';
//require_once '../pdf/vendor/autoload.php';
require_once '../libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
//use Dompdf\Options;

if (empty($_REQUEST['cl']) || empty($_REQUEST['f'])) {
	echo "No es posible generar la factura.";
} else {
	$codCliente = $_REQUEST['cl'];
	$noFactura = $_REQUEST['f'];
	$anulada = '';

	$query_config   = mysqli_query($conn, "SELECT * FROM configuracion");
	$result_config  = mysqli_num_rows($query_config);
	if ($result_config > 0) {
		$configuracion = mysqli_fetch_assoc($query_config);
	}


	$query = mysqli_query($conn, "SELECT f.id_factura, DATE_FORMAT(f.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha,'%H:%i:%s') as  hora, f.id_cliente, f.estatus,
												 v.email as vendedor,
												 cl.rfc, cl.nombre, cl.tel, cl.direccion
											FROM factura f
											INNER JOIN usuarios v
											ON f.usuario = v.id
											INNER JOIN cliente cl
											ON f.id_cliente = cl.id
											WHERE f.id_factura = $noFactura AND f.id_cliente = $codCliente  AND f.estatus != 10 ");

	$result = mysqli_num_rows($query);
	if ($result > 0) {
		$factura = mysqli_fetch_assoc($query);
		$no_factura = $factura['id_factura'];

		if ($factura['estatus'] == 2) {
			$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
		}

		$query_productos = mysqli_query($conn, "SELECT p.nombre_producto,dt.cantidad,dt.precio_venta,(dt.cantidad * dt.precio_venta) as precio_total
														FROM factura f
														INNER JOIN detalle_factura dt
														ON f.id_factura = dt.no_factura
														INNER JOIN productos p
														ON dt.cod_producto = p.id
														WHERE f.id_factura = $no_factura ");
		$result_detalle = mysqli_num_rows($query_productos);

		ob_start();
		    include(dirname('__FILE__').'/factura.php');
		    $html = ob_get_clean();
			/*$options = new Options();
			$options->set('isRemoteEnabled',True);*/
			// instantiate and use the dompdf class
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait');
			// Render the HTML as PDF
			$dompdf->render();
			// Output the generated PDF to Browser
			$dompdf->stream('factura_'.$noFactura.'.pdf',array('Attachment'=>0));
			exit;
	}
}
?>