<?php
print $noCache; 
print $noJavaScript; 


include_once("inc/vImage.php");
include_once('../inc/odbcss_c.php');
include_once ('inc/config.php');
include_once ('../inc/activaerror.php');

//print_r($HTTP_POST_VARS);

if(isset($_POST['acta'])) {
	
	$acta=$_POST['acta'];
	$cedula=$_POST['cidoc'];
	/*print $acta;
	print $cedula;*/

}else echo "<script>document.location.href='../notas';</script>\n";

//NUMERO DE ACTAS
$Ccont = new ODBC_Conn("DACEPOZ","N","N");
$mSQL = "select distinct count(*) ";
$mSQL= $mSQL."from his_act ";
$mSQL= $mSQL."where his_ced='$cedula' and ";
$mSQL= $mSQL."his_lap='$lapsoProceso'  ";
$Ccont->ExecSQL($mSQL);
$reg=$Ccont->result;
foreach($reg as $reg1){}
$reg2=$reg1[0]-1;
//Consulta Anterior
$Cact = new ODBC_Conn("DACEPOZ","N","N");
$mSQL = "select distinct a.his_act ";
$mSQL= $mSQL."from his_act a,tblaca008 c,dace004 d ";
$mSQL= $mSQL."where a.his_cod=c.c_asigna and his_ced='$cedula' and ";
$mSQL= $mSQL."a.his_lap='$lapsoProceso' and a.his_cod=d.c_asigna and ";
$mSQL= $mSQL."a.his_lap=d.lapso and a.his_act=d.acta";
$Cact->ExecSQL($mSQL);
$actass=$Cact->result;

if ($reg2 == -1){echo "<script>document.location.href='../notas/error.php';</script>\n";}

$sw='0';
for ($i=0;$i<=$reg2;$i++){
	//print $actass[$i][0];
	if ($acta == $actass[$i][0]){
		$sw='0';break;
	}else $sw='1';
	
}
//print $acta;
//print $sw;
if ($sw == '1') { 
	print "ERROR";
	echo "<script>document.location.href='../notas/error.php';</script>\n";
}

$fecha  = date('Y-m-d', time() - 3600*date('I'));
$hora   = date('h:i:s', time() - 3600*date('I'));


?>

<!-- ACTA ELECTRONICA SOLO INFORMATIVA-->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	
<?php

include_once("inc/vImage.php");
include_once('../inc/odbcss_c.php');
include_once ('inc/config.php');
include_once ('../inc/activaerror.php');

?>

<style type="text/css">
<!--
#prueba {
  overflow:hidden;
  color:#00FFFF;
  background:#F7F7F7;
}

.titulo {
  text-align: center; 
  font-family:Arial; 
  font-size: 13px; 
  font-weight: normal;
  margin-top:0;
  margin-bottom:0;	
}
.tit14 {
  text-align: center; 
  font-family: Arial; 
  font-size: 13px; 
  font-weight: bold;
  letter-spacing: 1px;
  font-variant: small-caps;
}
.instruc {
  font-family:Arial; 
  font-size: 12px; 
  font-weight: normal;
  background-color: #FFFFCC;
}
.datosp {
  text-align: left; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#F0F0F0; 
  font-variant: small-caps;
}
.boton {
  text-align: center; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#e0e0e0; 
  font-variant: small-caps;
  height: 20px;
  padding: 0px;
}
.enc_p {
  color:#FFFFFF;
  text-align: center; 
  font-family:Helvetica; 
  font-size: 11px; 
  font-weight: normal;
  background-color:#3366CC;
  height:20px;
  font-variant: small-caps;
}
.inact {
  text-align: center; 
  font-family:Arial; 
  font-size: 10px; 
  font-weight: normal;
  background-color:#F0F0F0;
}
.inact2 {
  text-align: left; 
  font-family:Arial; 
  font-size: 10px; 
  font-weight: normal;
  background-color:#F0F0F0;
}
.inact3 {
  text-align: left; 
  font-family:Arial; 
  font-size: 9px; 
  font-weight: normal;
  background-color:#F0F0F0;
}
.act { 
  text-align: center; 
  font-family:Arial; 
  font-size: 11px; 
  font-weight: normal;
  background-color:#99CCFF;
}

DIV.peq {
   font-family: Arial;
   font-size: 9px;
   z-index: -1;
}
select.peq {
   font-family: Arial;
   font-size: 8px;
   z-index: -1;
   height: 11px;
   border-width: 1px;
   padding: 0px;
   width: 84px;
}
.datosp {
  text-align: left; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#F0F0F0; 
  font-variant: small-caps;
}

-->
</style>

<?
//Consulta de notas incluidas
$Ccon = new ODBC_Conn("DACEPOZ","N","N");
$mSQL = "select a.exp_e,a.apellidos,a.nombres,b.acta,b.c_asigna,b.status,";
$mSQL= $mSQL."b.calificacion,c.asignatura,f.his_sec,e.ci,e.apellido,e.nombre,f.his_fec ";
$mSQL= $mSQL."from dace002 a,dace004 b,tblaca008 c,tblaca007 e,his_act f ";
$mSQL= $mSQL."where b.lapso='$lapsoProceso' and b.acta ='$acta' and f.his_ced='$cidoc' ";
$mSQL= $mSQL."and a.exp_e=b.exp_e and b.c_asigna=c.c_asigna ";
$mSQL= $mSQL."and b.c_asigna=f.his_cod and b.lapso=f.his_lap ";
$mSQL= $mSQL."and b.acta=f.his_act and f.his_ced=e.ci order by 2";
$Ccon->ExecSQL($mSQL);
$lista_c=$Ccon->result;

foreach ($lista_c as $est){
	$secc = $est[8];
	$acta = $est[3];
	$cidoc = $est[9];
	$nombdoc = $est[11];
	$apedoc = $est[10];
	$asig = $est[7];
	$cod = $est[4];
}

//Contamos los inscritos
$Cins= new ODBC_Conn("DACEPOZ","N","N");
$mSQL = "select count(*) from dace004 where acta='$acta' and lapso='$lapsoProceso'";
$Cins->ExecSQL($mSQL);
$lista_i=$Cins->result;

foreach ($lista_i as $ins){}

//Contamos los retirados
$Cret= new ODBC_Conn("DACEPOZ","N","N");
$mSQL = "select count(*) from dace006 where acta='$acta' and lapso='$lapsoProceso' and status='2'";
$Cret->ExecSQL($mSQL);
$lista_r=$Cret->result;

foreach ($lista_r as $ret){}



?>
<title>CONSULTA DE NOTAS&nbsp;PARA&nbsp;<? print $nombdoc."  ".$apedoc." - ".$asig." - ".$lapsoProceso ?></title>

</head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="1" width="650">
	<tr>
		<td class="inact"><IMG SRC="imagenes/unex15.gif" WIDTH="75" HEIGHT="75" BORDER="0" ALT="">
		</td>
		
		<td class="inact" >Universidad Nacional Experimental Polit&eacute;cnica<BR>"Antonio Jos&eacute; de Sucre"<BR>Vicerrectorado&nbsp;<? print $vicerrectorado?><BR><? print $nombreDependencia  ?><BR> <? print $tProceso ?>&nbsp;Lapso&nbsp;<? print $lapsoProceso ?>
		</td>
		<td bgcolor="#A7A7A7">&nbsp</td>
		
		<td bgcolor="#EFEFEF" class="datosp">
			&nbsp;&nbsp;<B>Fecha:</B>&nbsp;<?echo date("d/m/Y");?>&nbsp;<B>Hora:</B>&nbsp;<? print $hora; ?>&nbsp;&nbsp;&nbsp;&nbsp;<B>Lapso</B>:&nbsp;<? print $lapsoProceso ?><BR>
			
			&nbsp;&nbsp;<B>Docente:</B>&nbsp;<? print $nombdoc?>&nbsp;&nbsp;<? print $apedoc ?>&nbsp;<B>CI:</B>&nbsp;<? print $cidoc ?><BR>

			&nbsp;<B>Secci&oacute;n:</B>&nbsp;<? print $secc?>&nbsp;&nbsp;<B>C&oacute;digo:&nbsp;</B><?print $cod?>&nbsp;&nbsp;<B>Acta:</B>&nbsp;<? print $acta ?><BR>
			&nbsp;<B>Asignatura:</B>&nbsp;<? print $asig?><BR>
			&nbsp;<B>Inscritos:</B>&nbsp;<? print $ins[0]?>&nbsp;&nbsp;&nbsp;<B>Retirados:</B>&nbsp;<? print $ret[0]?><BR>
			&nbsp;<B>Fecha de Carga:</B>&nbsp;<? print $est[12]?>
				 
		</td>
	</tr>
	<tr><td colspan="4" bgcolor="#000000"></td></tr>
	<tr><td colspan="4" bgcolor="#000000"></td></tr>
	<tr><td colspan="4" bgcolor="#000000"></td></tr>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="1" width="650">


	<tr>
		<td style="width: 20px;" class="enc_p">NRO</td>
		<td style="width: 70px;" class="enc_p">EXPEDIENTE</td>
		<td style="width: 140px;" class="enc_p">APELLIDOS</td>
		<td style="width: 140px;" class="enc_p">NOMBRES</td>
		<td style="width: 40px;" class="enc_p">NOTA</td>
		<td style="width: 120px;" class="enc_p">EN LETRAS</td>
		<td style="width: 70px;" class="enc_p">OBS</td>
       
	</tr>
	<tr><td colspan="7" bgcolor="#000000"></td></tr>
	<tr><td colspan="7" bgcolor="#000000"></td></tr>
	<tr><td colspan="7" bgcolor="#000000"></td></tr>

	<form action="registrar.php" method="POST" name="notas" onsubmit = "return confirm('¿Esta seguro de incluir las notas de esta acta?\n Una vez ingresados no podran ser modificados')">
			<?
			$nota=array();
			$nro=0;
			//Consulta de notas incluidas
			$Ccon = new ODBC_Conn("DACEPOZ","N","N");
			$mSQL = "select a.exp_e,a.apellidos,a.nombres,b.acta,b.c_asigna,b.status,";
			$mSQL= $mSQL."b.calificacion,c.asignatura,f.his_sec,e.ci,e.apellido,e.nombre,f.his_fec ";
			$mSQL= $mSQL."from dace002 a,dace004 b,tblaca008 c,tblaca007 e,his_act f ";
			$mSQL= $mSQL."where b.lapso='$lapsoProceso' and b.acta ='$acta' and f.his_ced='$cidoc' ";
			$mSQL= $mSQL."and a.exp_e=b.exp_e and b.c_asigna=c.c_asigna ";
			$mSQL= $mSQL."and b.c_asigna=f.his_cod and b.lapso=f.his_lap ";
			$mSQL= $mSQL."and b.acta=f.his_act and f.his_ced=e.ci order by 2";
			$Ccon->ExecSQL($mSQL);
			$lista_c=$Ccon->result;
			foreach ($lista_c as $est){
				$nro++;
				//$nota[$nro]=$nro;
				//Buscar en la tabla el equivalente en letras
				$Clet = new ODBC_Conn("DACEPOZ","N","N");
				$mSQL = "select letras ";
				$mSQL= $mSQL."from letras ";
				$mSQL= $mSQL."where nota='$est[6]'";
				$Clet->ExecSQL($mSQL);
				$letras=$Clet->result;
				foreach ($letras as $let)
				//Seleccionar estatus
				
				if ($est[5] == '0'){
					$estatus = "Aprobado";
				}
				if ($est[5] == '1'){
					$estatus = 'Reprobado';
				}
				if ($est[5] == 'I'){
					$estatus = 'Inasistente';
				}			

				print "<tr>";
				print "<td><div class=\"inact\">$nro</div></td>";
				print "<td><div class=\"inact\">$est[0]</div></td>";
				print "<td><div class=\"inact2\">$est[1]</div></td>";
				print "<td><div class=\"inact2\">$est[2]</div></td>";
				print "<td><div class=\"inact\">$est[6]</div></td>";
				print "<td><div class=\"inact3\">$let[0]</div></td>";
				print "<td><div class=\"inact2\">$estatus</div></td>";
				
				print "</tr>";
				}
		?>
	<tr><td colspan="7" bgcolor="#000000" align="center"></td></tr>
	<tr><td colspan="7" bgcolor="#000000" align="center"></td></tr>
	<tr><td colspan="7" bgcolor="#000000" align="center"></td></tr>
	<tr><td colspan="7"><FONT SIZE="2" COLOR="#FF0000" FACE="arial"><B>ATENCION:</B><UL>
	<LI>Acta solo con fines informativos para uso docente.
	<LI>Acta original retirar por su departamento para verificaci&oacute;n y firma.
	
</UL></FONT></td></tr>
	<tr>
                    
                    <td valign="top" colspan="3"><p align="center">
                        <BR><input type="button" value="Cerrar" name="cerrar" class="boton" 
                         onclick="javascript:self.close();"></p> 
                    </td>
					<td valign="top" colspan="4"><p align="center">
                        <BR><input type="reset" value="Imprimir" name="imprimir" class="boton" onclick="window.print();"></p> 
                    </td>
                   
                </tr>
</form>
</table>
</body>
</html>