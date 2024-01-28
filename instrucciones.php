<php?
include_once ('../inc/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<title><?php echo 'Instrucciones '.$tProceso . $lapso; ?></title>
<style type="text/css">
<!--
.instruc {
  font-family:Arial; 
  font-size: 13px; 
  font-weight: normal;
  background-color: #FFFFCC;
}
.act { 
  text-align: center; 
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color:#99CCFF;
}
-->
</style>  
</head>


<?php
	if ($_GET['tp'] == '1') {
		$titulo = 'Inscripci&oacute;n ';
		$msgInscribir = "inscribir";
	}
	else if ($_GET['tp'] == '2') {
		$titulo = 'Inclusiones y Retiros ';
		$msgInscribir = "incluir o elige \"RETIRAR\"";
		$msgInscribir .=" para las materias inscritas que desees retirar";
	}

    print <<<P001
<body onload="javascript:self.focus();">
<table border="0" width="680">
	<tr><td class="act" style="font-size:14px; font-weight:bold;">
	INSTRUCCIONES GENERALES</td></tr>
	<tr><td class="instruc">
        <ul>
            <li style="list-style-type: square;">
            Por favor complete todos y cada uno de los datos solicitados.</li>
			<li style="list-style-type: square;">
            Para seleccionar las fechas, haga click sobre la imagen que all&iacute; aparece.</li>
			<li style="list-style-type: square;">
            En el caso de inclusi&oacute;n de datos a: hijos, asignaturas, cargos, y datos acad&eacute;micos, complete los datos solicitados y luego continue llenando el formulario.</li>
			<li style="list-style-type: square;">
            Para comenzar de nuevo presione el bot&oacute;n "BORRAR TODO".</li>
			<li style="list-style-type: square;">
            Los datos no se almacenar&aacute;n hasta tanto no presione el bot&oacute;n "PROCESAR" en cada ventana de captura de datos.</li>
			<li style="list-style-type: square;">
            Cualquier duda, comentario o sugerencia, dir&iacute;jase a URACE.</li>
            <li style="list-style-type: square;">
            Esta consulta est&aacute; sujeta a los reglamentos vigentes.</li>
        </ul>
        </td>
    </tr>
	<tr><td align="center">
	<input type="button" value="Cerrar" onclick="javascript:self.close();">
	</table>
P001;
?>
</body>
</html>