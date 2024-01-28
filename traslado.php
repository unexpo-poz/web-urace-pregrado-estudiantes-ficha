<?php
	include_once('inc/vImage.php');
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('inc/activaerror.php');

	 $Cdatos_p = new ODBC_Conn("CENTURA-DACE","SYSADM","SOMOSN");
	 $dSQL = " SELECT b.ci_e,b.exp_e,a.nombres from serv_ins a, dace002 b where a.exp_e=b.exp_e";
	 $Cdatos_p->ExecSQL($dSQL);
	 #echo $Cdatos_p->filas;
	 $dp=$Cdatos_p->result;
	 $filas = $Cdatos_p->filas;
	 $i=0;
	 $j=0;
	 //echo $filas;
	 //print_r($Cdatos_p->result);
		while ($i<=$filas-1){
			echo 1+$i." EXP: ".$dp[$i][1]." LAPSO: ".$dp[$i][2]."<BR>";
			//$nombres=explode(" ",$dp[$i][2]);
			//$apellidos=explode(" ",$dp[$i][1]);
	$Cdatos_m = new ODBC_Conn("CENTURA-DACE","sysadm","SOMOSN");
	$dSQL = "INSERT INTO DACE006 (ACTA,EXP_E,C_ASIGNA,LAPSO,CALIFICACION,STATUS,AFEC_INDICE,SECCION) ";
	$dSQL.= "VALUES ('300622','".$dp[$i][1]."','300622','".$dp[$i][2]."','','7','','SV') ";

			             
			//set apellidos='".$apellidos[0]."',apellidos2='".$apellidos[1]."',";
			//$dSQL .= " nombres='".$nombres[0]."',nombres2='".$nombres[1]."'";
			//$dSQL .= " WHERE exp_e='".$dp[$i][0]."'";
		$Cdatos_m->ExecSQL($dSQL);
		if ($Cdatos_m->fmodif == 1){
			$j++;
		}
		$i++;
		}
		echo "<br><br>filas modificadas: ".$j;
?>