<?php
    include_once('../inc/odbcss_c.php');
    include_once('inc/config.php');
    include_once('inc/activaerror.php');
    if(isset($_POST{'espec'})){
        $esp = $_POST{'espec'};
    }
    else $esp = "";
    imprime_h();
    imprime_botones();
    //echo $esp;
    if ($esp != "") {
    //echo "imprimo!";
	   imprime_re();
    }
    imprime_f();

function imprime_h() {
	
	global $lapsoProceso, $vicerrectorado;
    print <<<xHEAD
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Resultados en L&iacute;nea de las Inscripciones $lapsoProceso</title>
<SCRIPT languaje="Javascript">
<!--
function selesp(f) {
    //alert(f);
    with(document.f_espec){
	   espec.value=f;
	   submit();
    }   
    return true; 
  }
//-->
</SCRIPT>

<style type="text/css">
          <!--
#prueba {
  overflow:hidden;
}

.titulo {
  text-align: center; 
  font-family:Arial; 
  font-size: 14px; 
  font-weight: normal;
  margin-top:0;
  margin-bottom:0;	
}

  -->
</style>  


<style type="text/css">

TD.cielo { text-align: center; background-color:#99CCFF}
TD.blanco {text-align: left; background-color:#FFFFFF}
TD.gris {text-align: left; background-color:#EFEFEF}
TD.rojo {text-align: left; background-color:#FFCC99}
FONT.negro { font-family:monospace; font-size:14px; font-weight: normal; color:#000000}
</style>  

</head>
<body>


          

<table border="0" width="750">
		<tr>
		<td width="125">
		<p align="right" style="margin-top: 0; margin-bottom: 0">

		<img border="0" src="/img/unex15.gif" 
		     width="75" height="75"></p></td>
		<td width="500">
		<p class="titulo">
		Universidad Nacional Experimental Polit&eacute;cnica</p>
		<p class="titulo">
		Vicerrectorado $vicerrectorado</font></p>
		<p class="titulo">

		Unidad Regional de Admisi&oacute;n y Control de Estudios</font></td>
		<td width="125">&nbsp;</td>
		</tr><tr><td colspan="3" style="background-color:#99CCFF;">
		<font style="font-size:2px;"> &nbsp;</font></td></tr>
</table>
<table border="0" width="750" id="table1" cellspacing="1" cellpadding="0" 
 style="border-collapse: collapse">
	<tr><td>	
<font face="arial" size="4"><center>Resultados en L&iacute;nea de las Inscripciones $lapsoProceso</center></font></td>
	</tr>
	<tr>
		<td width="750"><center>
		<font face="arial" size="2"><br>Haz clic en el nombre de un departamento 
		acad&eacute;mico para mostrar el resultado de las inscripciones</center></font></td>
	</tr>
xHEAD
;
}

function imprime_botones() {
    global $esp;
    $c_p ="white";
    $c_np ="silver";
    $bcolor = array("EB"=> $c_np, "IE"=> $c_np,"EL"=> $c_np,"IM"=> $c_np,"MT"=> $c_np,
		            "II"=> $c_np,"IQ"=> $c_np,"CA"=> $c_np); 
    if ($esp != ""){ $bcolor{$esp} = $c_p;}
    print <<<xBOT
	<tr>
		<td width="750" height="20">
		<p align="center">
			<button name="B8" style="width: 95px; height: 22px; background:
xBOT
; 
    print $bcolor{'EB'}."\" value=\"EB\"";
    print <<<xBOT1
			        onclick="return selesp('EB');">
			        <font size="1">Estudios&nbsp;B&aacute;sicos</font></button>
			<button name="B9" style="width: 95px; height: 22px; background:
xBOT1
;

    print $bcolor{'IE'}."\" value=\"IE\"";
    print <<<xBOT2
			        onclick="return selesp('IE');">
					<font size="1">El&eacute;ctrica</font></button>
			<button name="B10" style="width: 95px; height: 22px; background:
 
xBOT2
;
    print $bcolor{'EL'}."\" value=\"EL\"";
    print <<<xBOT3
			        onclick="return selesp('EL');">
					<font size="1">Electr&oacute;nica</font></button>
			<button name="B11" style="width: 95px; height: 22px; background:
xBOT3
; 
    print $bcolor{'IM'}."\" value=\"IM\"";
    print <<<xBOT4
			        onclick="return selesp('IM');">
					<font size="1">Mec&aacute;nica</font></button>
			<button name="B12" style="width: 95px; height: 22px; background:
 
xBOT4
;
    print $bcolor{'MT'}."\" value=\"MT\"";
    print <<<xBOT5
			        onclick="return selesp('MT');">
					<font size="1">Metal&uacute;rgica</font></button>
			<button name="B13" style="width: 95px; height: 22px; background:
xBOT5
; 
    print $bcolor{'II'}."\" value=\"II\"";
    print <<<xBOT6
			        onclick="return selesp('II');">
					<font size="1">Industrial</font></button>
			<button name="B14" style="width: 95px; height: 22px; background:
xBOT6;
;
    print $bcolor{'IQ'}."\" value=\"IQ\"";
    print <<<xBOT7
			        onclick="return selesp('IQ');">
					<font size="1">Qu&iacute;mica</font></button>
			<button name="B15" style="width: 93px; height: 22px; background:
xBOT7
;  
    print $bcolor{'CA'}."\" value=\"CA\"";
    print <<<xBOT8
			        onclick="return selesp('CA');">
					<font size="1">N. Carora</font></button>
			</p>
		</td>
	</tr>
xBOT8
;  
}

    function imprime_tabla($h,$data){

    $rows = count($data);
    $cols = count($h);
    print "<table align=\"center\" border=\"0\" id=\"data\" cellspacing=\"2\" cellpadding=\"1\">";
    print "<tr>\n";
    foreach($h as $hc){
    print "<td class=\"cielo\"><font class=\"negro\">".$hc."</font></td>\n";
}
    print "</tr>\n";
    print "<tr>\n";
    $listc=false;
    foreach($data as $dr){
        if ($listc) $estilo="blanco"; else $estilo="gris";
		if($dr[4] == '0') {
			$estilo="rojo";
			$dr[4] = "SIN CUPO";
		}
        foreach($dr as $dc){
            if(is_null($dc)) $dc="&nbsp;"; 
            print "<td nowrap class=\"".$estilo."\"><font class=\"negro\">".$dc."</font></td>\n";
}
        $listc =!$listc;
        print "</tr>\n";
}
    print "</table>\n";
}


function imprime_re() {
    global $esp, $lapsoProceso;

$dep = array("EB"=> "Estudios B&aacute;sicos",
	     "IE"=> "Ingenier&iacute;a El&eacute;ctrica",
	     "EL"=> "Ingenier&iacute;a Electr&oacute;nica",
	     "IM"=> "Ingenier&iacute;a Mec&aacute;nica",
	     "MT"=> "Ingenier&iacute;a Metal&uacute;rgica",
	     "II"=> "Ingenier&iacute;a Industrial",
	     "IQ"=> "Ingenier&iacute;a Qu&iacute;mica",
	     "CA"=> "N&uacute;cleo Carora");
print "<tr>
		<td width=\"750\" height=\"31\"><b><font face=\"Verdana\" size=\"2\" 
            color=\"#0000FF\"><center>Resultados de las Inscripciones para {$dep[$esp]}</center></font></b>
		</td>
	   </tr>";
	if ($esp == "CA") {
		$sede = "CARORA";
		$esp = "";
	}
	else $sede = "BQTO";
	//echo $sede;
    $oTest = new ODBC_Conn($sede,"c","c");
    $sSQL  = "SELECT A.c_asigna, asignatura, A.seccion, INSCRITOS=COUNT(exp_e), ";
	$sSQL .= "CUPOS_LIBRES=C.tot_cup - COUNT(exp_e) FROM dace006 A, tblaca008 B, ";
	$sSQL .= "tblaca004 C WHERE A.c_asigna = B.c_asigna ";
	$sSQL .= "AND A.c_asigna = C.c_asigna AND A.lapso = '$lapsoProceso' ";
	$sSQL .= "AND A.lapso=C.lapso AND A.seccion=C.seccion AND A.status in('7','9') ";
    $sSQL .= "AND A.c_asigna LIKE('".$esp."%') GROUP BY A.seccion, A.c_asigna, ";
	$sSQL .= "C.inscritos, C.tot_cup, asignatura ORDER BY 1 asc"; 
    //muestrame($sSQL);    
    if($oTest->ExecSQL($sSQL)) {
       // print "Se encontraron $oTest->filas filas </td>";
        // print_r($oTest->rXML);
        // print_r($oTest->result_h);
        print "<tr><td>";
        $oTest->result_h[0]="C&Oacute;DIGO";
        $oTest->result_h[1]="ASIGNATURAS";
        $oTest->result_h[2]="SECCI&Oacute;N";
        imprime_tabla($oTest->result_h, $oTest->result);
         print "</td></tr>";       
    }   
    else {
        print "<pre>";
        print_r($oTest->status);
        print "</pre></td></tr>";
    }
   
    
}             

function imprime_f() {
    //echo "FINAL";
	print <<<xTAIL
	<tr>
		<td width="750">
  			<form method="POST" name="f_espec"
			action="resultados.php">
			<input type="hidden" name="espec" value="x">
			</form>
		</td>
	</tr>
</table>
</body>
</html>
xTAIL
;
}
