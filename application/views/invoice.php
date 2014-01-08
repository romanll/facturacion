<?php 

/* Ejemplo de factura/recibo para generar PDF */
$a=str_replace("\n", "", "||1.0|DED6E41C-FD99-458C-ABA4-56F9DFAE9903|2014-01-05T19:03:55|LWrz3BvtJQze1K/QSk+U/dlVnuspKuWd5wsrIyVq+jirmtXUzyM1p9ThXVmvnf749hvTv6XEbGwjWOrih6xejVjKmSorppNrsxc9+euWA5LLzztcDg4ldI0sjH/gCCu77mFX7PoA6OqLc0F+OS2NySbbzrcIS1yGToD9hoFyZ/c=|20001000000100005761||");
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Invoice</title>
</head>
<body>
	<!-- Logo, Emisor & Datos de factura -->
	<div id="header">
		<table id="top">
			<tr>
				<!-- Logo : solo funciona con ruta relativa -->
				<td id="logo">
					<img src="./images/cc.jpg" width="120"/>
				</td>
				<!-- Emisor -->
				<td id="emisor">
					<h1>Seminis Vegetables Seeds Mexicana, S.de. R.L. de C.V.</h1>
					SVS960709MS2 <br>
					Carr. Transpeninsular Km 184, Col. Estado 29<br>
					San Quintín, Código Postal 22930
				</td>
				<!-- Datos Factura -->
				<td id="dfactura" align="right">
					<table class="zebra">
						<tr class="seriefolio">
							<td><h4>Factura Serie #Folio</h4></td>
						</tr>
						<tr class="z">
							<td><i>Folio Fiscal</i></td>
						</tr>
						<tr>
							<td>1234567890000</td>
						</tr>
						<tr class="z">
							<td><i>No. Certificado Digital</i></td>
						</tr>
						<tr>
							<td>00000000000</td>
						</tr>
						<tr class="z">
							<td><i>No. Certificado SAT</i></td>
						</tr>
						<tr>
							<td>00000000000000</td>
						</tr>
						<tr class="z">
							<td><i>Fecha y hora de certificación</i></td>
						</tr>
						<tr>
							<td>Fecha - Hora</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!-- Datos de cliente -->
		<table id="receptor">
			<tr>
				<th>Receptor</th>
			</tr>
			<tr>
				<td><h1>Cliente Nombre o Razon Social</h1></td>
			</tr>
			<tr>
				<td>RFC0000CL</td>
			</tr>
			<tr>
				<td>Calle Primera #123, Colonia Centro CP 55555,San Quintín, Baja California, México</td>
			</tr>
		</table>
	</div>
	<!-- Productos -->
	<div id="conceptos">
		<table>
			<thead>
				<tr>
					<th>Cantidad</th>
					<th>Unidad</th>
					<th>Descripción</th>
					<th>Precio Unitario</th>
					<th>Importe</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="txtc">1</td>
					<td class="txtc">Pieza</td>
					<td>Impresora Láser Samsung ML-2165, 20ppm, 1200x1200 dpi, USB</td>
					<td class="txtc">1,099.00</td>
					<td class="txtr">1,099.00</td>
				</tr>
				<tr>
					<td class="txtc">2</td>
					<td class="txtc">Pieza</td>
					<td>Disco Duro Externo Seagate Expansion de 2 TB, USB 3.0.</td>
					<td class="txtc">1,599.00</td>
					<td class="txtr">4,797.00</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3"></td>
					<td class="txtc"><i>Subtotal</i></td>
					<td class="txtr">5,896.00</td>
				</tr>
			</tfoot>	
		</table>
	</div>
	<!-- Totales e impuestos -->
	<div id="footer">
		<!-- importe letra, forma de pago -->
		<div id="pagos">
			<table class="zebra">
				<tr class="z">
					<td><h5>Importe con letra</h5></td>
					<td>Cuatro mil setecientos noventa y siete pesos (0/100MN)</td>
				</tr>
				<tr>
					<td><h5>Forma de Pago</h5></td>
					<td>Pago en una sola exhibicion</td>
				</tr>
				<tr class="z">
					<td><h5>Condiciones de Pago</h5></td>
					<td>Contado</td>
				</tr>
				<tr>
					<td><h5>Método de Pago</h5></td>
					<td>Efectivo</td>
				</tr>
				<tr class="z">
					<td><h5>No. Cta. Pago</h5></td>
					<td></td>
				</tr>
				<tr>
					<td><h5>Observaciones</h5></td>
					<td></td>
				</tr>
			</table>
		</div>
		<div id="impuestos">
			<table>
				<tr>
					<th colspan="2">Importe</th>
				</tr>
				<tr>
					<td><h5>Subtotal</h5></td>
					<td class="txtr">00.00</td>
				</tr>
				<tr>
					<td><h5>Descuento</h5></td>
					<td class="txtr">00.00</td>
				</tr>
				<tr>
					<td><h5>IVA 16%</h5></td>
					<td class="txtr">00.00</td>
				</tr>
				<tr>
					<td><h5>Retención ISR</h5></td>
					<td class="txtr">00.00</td>
				</tr>
				<tr>
					<td><h5>Retención IVA</h5></td>
					<td class="txtr">00.00</td>
				</tr>
				<tr>
					<td>Total</td>
					<td class="txtr">0000.00</td>
				</tr>
			</table>
		</div>
		<div style="clear:both;">
		<div id="sellos">
			<div id="qr" style="width:20%;float:left;">
				<img src="./ufiles/AAA010101AAA/qr.png" alt="qr">
			</div>
			<div style="width:80%;font-size:6pt;">
				<h5>Sello CFDI</h5>
			    LWrz3BvtJQze1K/QSk+U/dlVnuspKuWd5wsrIyVq+jirmtXUzyM1p9ThXVmvnf749hvTv6XEbGwjWOrih6xejVjKmSorppNrsxc9+euWA5LLzztcDg4ldI0sjH/gCCu77mFX7PoA6OqLc0F+OS2NySbbzrcIS1yGToD9hoFyZ/c=
			    <h5>Sello SAT</h5>
			    qwEiFHvcVp6Q088XqcoOE0Cx4PkxJSlIBxO7tTzg+TE+1n6Eb6CZvpw5+zuYEnqc0qQWwUrcacvYj5WGF3j8n2VidhcR+YmadFYw8U3/+J5znckZAhlVu3CfcUfGH0EnGsmI6sQSYqt0IN/30PZN8Hez/HziqmGRBK00S0XKbKg=
			    <h5>Cadena Original del complemento de certificación del SAT</h5>
			    ||1.0|DED6E41C-FD99-458C-ABA4-56F9DFAE9903|2014-01-05T19:03:55|
			    LWrz3BvtJQze1K/QSk+U/dlVnuspKuWd5wsrIyVq+jirmtXUzyM1p9ThXVmvnf749hvTv6XEbGwjWOrih6xejVjKmSorppNrsxc9+euWA5LLzztcDg4ldI0sjH/gCCu77mFX7PoA6OqLc0F+OS2NySbbzrcIS1yGToD9hoFyZ/c=|20001000000100005761||
			</div>
			
			    <!-- 
			    Cadena Original del complemento de certificación del SAT:
			    1.- version 1.0
				2.- el UUID
				3.- fecha de certificacion
				4.- sello digital CFDI
				5.- numero de certificado 
			     -->
		</div>
		
		<!-- Impuestos, descuentos, total -->
	</div>
	
</body>
</html>