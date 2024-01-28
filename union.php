<?php
	include_once('inc/vImage.php');
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('inc/activaerror.php');
	$laBitacora='union.log';
	 $Cdatos_p = new ODBC_Conn("DACEPOZ","sysadm","");
	 $dSQL = " SELECT ci_e,apellido1,apellido2,nombre1,nombre2 from grado_1012";
	 $Cdatos_p->ExecSQL($dSQL);
	 #echo $Cdatos_p->filas;
	 $dp=$Cdatos_p->result;
	 $filas = $Cdatos_p->filas;
	 $i=0;
	 #echo $filas;
	 #print_r($Cdatos_p->result);
		while ($i<=$filas-1){
			#echo $i."<BR>";
			$nombres=strtoupper($dp[$i][3]." ".$dp[$i][4]);
			$apellidos=strtoupper($dp[$i][1]." ".$dp[$i][2]);
			#echo $dp[$i][0]." ".$nombres." ".$apellidos."<BR>";
			$Cdatos_m = new ODBC_Conn("DACEPOZ","sysadm","",$ODBCC_conBitacora, $laBitacora);
			$dSQL = " UPDATE DACE002_GRAD set apellidos='".$apellidos."', nombres='".$nombres."' ";
			$dSQL .= " WHERE ci_e='".$dp[$i][0]."'";
			$Cdatos_m->ExecSQL($dSQL,__LINE__,true);
			$i++;
		}
		echo $Cdatos_m->fmodif;
?>