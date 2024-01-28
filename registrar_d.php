<?php

include_once('inc/odbcss_c.php');
include_once('inc/config.php');
include_once ('inc/activaerror.php');
include_once("inc/vImage.php");

print $noCache; 
print $noJavaScript; 

//print $lapsoProceso;print "<BR>";
//print_r($HTTP_POST_VARS);

$fecha = date('Y-m-d', time() - 3600*date('I'));
$hora = date('h:i:s', time() - 3600*date('I'));

if (isset($_POST)){
	//DATOS PERSONALES
	if(isset($_POST['doc_identS'])){
		$doc_ident=$_POST['doc_identS'];
		$passport=$_POST['pasaporte_nro'];
		$res_ext=$_POST['res_extS'];
	}else {
		$doc_ident='';
		$passport='';
		$res_ext='';
	}

	$apellidos2=$_POST['apellidos2'];
	$nombres2=$_POST['nombres2'];
	$fnac = "19".$_POST['anioN']."-".$_POST['mesN']."-".$_POST['diaN'];
	$paisn = $_POST['p_nac_e'];
	$lugarn = $_POST['l_nac_e'];
	$edocivil = $_POST['edo_c_eS'];
	$sexo = $_POST['sexoS'];
	$email_1 = $_POST['email_1'];
	$email_2 = $_POST['email_2'];
	$avenida = $_POST['avCalle'];
	$urbanizacion = $_POST['barrio'];
	$manzana = $_POST['manzana'];
	$nrocasa = $_POST['casa'];
	$ciudad = $_POST['ciudad'];
	$estado = $_POST['estado'];
	$telf_1 = $_POST['codT'].$_POST['telefono'];
	$telf_2 = $_POST['celcod'].$_POST['celnro'];
	$telf_3 = $_POST['codfax'].$_POST['nrofax'];

	//ACTUALIZAMOS LOS DATOS PERSONALES
	$Cnot = new ODBC_Conn("CENTURA-DACE","c","c",$ODBCC_conBitacora, $laBitacora);
	$mSQL = " UPDATE dace002 SET";
	$mSQL= $mSQL." f_nac_e='".$fnac."',p_nac_e='".$paisn."',l_nac_e='".$lugarn."',";
	$mSQL= $mSQL."edo_c_e='".$edocivil."',sexo='".$sexo."',correo1='".$email_1."',";
	$mSQL= $mSQL."correo2='".$email_2."',avenida='".$avenida."',urbanizacion='".$urbanizacion."',";
	$mSQL= $mSQL."manzana='".$manzana."',nrocasa='".$nrocasa."',ciudad='".$ciudad."',";
	$mSQL= $mSQL."estado='".$estado."',telefono1='".$telf_1."',telefono2='".$telf_2."',";
	$mSQL= $mSQL."telefono3='".$telf_3."',apellidos2='".$apellidos2."',nombres2='".$nombres2."',";
	$mSQL= $mSQL."res_extraj='".$res_ext."',doc_ident='".$doc_ident."',pasaporte_nro='".$passport."'";
	$mSQL= $mSQL." WHERE ci_e='".$_POST['cedula']."'";
	$Cnot->ExecSQL($mSQL,__LINE__,true);
	
	#print $Cnot->fmodif;
	#print_r($_POST);
	
	if($Cnot->fmodif > 0){
		$msg="La informaci&oacute;n ha sido actualizada correctamente. Por favor cierre esta ventana.";
		$img="SRC=\"imagenes/bien.jpg\" WIDTH=\"75\" HEIGHT=\"100\" BORDER=\"0\" ALT=\"\"";
		}
	else {
		$msg="Ha ocurrido un error durante la actualizaci&oacute;n de los datos por favor contacte a Control de Estudios.";
		$img="SRC=\"imagenes/mal.jpg\" WIDTH=\"100\" HEIGHT=\"75\" BORDER=\"0\" ALT=\"\"";
		}
	
}else echo "<script>document.location.href='index.php';</script>\n";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script type="text/javascript">
	function contacto()
		{
		window.open('http://www.poz.unexpo.edu.ve/dace/contacto','contacto', 'location=0,resizable=0,scrollbars=0,toolbar=0,direcories=0,width=500,height=450,top=100,left=300')
		}
</script>

<style type="text/css">
<!--

a.linkopacity img {
filter:alpha(opacity=100);
-moz-opacity: 1.0;
opacity: 1.0;}
a.linkopacity:hover img {
filter:alpha(opacity=70);
-moz-opacity: 0.7;
opacity: 0.7;
}


a.opacity img {
filter:alpha(opacity=50);
-moz-opacity: 0.7;
opacity: 0.7;}
a.opacity:hover img {
filter:alpha(opacity=100);
-moz-opacity: 1.0;
opacity: 1.0;
}

.inact {
  text-align: center; 
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color:#FFFFFF;
}
.inact2 {
  text-align: justify; 
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color:#FFFFFF;
}
-->
</style>

<title><?php echo $tProceso?> > Planilla de Datos</title>
</head>

<body>
<BR>
<table border="0" width="400" id="table1" cellspacing="1" cellpadding="0" style="border-collapse: collapse;border-color:blue;" align="center">
	<tr>
		<td class="inact"><IMG SRC="imagenes/unex15.gif" WIDTH="75" HEIGHT="75" BORDER="0" ALT=""></td>
				
		<td class="inact">Universidad Nacional Experimental Polit&eacute;cnica<BR>"Antonio Jos&eacute; de Sucre"<BR>Vicerrectorado&nbsp;<? print $vicerrectorado?><BR><? print $nombreDependencia  ?>
		</td>
	<tr>
</table><BR>
<HR width="600" align="center">
<table border="0" width="500" id="table1" cellspacing="1" cellpadding="0" style="border-collapse: collapse;border-color:blue;" align="center">
	<tr>
		<td class="inact"><IMG <?=$img?>></td>
		<td class="inact2"><?=$msg?></td>
	<tr>
</table>
<HR width="600" align="center">
<table border="0" width="500" id="table1" cellspacing="1" cellpadding="0" style="border-collapse: collapse;border-color:blue;" align="center">
	<tr>
		<td class="inact2" width="250">Si presentas alg&uacute;n inconveniente con tus datos personales o acad&eacute;micos, por favor cont&aacute;ctanos.</td>
		<td class="inact" width="250">
		<a href="javascript:contacto('')" class="linkopacity">
			<IMG SRC="../../../../img/casos.png" WIDTH="150" HEIGHT="86" BORDER="0" ALT="">
		</a>
		</td>
	<tr>
</table>
</body>
<html>