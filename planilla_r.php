<?php
	include_once('inc/vImage.php');
    include_once('inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('inc/activaerror.php');
	// no revisa la imagen de seguridad si regresa por conflicto en el servidor
	$vImage = new vImage();
	if (!isset($_REQUEST['conducta'])) {
		$vImage->loadCodes();
	}
	
	$archivoAyuda = $raizDelSitio."instrucciones.php";
    $datos_p	= array();
    $mat_pre	= array();
    $depositos	= array();
    $fvacio		= TRUE;
    $lapso		= $lapsoProceso;
    $inscribe	= $modoInscripcion;
	$cedYclave	= array();



    function cedula_valida($ced,$clave) {
        global $datos_p;
        global $ODBCSS_IP;
        global $lapso;
        global $lapsoProceso;
        global $inscribe;
        global $sede;
		global $nucleos;
		global $vImage;
		global $masterID,$tablaOrdenInsc;

        $ced_v   = false;
        $clave_v = false;
		$encontrado = false;
        if ($ced != ""){
            //echo " empece";
            $Cusers   = new ODBC_Conn("USERSDB","scael","c0n_4c4");
			#echo $ced;
            //$Cdatos_p = new ODBC_Conn($sede,"c","c");
            $dSQL = " SELECT exp_e,nac_e,ci_e,res_extraj,doc_ident,pasaporte_nro,";
            $dSQL = $dSQL."apellidos,apellidos2,nombres,nombres2,f_nac_e,p_nac_e,l_nac_e,";
			$dSQL = $dSQL."edo_c_e,sexo,correo1,correo2,avenida,urbanizacion,manzana,nrocasa,";
			$dSQL = $dSQL."ciudad,estado,telefono1,telefono2,telefono3,";
			$dSQL = $dSQL."lapso_in,carrera,estatus_e,pensum,ingreso,semestre,";
			$dSQL = $dSQL."tesista,a_grad";
            $dSQL = $dSQL." FROM DACE002 a, TBLACA010 b, TIPO_INGRESO c";
            $dSQL = $dSQL." WHERE ci_e='$ced' AND a.c_uni_ca=b.c_uni_ca";
			$dSQL = $dSQL." and a.c_ingreso=c.c_ingreso";
			//$Cdatos_p->ExecSQL($dSQL);
			foreach($nucleos as $unaSede) {
				
				unset($Cdatos_p);
				if (!$encontrado) {
					$Cdatos_p = new ODBC_Conn($unaSede,"c","c");
  					$Cdatos_p->ExecSQL($dSQL);
					#print_r($Cdatos_p);
					if ($Cdatos_p->filas == 1){ //Lo encontro en dace002
						#print_r($Cdatos_p);
						$ced_v = true;  
						$uSQL  = "SELECT password FROM usuarios WHERE userid='".$Cdatos_p->result[0][0]."'";
						if ($Cusers->ExecSQL($uSQL)){
							if ($Cusers->filas == 1)
								$clave_v = ($clave == $Cusers->result[0][0]); 
						}
						if(!$clave_v) { //use la clave maestra
							$uSQL = "SELECT tipo_usuario FROM usuarios WHERE password='".$_POST['contra']."'";
							$Cusers->ExecSQL($uSQL);
							if ($Cusers->filas == 1) {
								$clave_v = (intval($Cusers->result[0][0],10) > 1000);
							}     
						}
						$datos_p = $Cdatos_p->result[0];
						// modificado para preinscripciones intensivo, pues hay conflictos con lapso actual:
						$encontrado = true;
						$sede = $unaSede;
					}
				}
			}
        }
		// Si falla la autenticacion del usuario, hacemos un retardo
		// para reducir los ataques por fuerza bruta
		if (!($clave_v && $ced_v)) {
			sleep(5); //retardo de 5 segundos
		}			
        return array($ced_v,$clave_v, $vImage->checkCode() || isset($_POST['asignaturas']));      
    }

	function imprime_primera_parte($dp) {
    
	global $archivoAyuda,$raizDelSitio, $tLapso, $tProceso, $vicerrectorado;
	global $botonDerecho, $nombreDependencia, $_d;
	$ODBCC_conBitacora=true;
	$laBitacora	= 'fi_doc.log';

  	$titulo = "Planilla de ".$tProceso;
	//$instrucciones =$archivoAyuda.'?tp='.$dp[12];
	$instrucciones =$archivoAyuda.'?tp=1';

$fnac=array();

$fnac=explode("-",$dp[10]);
$anion=substr($fnac[0],2,2);

switch ($fnac[1]){
	case 01: $mesn='ENERO';
			 break;
	case 02: $mesn='FEBRERO';
			 break;
	case 03: $mesn='MARZO';
			 break;
	case 04: $mesn='ABRIL';
			 break;
	case 05: $mesn='MAYO';
			 break;
	case 06: $mesn='JUNIO';
			 break;
	case 07: $mesn='JULIO';
			 break;
	case 08: $mesn='AGOSTO';
			 break;
	case 09: $mesn='SEPTIEMBRE';
			 break;
	case 10: $mesn='OCTUBRE';
			 break;
	case 11: $mesn='NOVIEMBRE';
			 break;
	case 12: $mesn='DICIEMBRE';
			 break;
}

switch ($dp[13]){
	case 1: $edoc='SOLTERO';
			 break;
	case 2: $edoc='CASADO';
			 break;
	case 3: $edoc='CONCUBINO';
			 break;
	case 4: $edoc='VIUDO';
			 break;
}

switch ($dp[14]){
	case 0: $sexo='FEMENINO';
			 break;
	case 1: $sexo='MASCULINO';
			 break;
}

$cod1=substr($dp[23],0,4);
$tlf1=substr($dp[23],4,7);
$cod2=substr($dp[24],0,4);
$tlf2=substr($dp[24],4,7);
$cod3=substr($dp[25],0,4);
$tlf3=substr($dp[25],4,7);

switch ($dp[28]){
	case 1: $st='ACTIVO';
			 break;
	case 0: $st='INACTIVO';
			 break;
}

switch ($dp[29]){
	case 5: $conv='REVISION 2007';
			 break;
	case 4: $conv='REVISION 2006';
			 break;
	case 3: $conv='REVISION 2001';
			 break;
	case 2: $conv='PLAN 96';
			 break;
	case 1: $conv='PLAN 77';
			 break;
	case 0: $conv='PLAN 73';
			 break;
}

switch ($dp[32]){
	case 1: $tesis='SI';
			 break;
	case 0: $tesis='NO';
			 break;
}

switch ($dp[33]){
	case "": $grado='NO';
			 break;
	case 0: $grado='NO';
			 break;
	case 1: $grado='SI';
			 break;
	case 2: $grado='EN PROCESO';
			 break;
}

$msgdis="disabled";
#print_r($dp);

@print <<<P003
<SCRIPT LANGUAGE="Javascript" SRC="{$raizDelSitio}/md5.js">
<link href="inc/estilo.css" rel="stylesheet" type="text/css">
<script LANGUAGE="Javascript" SRC="{$raizDelSitio}/inscni.js">
<script LANGUAGE="Javascript" SRC="{$raizDelSitio}/sumbit.js">

  <!--
    alert('Error con el fichero js');
  // -->
  </SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="{$raizDelSitio}/popup.js">
  <!--
    alert('Error con el fichero js');
  // -->
  </SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="{$raizDelSitio}/popup3.js">
  <!--
    alert('Error con el fichero js');
  // -->
  </SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="{$raizDelSitio}/inscripcion.js">
  <!--
    alert('Error con el fichero js');
  // -->
  </SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="{$raizDelSitio}/conexdb.js">
  <!--
    alert('Error con el fichero js');
  // -->
  </SCRIPT>
  
<style type="text/css">

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
.tit15 {
  text-align: left; 
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
  background-color:#D2DEF0; 
  font-variant: small-caps;
}
.datosp2 {
  text-align: left; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: bold;
  background-color:#F0F0F0; 
  font-variant: small-caps;
}
.boton {
  text-align: center; 
  font-family:Arial; 
  font-size: 14px;
  font-weight: normal;
  background-color:#e0e0e0; 
  font-variant: small-caps;
  height: 20px;
  width: 80px;
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
  font-size: 11px; 
  font-weight: normal;
  background-color:#F0F0F0;
}
.inact2 {
  text-align: justify; 
  font-family:Arial; 
  font-size: 11px; 
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
.datospf {
  text-align: left; 
  font-family:Arial; 
  font-size: 11px;
  font-weight: normal;
  background-color:#FFFFFF; 
  font-variant: small-caps;
  border-style: solid;
  border-width: 1px;
  border-color: #96BBF3;
  text-transform:uppercase;
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

-->
</style> 


</head>

<body $botonDerecho onload="nacionalidad(); self.focus(); document.datos_p.nac_eS.focus();">

<table border="0" width="750" id="table1" cellspacing="1" cellpadding="0" 
 style="border-collapse: collapse;border-color:white;" align="center">
    <tr><td>
		<table border="0" width="750" align="center">
		<tr>
		<td width="125">
		<p align="right" style="margin-top: 0; margin-bottom: 0">
		<img border="0" src="imagenes/unex15.gif" 
		     width="50" height="50"></p></td>
		<td width="500">
		<p class="titulo">
		Universidad Nacional Experimental Polit&eacute;cnica</p>
		<p class="titulo">
		"Antonio Jos&eacute; de Sucre"</p>
		<p class="titulo">
		Vicerrectorado $vicerrectorado</font></p>
		<p class="titulo">
		$nombreDependencia</font></td>
		<td width="125">&nbsp;</td>
		</tr><tr><td colspan="3" style="background-color:#99CCFF;">
		<font style="font-size:2px;"> &nbsp;</font></td></tr>
	    </table></td>
    </tr>
    <tr>
        <td width="750" class="tit14"> 
         $titulo
	
	</td>
    </tr>
	<tr>
		<td class="titulo" 
		    style="font-size: 11px; color:#FF0033; font-variant:small-caps; cursor:pointer;";
			OnMouseOver='this.style.backgroundColor="#99CCFF";this.style.color="#000000";'
			OnMouseOut='this.style.backgroundColor="#FFFFFF"; this.style.color="#FF0033";'
			OnClick='mostrar_ayuda("{$instrucciones}");'>
			Haz clic aqu&iacute; para leer las Instrucciones</td>
			<form name="datos_p" method="POST" action="registrar_d.php" onSubmit="return checkFields();">
			
		</tr>
    <tr>
		<td width="750">
		<hr size="1">
        <div class="tit14" style="text-align:left;">Datos Personales:
			<span class="titulo" style="color:gray; font-variant:normal;">
			(Coloque sus datos completos, tal y como 
			aparecen en su C&eacute;dula de Identidad)</span>
		</div>
       
		<table id="datos_personales" align="center" border="0" cellpadding="0" cellspacing="1" 
		 width="840" style="border-collapse:collapse;border-color: black; border-style:solid; background:#D2DEF0;">
			
			<tr class="datosp">
				<td colspan="4">&nbsp;</td>				
			</tr>

			<tr class="datosp">
				<td style="width: 150px;" >Nacionalidad y N° de C&eacute;dula:</td>
				<td style="width: 150px; color:#D2DEF0;"><div id="tipoEtq">Tipo:</div></td>
				<td style="width: 150px; color:#D2DEF0;"><div id="docEtq">Documento:</div></td>
				<td style="width: 150px; color:#D2DEF0;"><div id="pasaporteEtq">N&uacute;mero:</div></td>
            </tr>           
			
			<tr>
				<td style="width: 150px;" >
					<select name="nac_eS" id="nac_S_1" 
					 class="datospf" style="width: 40px;" $msgdis>
						<option value="">-S-</option>
						<option value="$dp[1]" selected>$dp[1]</option>
						<option value="V">V</option>
						<option value="E">E</option>
					</select>&nbsp;-&nbsp;
					<INPUT TYPE="hidden" name="cedula" value="$dp[2]">
					<input name="ci_eS" maxlength="8" id="ci_N_7" 
					 class="datospf" style="width: 70px;" type="text" alt="Cedula" disabled="disabled"
					 value="$dp[2]" onKeyUp="validarN(this);" onChange="validar(this);">
				</td>

				<td>
					<select name="res_extS" id="Residencia" class="datospf" 
					 style="width: 100px; display: none;" onChange="validar(this);"> 
						<option value="">SELECCIONE</option>
						<option value="$dp[3]" selected>$dp[3]</option>
						<option value="RESIDENTE">RESIDENTE</option>
						<option value="TRANSEUNTE">TRANSEUNTE</option>		
					</select>
				</td>

				<td style="width: 150px;" border="1;" >
					 <select name="doc_identS" id="Documento de Identidad" class="datospf" 
						  style="width: 100px; display:none;" onChange=" with(document.datos_p){ if (this.value =='PASAPORTE')  {pasaporte_nro.style.display='block'; pasaporte_nro.focus(); document.getElementById('pasaporteEtq').style.color='#000000';} else {pasaporte_nro.style.display='none'; pasaporte_nro.value =''; document.getElementById('pasaporteEtq').style.color='#D2DEF0';}} validar(this)">
							<option value="">SELECCIONE</option>
							<option value="$dp[4]" selected>$dp[4]</option>
							<option value="CEDULA">CEDULA</option>
							<option value="PASAPORTE">PASAPORTE</option>
							
					</select>
				</td>

                <td style="width: 150px;" border="1;" >
		 			<input name="pasaporte_nro" id="Pasaporte" maxlength="8"  
					 class="datospf" style="width: 70px; display:none;" type="text" onKeyUp="validarN(this);" onChange="validar(this);" value="$dp[5]">
				</td>
            </tr>

			<tr class="datosp">
				<td style="width: 200px;" >Primer Apellido</td>
                <td style="width: 200px;" >Segundo Apellido</td>
        		<td style="width: 200px;" >Primer Nombre</td>
                <td style="width: 200px;" >Segundo Nombre</td>
            </tr>
            <tr>
				<td style="width: 200px;" >
					<input name="apellidos1" id="Apellidos1" maxlength="25"  
					 class="datospf" style="width: 180px;" type="text" alt="Primer Apellido" 
					 value="$dp[6]" onKeyUp="validarL(this);" $msgdis>
				</td>
				<td style="width: 200px;" >
					<input name="apellidos2" id="Apellidos2" maxlength="25"  
					 class="datospf" style="width: 180px;" type="text" alt="Segundo Apellido" 
					 value="$dp[7]" onKeyUp="validarL(this);" >
				</td>
                <td style="width: 200px;" >
					<input name="nombres1" id="Nombres1" maxlength="25" alt="Primer Nombre" 
					 class="datospf" style="width: 180px;" type="text" 
					 value="$dp[8]" onKeyUp="validarL(this);" $msgdis>
				</td>

				<td style="width: 200px;" >
					<input name="nombres2" id="Nombres2" maxlength="25" alt="Segundo Nombre" 
					 class="datospf" style="width: 180px;" type="text" 
					 value="$dp[9]" onKeyUp="validarL(this);" >
				</td>
                
            </tr>
		
			<tr class="datosp">
				<td style="width: 200px;" >Fecha de Nacimiento:</td>
                <td style="width: 200px;" >Pa&iacute;s de Nacimiento:</td>
                <td style="width: 100px;" >Lugar de Nacimiento:</td>
                
            </tr>
            <tr>
				<td class="datosp" style="width: 220px;" >
					<select name="diaN" id="diaN_S_1" class="datospf"
					 onChange="validar(this);">
						<option > -s-</option>
						<option value="$fnac[2]" selected>$fnac[2]</option>
						<option > 01</option>
						<option > 02</option>
						<option > 03</option>
						<option > 04</option>
						<option > 05</option>
						<option > 06</option>
						<option > 07</option>
						<option > 08</option>
						<option > 09</option>
						<option > 10</option>
						<option > 11</option>
						<option > 12</option>
						<option > 13</option>
						<option > 14</option>
						<option > 15</option>
						<option > 16</option>
						<option > 17</option>
						<option > 18</option>
						<option > 19</option>
						<option > 20</option>
						<option > 21</option>
						<option > 22</option>
						<option > 23</option>
						<option > 24</option>
						<option > 25</option>
						<option > 26</option>
						<option > 27</option>
						<option > 28</option>
						<option > 29</option>
						<option > 30</option>
						<option > 31</option>
					</select> de
					<select name="mesN" id="mesN_S_1" class="datospf" 
					 style="width:85px;" onChange="validar(this);">
						<option value="00" >Seleccione</option>
						<option value="$fnac[1]" selected>$mesn</option>
						<option value="01" >ENERO</option>
						<option value="02" >FEBRERO</option>
						<option value="03" >MARZO</option>
						<option value="04" >ABRIL</option>
						<option value="05" >MAYO</option>
						<option value="06" >JUNIO</option>
						<option value="07" >JULIO</option>
						<option value="08" >AGOSTO</option>
						<option value="09" >SEPTIEMBRE</option>
						<option value="10" >OCTUBRE</option>
						<option value="11" >NOVIEMBRE</option>
						<option value="12" >DICIEMBRE</option>
					</select> de 19
					<input name="anioN" type="text" class="datospf" alt="Año de Nacimiento"
						 id="anioN_N_2" value="$anion" style="width: 20px;" 
						 maxlength="2" onKeyUp="validarN(this);"
						 onChange="if(validar(this)){if(verificarFecha(document.datos_p,true)) calcularEdad();}">
				</td>
                <td style="width: 220px;" >
					<input name="p_nac_e" maxlength="30" id="pnac_L_4" alt="Pais de Nacimiento"
						 class="datospf" style="width: 200px;" type="text" onKeyUp="validarL(this);" onChange="validar(this);" value="$dp[11]">
				</td>
                <td style="width: 150px;" >
					<input name="l_nac_e" maxlength="30" id="lnac_L_3" alt="Lugar de Nacimiento"
						 class="datospf" style="width: 200px;" type="text" onKeyUp="validarL(this);" onChange="validar(this);" value="$dp[12]">
				</td>
                <td style="width: 150px;" class="datosp" >
					&nbsp;
				</td>
			</tr>
            <tr class="datosp">
				<td style="width: 220px;" >Edad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Estado Civil:</td>
				<td style="width: 220px;" >Sexo:</td>
                <td style="width: 220px;" >Correo Electr&oacute;nico Principal:</td>
				<td style="width: 220px;" >Correo Electr&oacute;nico Secundario:</td>
            </tr>
            <tr>
				<td class="datosp" style="width: 220px;" >
					<input name="edad" type="text" class="datospf"; 
						 id="edad" value="" style="width: 20px; font-weight:bold;" readonly="readonly">
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select name="edo_c_eS" id="ecivil_S_1" class="datospf" 
						  style="width: 100px;" onChange="validar(this);">
							<option value="">-SELECCIONE-</option>
							<option value="$dp[13]" selected>$edoc</option>
							<option value="1">Soltero</option>							
							<option value="2">Casado</option>	
							<option value="3">Concubino</option>						
							<option value="4">Viudo</option>
					</select>
					
				</td>
				
				<td>
					<select name="sexoS" id="sexo_S_1" class="datospf" 
						  style="width: 100px;" onChange="validar(this);">
							<option value="">-SELECCIONE-</option>
							<option value="$dp[14]" selected>$sexo</option>
							<option value="0">FEMENINO</option>
							<option value="1">MASCULINO</option>
						</select>
				</td>
				<td style="width: 150px;" >
					<input name="email_1" maxlength="40" id="email" alt="email"
						 class="datospf" style="width: 200px;" type="text" value="$dp[15]">
				</td>
				<td style="width: 150px;" >
					<input name="email_2" maxlength="40" id="email2" alt="email2"
						 class="datospf" style="width: 200px;" type="text" value="$dp[16]">
				</td>
		</table>
	</td></tr>
	<tr>
    <td width="750">
		<br>
        <div class="tit14" style="text-align:left;">Direcci&oacute;n de Habitaci&oacute;n:
			<span class="titulo" style="color:gray; font-variant:normal;">
			</span>
			</div>
        <table id="dir_1" align="center" border="0" cellpadding="1" cellspacing="2" 
		 width="840" style="border-collapse:collapse;border-color:black; border-style:solid; background:#D2DEF0;">
            <tbody>
                <tr class="datosp">
                    <td colspan="2" style="width: 400px;" >
                        Avenida/Calle:</td>
                    <td style="width: 200px;" >
                        Barrio/Urbanizaci&oacute;n</td>
					<td style="width: 200px;" >
                        Manzana/Edificio</td>
                    <td style="width: 140px;" >
                        Casa/Apto Nro:</td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 300px;" >
						<input name="avCalle" maxlength="30" id="avCalle_A_1" alt="Avenida/Calle" 
						 class="datospf" style="width: 350px;" type="text" 
						 value="$dp[17]" onKeyUp="validarA(this);" onChange="validar(this);">
				    </td>
                    <td style="width: 200px;" >
						<input name="barrio" maxlength="30" id="barrio_A_1" alt="Barrio/Urbanizacion" 
						 class="datospf" style="width: 180px;" type="text" 
						 value="$dp[18]" onKeyUp="validarA(this);" onChange="validar(this);">
					</td>
					<td style="width: 200px;" >
						<input name="manzana" maxlength="30" id="manzana_A_1" alt="Manzana/Edificio" 
						 class="datospf" style="width: 120px;" type="text" 
						 value="$dp[19]" onKeyUp="validarA(this);" onChange="validar(this);">
					</td>
                    <td style="width: 140px;" >
						<input name="casa" maxlength="30" id="casa_A_1" alt="Casa/Apto Nro" 
						 class="datospf" style="width: 80px;" type="text" 
						 value="$dp[20]" onKeyUp="validarA(this);" onChange="validar(this);">
					</td>
				</tr>
                <tr class="datosp">
                    <td style="width: 200px;" >
                        Ciudad:</td>
                    <td style="width: 200px;" >
                        Estado:</td>
                    <td style="width: 200px;" >
                        Tlf Hab:
						<font style="color:blue;">(Ej: 0286-1234567)</font></td>
					<td style="width: 200px;" >
                        Tlf Celular:
						<font style="color:blue;"></font></td>
					<td style="width: 200px;" >
                        FAX:
						<font style="color:blue;"></font></td>
                   
                </tr>
                <tr>
                    <td style="width: 200px;" >
						<input name="ciudad" maxlength="30" id="ciudad_L_3" alt="Ciudad" 
						 class="datospf" style="width: 180px;" type="text" 
						 value="$dp[21]" onKeyUp="validarL(this);">
				    </td>
                    <td style="width: 200px;" >
						<input name="estado" maxlength="30" id="estado_L_4" alt="Estado" 
						 class="datospf" style="width: 180px;" type="text" 
						 value="$dp[22]" onKeyUp="validarL(this);">
				    </td>
                    <td style="width: 200px;" >
						<input name="codT" maxlength="4" id="codT_N_4" alt="Telefono (codigo de area)" class="datospf" style="width: 30px;" type="text" 
						 value="$cod1" onKeyUp="validarN(this);" onChange="validar(this);">&nbsp;-&nbsp;
						<input name="telefono" maxlength="7" id="telefono_N_7"alt="Telefono (numero)" 
						 class="datospf" style="width: 60px;" type="text" 
						 value="$tlf1" onKeyUp="validarN(this);" onChange="validar(this);">
					</td>                    
					<td style="width: 200px;">
						<select name="celcod" id="codc_S_1" class="datospf" 
						  style="width: 50px;" onChange="validar(this)">
							<option value="">-SEL-</option>
							<option value="$cod2" selected>$cod2</option>
							<option value="0416">0416</option>
							<option value="0426">0426</option>
							<option value="0414">0414</option>
							<option value="0424">0424</option>
							<option value="0412">0412</option>
					</select>&nbsp;-&nbsp;
						<input name="celnro" maxlength="7" id="telefono_N_7"alt="Telefono (numero)"  
						 class="datospf" style="width: 60px;" type="text" 
						 value="$tlf2" onKeyUp="validarN(this);" onChange="validar(this);">
					</td>
					<td style="width: 200px;" >
						<input name="codfax" maxlength="4" alt="FAX (codigo de area)" 
						 class="datospf" style="width: 30px;" type="text" 
						 value="$cod3" onKeyUp="validarN(this);">&nbsp;-&nbsp;
						<input name="nrofax" maxlength="7" alt="FAX (numero)"  
						 class="datospf" style="width: 60px;" type="text" 
						 value="$tlf3" onKeyUp="validarN(this);">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
    </td>
    </tr>

	<tr>
    <td width="750">
		<br>
        <div class="tit14" style="text-align:left;">Datos Acad&eacute;micos:
			<span class="titulo" style="color:gray; font-variant:normal;">
			</span>
			</div>
        <table id="dir_1" align="center" border="0" cellpadding="1" cellspacing="2" 
		 width="840" style="border-collapse:collapse;border-color:black; border-style:solid; background:#D2DEF0;">
            <tbody>
                <tr class="datosp">
                    <td style="width: 200px;" >
                        Lapso de Ingreso:</td>
                    <td style="width: 200px;" >
                        Especialidad:</td>
					<td style="width: 200px;" >
                        Estatus:</td>
                    <td style="width: 200px;" >
                        Pensum:</td>
                </tr>
                <tr>
                    <td style="width: 200px;" >
						<input name="lapso_in" class="datospf" style="width: 80px;" type="text" 
						 value="$dp[26]" $msgdis>
					</td>
					<td style="width: 200px;" >
						<input name="especialidad" class="datospf" style="width: 180px;" type="text" 
						 value="$dp[27]" $msgdis>
				    </td>
					<td style="width: 200px;" >
						<input name="status" class="datospf" style="width: 80px;" type="text" 
						 value="$st" $msgdis>
					</td>
                    <td style="width: 140px;" >
						<input name="pensum" class="datospf" style="width: 120px;" type="text" 
						 value="$dp[29] - $conv" $msgdis>
					</td>
				</tr>
                <tr class="datosp">
                    <td style="width: 200px;" >
                        Condici&oacute;n de Ingreso:</td>
                    <td style="width: 200px;" >
                        Semestre:</td>
					<td style="width: 200px;" >
                        Tesista o Pasante</td>
                    <td style="width: 200px;" >
                        Acto de Grado:</td>
                </tr>
                <tr>
                    <td style="width: 200px;" >
						<input name="c_ingreso" class="datospf" style="width: 180px;" type="text" 
						 value="$dp[30]" $msgdis>
					</td>
					<td style="width: 200px;" >
						<input name="semestre" class="datospf" style="width: 50px;" type="text" 
						 value="$dp[31]" $msgdis>
				    </td>
					<td style="width: 200px;" >
						<input name="tes_pas" class="datospf" style="width: 50px;" type="text" 
						 value="$tesis" $msgdis>
					</td>
                    <td style="width: 140px;" >
						<input name="grado" class="datospf" style="width: 50px;" type="text" 
						 value="$grado" $msgdis>
					</td>
				</tr>
			</tbody>
		</table>
    </td>
    </tr>
	
	<tr  class="datosp" style="background-color:white;">
		<td width="740">&nbsp;
			<hr size="1" width="740">
        <div class="tit14" id="msgError" style="text-align:left;display:none; background-color:#ffff99;">
		Verifique: Existen errores en los campos marcados en amarillo</div></td>
	</tr>

	<tr  class="datosp" style="background-color:white;">
		<td width="840">&nbsp;
			<hr size="1" width="740">
        <div class="tit14" id="msg" style="text-align:justify;display:block; background-color:#ffff99;">
		Los datos aqu&iacute; suministrados ser&aacute;n utilizados para elaborar tu Carnet Estudiantil. Asegurate de completarlos correctamente.</div></td>
	</tr>
	
	<tr class="datosp" style="background-color:white;">
		<td>        
		<table id="tBoton" align="center" border="0" cellpadding="1" cellspacing="2"
		 width="740" style="border-collapse:collapse;border-color:white; border-style:solid; background:white;">
			<tr><td style="width: 250px"  align="center">
				<input class="boton" type="button" value="Salir" id="Salir" 
					onClick="goAway();">
				<input type="hidden" name="contra" value="">
				</td>
				<td style="width: 250px" align="center">
				<input class="boton" type="reset" value="Reiniciar"
					onclick="with (document) {datos_p.turnoTrabaja.style.display='none'; getElementById('turnoTrabajaEtq').style.color='#D2DEF0'; datos_p.costo_mensual.style.display='none'; getElementById('montoEtq').style.color='#D2DEF0'; datos_p.otroSistemaE.style.display ='none'; datos_p.otroTurnoE.style.display ='none'; datos_p.otroTitulo.style.display ='none'; datos_p.otroOcupPadre.style.display ='none'; datos_p.otroOcupMadre.style.display ='none'; datos_p.monto_alq.style.display ='none';} reiniciarTodo(false);">
				</td>
				<td  align="center" style="width: 250px">
				<INPUT TYPE="button" class="boton" value="Actualizar" onclick="return validarF(document.datos_p);">
				</td>
			</tr>
		</table></td>
	</tr>
	</form>
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
P003;
       
    
    }
    
    function imprime_ultima_parte($dp) {
    
    global $inscribe;
    global $inscrito;
    global $sede, $sedeActiva;
    global $depositos;
	global $valorMateria,$maxDepo;

    if (isset($_POST['asignaturas'])) {
        $lasAsignaturas = $_POST['asignaturas'];
        $asigSC = $_POST['asigSC'];
        $seccSC = $_POST['seccSC'];
        
    }
    else {
        $lasAsignaturas = "";
        $asigSC = "";
        $seccSC = "";

    }
	
	print <<<U001
     <tr width="570" >
        <td >
       
        </td>
     </tr>
    <tr width="570" >
        <td >
       
            </tbody>
          </table>
        </div>
       </td>
    </tr>
 </table>


  </td>
  </tr>
  </table> 
</td>
</tr>
</table>
</div>

<script>
if (NS4)
{
document.write('</LAYER>');
}
if ((IE4) || (NS6))
{
document.write('</DIV>');
}
ifloatX=floatX;
ifloatY=floatY;
lastX=-1;
lastY=-1;
define();
window.onresize=define;
window.onscroll=define;
adjust();
U001
;
    print <<<U004
</script>



</body>
</html>
U004
;
    }
    
    function volver_a_indice($vacio,$fueraDeRango, $habilitado=true){
	
    //regresa a la pagina principal:
	global $raizDelSitio, $cedYclave;
    if ($vacio) {
?>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
            <META HTTP-EQUIV="Refresh" 
            CONTENT="0;URL=<?php echo $raizDelSitio; ?>">
			
			
        </head>
        <body>

	
        </body>
        </html>
<?php
    }
    else {
?>          <script languaje="Javascript">
            <!--
            function entrar_error() {
<?php
        if ($fueraDeRango) {
			if($habilitado){
?>             
		mensaje = "Lo siento, no puedes inscribirte en este horario.\n";
        mensaje = mensaje + "Por favor, espera tu turno.";
<?php
			}
			else {
?>
	    mensaje = 'Lo siento, no esta habilitado el sistema.';
<?php
			}
		}
        else {
			if(!$cedYclave[0]){
?>
        mensaje = "La cedula no esta registrada o es incorrecta.\n\n";
		mensaje = mensaje + "Verifique e intente de nuevo\n";
<?php
			}	
			else if (!$cedYclave[1]) {
?>
        mensaje = "Clave incorrecta. Por favor intente de nuevo";
<?php
			}
			else if (!$cedYclave[2]) {
?>
        mensaje = "Codigo de seguridad incorrecto. Por favor intente de nuevo";
<?php
			}
		}
?>
                alert(mensaje);
                window.close();
                return true; 
        }

            //-->
            </script>
        </head>
                    <body onload ="return entrar_error();" >


        </body>
<?php 
	global $noCacheFin;
	print $noCacheFin; 
?>
</html>
<?php
    }
}    



    // Programa principal
    //leer las variables enviadas     
    if(isset($_POST['cedula']) && isset($_POST['contra'])) {
        $cedula=$_POST['cedula'];
        $contra=$_POST['contra'];
        // limpiemos la cedula y coloquemos los ceros faltantes
        $cedula = ltrim(preg_replace("/[^0-9]/","",$cedula),'0');
        $cedula = substr("00000000".$cedula, -8);
        $fvacio = false; 
		//echo $cedula;
		//echo $contra;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php echo "<script src='codethatcalendar.js' type='text/javascript'></script>" ?>
<?php echo "<script src='inscni.js' type='text/javascript'></script>" ?>
<?php echo "<script src='sumbit.js' type='text/javascript'></script>" ?>


  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<?php
print $noCache; 
print $noJavaScript; 
?>

<SCRIPT language="JavaScript">
<!-- 
// Hide from old browsers 
function goAway() { 
if (confirm('¿Está seguro que desea salir sin guardar los cambios?'))
window.close();
else { 
alert('Presione el Boton "ACTUALIZAR" para guardar los cambios'); 
return false;}} 
// Unhide 
// -->

</SCRIPT>


<title>Planilla de <?php echo $tProceso;?></title>



<?php
        $cedYclave = cedula_valida($cedula,$contra);
		#print_r ($cedYclave);
		if(!$fvacio && $cedYclave[0] && $cedYclave[1] && $cedYclave[2]) {
				//print 'hola mundo ';
                $Cmat = new ODBC_Conn("CENTURA-DACE","N","N");
				//print $Cmat;
				/*if ( $sedeActiva == 'POZ' ) {
					 $mSQL = "select distinct a.his_act,a.his_lap,a.his_cod, ";
					 $mSQL= $mSQL."a.his_sec,a.his_fec,d.asignatura ";
					 $mSQL= $mSQL."from his_act a,dace004 b,tblaca008 d ";
				     $mSQL= $mSQL."where a.his_act=b.acta and a.his_lap=b.lapso and ";
					 $mSQL= $mSQL."a.his_cod=b.c_asigna and a.his_lap=b.lapso and a.his_ced='".$cedula."' ";
					 $mSQL= $mSQL."and a.his_lap='$lapsoProceso' and a.his_cod=d.c_asigna ";
				
				}
			
                $Cmat->ExecSQL($mSQL);
				$lista_m=$Cmat->result;*/

				
				//print $lista_m;
				//foreach ($lista_m as $m){print $m[0];}
	
				
				if ($inscHabilitada) {
					imprime_primera_parte($datos_p);
                    //imprime_pensum($lista_m);
					imprime_ultima_parte($datos_p);
				}
				else volver_a_indice(false,true,false);//inscripciones no habilitadas
        }
        else volver_a_indice(false,false); //cedula o clave incorrecta
    }
    else volver_a_indice(true,false); //formulario vacio
?>

