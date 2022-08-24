<?php
$subtotal 	= 0;
$iva 	 	= 0;
$impuesto 	= 0;
$tl_sniva   = 0;
$total 		= 0;
//print_r($configuracion); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Factura</title>
	<link rel="stylesheet" href="http://localhost:3000/xampp/htdocs/VSCodePHP/Login_php/factura/style.css">
</head>
<style>
	/*@import url("fonts/BrixSansRegular.css");
	@import url("fonts/BrixSansBlack.css");*/

	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	p,
	label,
	span,
	table {
		/*font-family: "BrixSansRegular";*/
		font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
		font-size: 9pt;
	}

	.h2 {
		/*font-family: "BrixSansBlack";*/
		font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
		font-size: 16pt;
		font-weight: bolder;
	}

	.h3 {
		/*font-family: "BrixSansBlack";*/
		font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
		font-size: 12pt;
		display: block;
		background: #e95420;
		color: #fff;
		text-align: center;
		padding: 3px;
		margin-bottom: 5px;
	}

	#page_pdf {
		width: 95%;
		margin: 15px auto 10px auto;
	}

	#factura_head,
	#factura_cliente,
	#factura_detalle {
		width: 100%;
		margin-bottom: 10px;
	}

	.logo_factura {
		width: 25%;
	}

	.info_empresa {
		width: 50%;
		text-align: center;
	}

	.info_factura {
		width: 25%;
	}

	.info_cliente {
		width: 100%;
	}

	.datos_cliente {
		width: 100%;
	}

	.datos_cliente tr td {
		width: 50%;
	}

	.datos_cliente {
		padding: 10px 10px 0 10px;
	}

	.datos_cliente label {
		width: 75px;
		display: inline-block;
	}

	.datos_cliente p {
		display: inline-block;
	}

	.textright {
		text-align: right;
	}

	.textleft {
		text-align: left;
	}

	.textcenter {
		text-align: center;
	}

	.round {
		border-radius: 5px;
		border: 2px solid #000;
		overflow: hidden;
		padding-bottom: 15px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		-ms-border-radius: 5px;
		-o-border-radius: 5px;
	}

	.round p {
		padding: 0 15px;
	}

	#factura_detalle {
		border-collapse: collapse;
	}

	#factura_detalle thead th {
		background: #e95420;
		color: #fff;
		padding: 5px;
	}

	#detalle_productos tr:nth-child(even) {
		background: #ededed;
	}

	#detalle_totales span {
		/*font-family: "BrixSansBlack";*/
		font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
	}

	.nota {
		font-size: 8pt;
	}

	.label_gracias {
		font-family: verdana;
		font-weight: bold;
		font-style: italic;
		text-align: center;
		margin-top: 20px;
	}

	.anulada {
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translateX(-50%) translateY(-50%);
	}

	/*#logo{
		background: url('img/abaBarLogin.png');
	}*/
</style>

<?php
#Imagen
#$nombreImagen = 'abaBarLogin.png';
#$imagenBase64 = "datos:img/png:base64,".base64_encode(file_get_contents($nombreImagen));
?>

<body>
	<?php echo $anulada; ?>
	<div id="page_pdf">
		<table id="factura_head">
			<tr>
				<td class="logo_factura">
					<div id="logo">
						<!--
							<img src="http://localhost:3000/xampp/htdocs/VSCodePHP/Login_php/factura/img/abaBarLogin.png">
							<img src="<?php echo $imagenBase64 ?>" alt="">
						-->
						<img src="./img/abaBarLogin.png" alt="" style="width: 100%;">
					</div>
				</td>
				<td class="info_empresa">
					<?php
					if ($result_config > 0) {
						$iva = $configuracion['IVA'];
					?>
						<div>
							<span class="h2"><?php echo $configuracion['nombre']; ?></span>
							<p><?php echo $configuracion['razon_social']; ?></p>
							<p><?php echo $configuracion['direccion']; ?></p>
							<p>RFC: <?php echo $configuracion['RFC']; ?></p>
							<p>Teléfono: <?php echo $configuracion['telefono']; ?></p>
							<p>Email: <?php echo $configuracion['email']; ?></p>
						</div>
					<?php
					}
					?>
				</td>
				<td class="info_factura">
					<div class="round">
						<span class="h3">Factura</span>
						<p>No. Factura: <strong><?php echo $factura['id_factura']; ?></strong></p>
						<p>Fecha: <?php echo $factura['fecha']; ?></p>
						<p>Hora: <?php echo $factura['hora']; ?></p>
						<p>Vendedor: <?php echo $factura['vendedor']; ?></p>
					</div>
				</td>
			</tr>
		</table>
		<table id="factura_cliente">
			<tr>
				<td class="info_cliente">
					<div class="round">
						<span class="h3">Cliente</span>
						<table class="datos_cliente">
							<tr>
								<td><label>RFC:</label>
									<p><?php echo $factura['rfc']; ?></p>
								</td>
								<td><label>Teléfono:</label>
									<p><?php echo $factura['tel']; ?></p>
								</td>
							</tr>
							<tr>
								<td><label>Nombre:</label>
									<p><?php echo $factura['nombre']; ?></p>
								</td>
								<td><label>Dirección:</label>
									<p><?php echo $factura['direccion']; ?></p>
								</td>
							</tr>
						</table>
					</div>
				</td>

			</tr>
		</table>

		<table id="factura_detalle">
			<thead>
				<tr>
					<th width="50px">Cant.</th>
					<th class="textleft">Descripción</th>
					<th class="textright" width="150px">Precio Unitario.</th>
					<th class="textright" width="150px"> Precio Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">

				<?php
				if ($result_detalle > 0) {
					while ($row = mysqli_fetch_assoc($query_productos)) {
				?>
						<tr>
							<td class="textcenter"><?php echo $row['cantidad']; ?></td>
							<td><?php echo $row['nombre_producto']; ?></td>
							<td class="textright"><?php echo $row['precio_venta']; ?></td>
							<td class="textright"><?php echo $row['precio_total']; ?></td>
						</tr>
				<?php
						$precio_total = $row['precio_total'];
						$subtotal = round($subtotal + $precio_total, 2);
					}
				}

				$impuesto 	= round($subtotal * ($iva / 100), 2);
				$tl_sniva 	= round($subtotal - $impuesto, 2);
				$total 		= round($tl_sniva + $impuesto, 2);
				?>
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="3" class="textright"><span>SUBTOTAL $.</span></td>
					<td class="textright"><span><?php echo $tl_sniva; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
					<td class="textright"><span><?php echo $impuesto; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>TOTAL $.</span></td>
					<td class="textright"><span><?php echo $total; ?></span></td>
				</tr>
			</tfoot>
		</table>
		<div>
			<p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p>
			<h4 class="label_gracias">¡Gracias por su compra!</h4>
		</div>

	</div>

</body>

</html>