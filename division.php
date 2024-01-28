<?php
	include_once('inc/vImage.php');
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('inc/activaerror.php');

	 $Cdatos_p = new ODBC_Conn("DACEPOZ","N","N");
	 $dSQL = " SELECT exp_e,nombres from serv_ins";
	 $Cdatos_p->ExecSQL($dSQL);
	 #echo $Cdatos_p->filas;
	 $dp=$Cdatos_p->result;
	 $filas = $Cdatos_p->filas;
	 $i=0;
	 #echo $filas;
	 print_r($Cdatos_p->result);
		/*while ($i<=$filas-1){
			#echo $i."<BR>";
			$nombres=explode(" ",$dp[$i][2]);
			$apellidos=explode(" ",$dp[$i][1]);
			$Cdatos_m = new ODBC_Conn("DACEPOZ","sysadm","");
			$dSQL = " UPDATE DACE002 set apellidos='".$apellidos[0]."',apellidos2='".$apellidos[1]."',";
			$dSQL .= " nombres='".$nombres[0]."',nombres2='".$nombres[1]."'";
			$dSQL .= " WHERE exp_e='".$dp[$i][0]."'";
			$Cdatos_m->ExecSQL($dSQL);
			$i++;
		}*/
		echo $Cdatos_m->fmodif;
?>