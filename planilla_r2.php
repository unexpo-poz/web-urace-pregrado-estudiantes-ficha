<?
// Modificado el 28/02/2007 para agregar restricciones de semestre requeridas para Pto Ordaz
//
// LATD -- 
	include_once("inc/vImage.php");
    include_once('../inc/odbcss_c.php');
	include_once ('inc/config.php');
	include_once ('../inc/activaerror.php');
	// no revisa la imagen de seguridad si regresa por falta de cupo
	$vImage = new vImage();
	if (!isset($_POST['asignaturas'])) {
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
            $Cusers   = new ODBC_Conn("USERDOC","scael","c0n_4c4");
			$dSQL     = " SELECT ci, nombre, apellido";
            $dSQL     = $dSQL." FROM TBLACA007 ";
            $dSQL     = $dSQL." WHERE ci='$ced' " ;
            //  print " empece";
			foreach($nucleos as $unaSede) {
				
				unset($Cdatos_p);
				if (!$encontrado) {
					$Cdatos_p = new ODBC_Conn($unaSede,"c","c");
  					$Cdatos_p->ExecSQL($dSQL);  
					if ($Cdatos_p->filas == 1){ //Lo encontro en orden_inscripcion
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
						$datos_p[11] = $lapsoProceso;
						$lapso = $datos_p[11];
						$encontrado = true;
						$sede = $unaSede;
						//echo $Cdatos_p;
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

    print "<SCRIPT LANGUAGE=\"Javascript\">\n<!--\n";
    print "chequeo = false;\n";
    print "ced=\"".$dp[0]."\";\n";
    print "contra=\"".$_POST['contra']."\";\n";
    print "exp_e=\"".$dp[1]."\";\n";
    print "nombres=\"".$dp[2]."\";\n";
    print "apellidos=\"".preg_replace("/\"/","'",$dp[0])."\";\n";
    print "carrera=\"".$dp[0]."\";\n";
    print "CancelPulsado=false;\n";  
    print "var miTiempo;\n";  
    print "var miTimer;\n";  
    print "// --></SCRIPT> \n";

	$titulo = $tProceso ." " . $tLapso;
	//$instrucciones =$archivoAyuda.'?tp='.$dp[12];
	$instrucciones =$archivoAyuda.'?tp=1';
    print <<<P001
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

<body $botonDerecho onload="reiniciarTodo(); self.focus(); document.datos_p.nac_eS.focus();">

<table border="0" width="750" id="table1" cellspacing="1" cellpadding="0" 
 style="border-collapse: collapse;border-color:white;">
    <tr><td>
		<table border="0" width="750">
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
         $titulo </td>
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
		 width="740" style="border-collapse:collapse;border-color: white; border-style:solid; background:#D2DEF0;">
			
			<tr class="datosp">
				<td>&nbsp;</td>				
			</tr>

			<tr class="datosp">
				<td style="width: 150px;" >C&eacute;dula:<font style="color: blue;"> (Ej: V-12345678)</font></td>
				<td style="width: 150px; color:#D2DEF0;"><div id="tipoEtq">Tipo:</div></td>
				<td style="width: 150px; color:#D2DEF0;"><div id="docEtq">Documento:</div></td>
				<td style="width: 150px; color:#D2DEF0;"><div id="pasaporteEtq">N&uacute;mero:</div></td>
            </tr>           

			<tr>
				<td style="width: 150px;" >
					<select name="nac_eS" id="nac_S_1" 
					 class="datospf" style="width: 40px;" onChange="with(document.datos_p){ if (this.value =='E')  {res_extrajS.style.display='block'; res_extrajS.focus(); document.getElementById('tipoEtq').style.color='#000000';} else {res_extrajS.style.display='none'; res_extraj.value =''; document.getElementById('tipoEtq').style.color='#D2DEF0';}} { if (this.value =='E') {doc_identS.style.display='block'; doc_identS.focus(); document.getElementById('docEtq').style.color='#000000';} else {doc_identS.style.display='none'; doc_ident.value =''; document.getElementById('docEtq').style.color='#D2DEF0';}}{ if (this.value =='V') {pasaporte_nro.style.display='none'; pasaporte_nro.value =''; document.getElementById('pasaporteEtq').style.color='#D2DEF0';}}validar(this);">
						<option value="">-s-</option>
						<option value="V">V</option>
						<option value="E">E</option>
					</select>&nbsp;-&nbsp;
					<INPUT TYPE="hidden" name="cedula" value="$dp[0]">
					<input name="ci_eS" maxlength="8" id="ci_N_7" 
					 class="datospf" style="width: 70px;" type="text" alt="Cedula" disabled="disabled"
					 value="$dp[0]" onKeyUp="validarN(this);" onChange="validar(this);">
				</td>

				<td>
					<select name="res_extrajS" id="Residencia" class="datospf" 
					 style="width: 100px; display: none;" onChange="validar(this);"> 
						<option value="">-SELECCIONE-</option>
						<option value="RESIDENTE">RESIDENTE</option>
						<option value="TRANSEUNTE">TRANSEUNTE</option>		
					</select>
				</td>

				<td style="width: 150px;" border="1;" >
					 <select name="doc_identS" id="Documento de Identidad" class="datospf" 
						  style="width: 100px; display:none;" onChange=" with(document.datos_p){ if (this.value =='PASAPORTE')  {pasaporte_nro.style.display='block'; pasaporte_nro.focus(); document.getElementById('pasaporteEtq').style.color='#000000';} else {pasaporte_nro.style.display='none'; pasaporte_nro.value =''; document.getElementById('pasaporteEtq').style.color='#D2DEF0';}} validar(this)" required="1">
							<option value="">-SELECCIONE-</option>
							<option value="CEDULA">CEDULA</option>
							<option value="PASAPORTE">PASAPORTE</option>
							
					</select>
				</td>

                <td style="width: 150px;" border="1;" >
		 			<input name="pasaporte_nro" id="Pasaporte" maxlength="8"  
					 class="datospf" style="width: 70px; display:none;" type="text" onKeyUp="validarN(this);" onChange="validar(this);">
				</td>
            </tr>

			<tr class="datosp">
				<td style="width: 220px;" ><div>Apellidos Completos:</div></td>
                <td style="width: 220px;" >Nombres Completos:</td>
                <td style="width: 150px;" >Correo electr&oacute;nico:</td>
                <td style="width: 150px;" >&nbsp;</td>
            </tr>
            <tr>
				<td style="width: 220px;" >
					<input name="apellidos" id="Apellidos" maxlength="25"  
					 class="datospf" style="width: 200px;" type="text" alt="Apellidos" 
					 value="$dp[2]" onKeyUp="validarL(this);" required="1">
				</td>
                <td style="width: 220px;" >
					<input name="nombres" id="Nombres" maxlength="25" alt="Nombres" 
					 class="datospf" style="width: 200px;" type="text" 
					 value="$dp[1]" onKeyUp="validarL(this);" required="1">
				</td>

				<td style="width: 220px;" >
					<input name="correo_e" id="Correo Electr&oacute;nico" maxlength="30" class="datospf" style="width: 200px;" type="text">
				</td>
                
                <td style="width: 150px;" >
					&nbsp;
				</td>
            </tr>
		
			<tr class="datosp">
				<td style="width: 220px;" >Fecha de Nacimiento:</td>
                <td style="width: 220px;" >Pa&iacute;s de Nacimiento:</td>
                <td style="width: 150px;" >Lugar de Nacimiento:</td>
                <td style="width: 150px;" >&nbsp;</td>
            </tr>
            <tr>
				<td class="datosp" style="width: 220px;" >
					<input type="hidden" name="f_nac_e" value="{$_d['f_nac_e']}"> 
					<select name="diaN" id="diaN_S_1" class="datospf"
					 onChange="if(validar(this)) calcularEdad();">
						<option >-s-</option>
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
					 style="width:85px;" onChange="if (validar(this)) calcularEdad();">
						<option value="00" >seleccione</option>
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
						 id="anioN_N_2" value="" style="width: 15px;" 
						 maxlength="2" onKeyUp="validarN(this);"
						 onChange="if(validar(this)){if(verificarFecha(document.datos_p,true)) calcularEdad();}">
				</td>
                <td style="width: 220px;" >
					<input name="p_nac_e" maxlength="30" id="pnac_L_4" alt="Pais de Nacimiento"
						 class="datospf" style="width: 200px;" type="text" onKeyUp="validarL(this);" onChange="validar(this);">
				</td>
                <td style="width: 150px;" >
					<input name="l_nac_e" maxlength="30" id="lnac_L_3" alt="Lugar de Nacimiento"
						 class="datospf" style="width: 130px;" type="text" onKeyUp="validarL(this);" onChange="validar(this);">
				</td>
                <td style="width: 150px;" class="datosp" >
					&nbsp;

				</td>
			</tr>
            <tr class="datosp">
				<td style="width: 220px;" >Edad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Estado Civil:</td>
				<td style="width: 220px;" >Sexo:</td>
                <td style="width: 220px;" >Hijos:</td>
                <td style="width: 150px; color:#D2DEF0;" colspan="2"><div id="turnoTrabajaEtq">Indique Cuantos:</div></td>
                <td style="width: 150px;" >&nbsp;</td>
            </tr>
            <tr>
				<td class="datosp" style="width: 220px;" >
					<input name="edad" type="text" class="datospf"; 
						 id="edad" value="" style="width: 20px; font-weight:bold;" readonly="readonly">
						 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select name="edo_c_eS" id="ecivil_S_1" class="datospf" 
						  style="width: 100px;" onChange="validar(this);">
							<option value="">-SELECCIONE-</option>							
							<option value="SOLTERO">Soltero</option>							
							<option value="CASADO">Casado</option>							
							<option value="CONCUBINO">Concubino</option>						
							<option value="VIUDO">Viudo</option>
					</select>
					
				</td>
				
				<td>
					<select name="sexoS" id="sexo_S_1" class="datospf" 
						  style="width: 100px;" onChange="validar(this);">
							<option value="">-SELECCIONE-</option>
							<option value="FEMENINO">FEMENINO</option>
							<option value="MASCULINO">MASCULINO</option>
						</select>
				</td>

				<td style="width: 220px;" >
					<select name="hijosS" id="hijos_S_1" class="datospf" 
						  style="width: 100px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_hijos.style.display='block'; c_hijos.value ='';c_hijos.focus(); datos.style.display='block'; document.getElementById('turnoTrabajaEtq').style.color='#000000';} else {c_hijos.style.display='none'; c_hijos.value ='0'; datos.style.display='none'; document.getElementById('turnoTrabajaEtq').style.color='#D2DEF0';}} validar(this);">
							<option value="">-SELECCIONE-</option>
							<option value="NO">NO</option>
							<option value="SI">SI</option>
					</select>
				</td>
				
					<td style="width: 150px;" >
					
					<INPUT TYPE="text" NAME="c_hijos" id="hijos" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);">
					
					<INPUT TYPE="hidden" id="padre" value="$dp[0]">
					</td>
				<td>
					<INPUT TYPE="button" id="datos" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaA();">
				</td>
					
				<SCRIPT LANGUAGE="JavaScript">
				<!--
				function enviaA() {
				var a = document.getElementById("hijos").value;
				var b = document.getElementById("ci_N_7").value;
				window.open('hijos.php?hijos=' + a +'&padre=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=800,left=110, screenX=0,top=150,screenY=0');
				
				}

				//-->
				</SCRIPT>
			</tr>
			
			<tr class="datosp">
				<td>&nbsp;</td>				
			</tr>

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
		 width="740" style="border-collapse:collapse;border-color:white; border-style:solid; background:#D2DEF0;">
            <tbody>
                <tr class="datosp">
                    <td colspan="2" style="width: 400px;" >
                        Avenida/Calle:</td>
                    <td style="width: 200px;" >
                        Barrio/Urbanizaci&oacute;n/Edificio:</td>
                    <td style="width: 140px;" >
                        Casa/Apto Nro:</td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 400px;" >
						<input name="avCalle" maxlength="100" id="avCalle_A_6" alt="Avenida/Calle" 
						 class="datospf" style="width: 380px;" type="text" 
						 value="" onKeyUp="validarA(this);" onChange="validar(this);">
				    </td>
                    <td style="width: 200px;" >
						<input name="barrio" maxlength="60" id="barrio_A_3" alt="Barrio/Urbanizacion/Edificio" 
						 class="datospf" style="width: 180px;" type="text" 
						 value="" onKeyUp="validarA(this);" onChange="validar(this);">
                    <td style="width: 140px;" >
						<input name="casa" maxlength="25" id="casa_A_1" alt="Casa/Apto Nro" 
						 class="datospf" style="width: 120px;" type="text" 
						 value="" onKeyUp="validarA(this);" onChange="validar(this);">
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
                   
                </tr>
                <tr>
                    <td style="width: 200px;" >
						<input name="ciudad" maxlength="30" id="ciudad_L_3" alt="Ciudad" 
						 class="datospf" style="width: 180px;" type="text" 
						 value="" onKeyUp="validarL(this);">
				    </td>
                    <td style="width: 200px;" >
						<input name="estado" maxlength="30" id="estado_L_4" alt="Estado" 
						 class="datospf" style="width: 180px;" type="text" 
						 value="" onKeyUp="validarL(this);">
				    </td>
                    <td style="width: 200px;" >
						<input name="codT" maxlength="4" id="codT_N_4" alt="Telefono (codigo de area)" 
						 class="datospf" style="width: 30px;" type="text" 
						 value="" onKeyUp="validarN(this);" onChange="validar(this);">&nbsp;-&nbsp;
						<input name="telefono" maxlength="7" id="telefono_N_7"alt="Telefono (numero)"  
						 class="datospf" style="width: 60px;" type="text" 
						 value="" onKeyUp="validarN(this);" onChange="validar(this);">
                    
					<td style="width: 200px;" >
						<select name="celcod" id="codc_S_1" class="datospf" 
						  style="width: 50px;" onChange="validar(this)">
							<option value="">-SEL-</option>
							<option value="0416">0416</option>
							<option value="0426">0426</option>
							<option value="0414">0414</option>
							<option value="0424">0424</option>
							<option value="0412">0412</option>
					</select>&nbsp;-&nbsp;
						<input name="celnro" maxlength="7" id="telefono_N_7"alt="Telefono (numero)"  
						 class="datospf" style="width: 60px;" type="text" 
						 value="" onKeyUp="validarN(this);" onChange="validar(this);">
                    <td style="width: 140px;" >&nbsp;
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
    
	<tr>
    <td width="750">
		<br>
        <div class="tit14" style="text-align:left;">
		Perfil Actual:
		</div>
        <table align="center" border="0" cellpadding="1" cellspacing="2" 
		 width="740" style="border-collapse:collapse;border-color:white; border-style:solid; background:#D2DEF0;">
            <tbody>
                <tr class="datosp">
					<td style="width: 200px;">Ingreso a la Adm. Publica:</td>
					<td style="width: 200px;">Ingreso a la UNEXPO:</td>
					<td style="width: 200px;">Dpto. al que pertenece:</td>
					<td style="width: 200px;">Estatus:</td>
                </tr>
                <tr>
                    <td style="width: 85px;">
						<input name="ing_pub" class="datospf" style="width: 60px;" onClick="c1.popup('ing_pub');" readonly="readonly">&nbsp;&nbsp;<IMG SRC="Builder/img/cal.gif" WIDTH="16" HEIGHT="16" BORDER="0" onClick="c1.popup('ing_pub');">
				    </td>
					 <td style="width: 85px;">
						<input name="ing_unexpo" class="datospf" style="width: 60px;" onClick="c1.popup('ing_unexpo');" readonly="readonly">&nbsp;&nbsp;<IMG SRC="Builder/img/cal.gif" WIDTH="16" HEIGHT="16" BORDER="0" onClick="c1.popup('ing_unexpo');">
				    </td>
					<td>
						<select name="dpto" class="datospf" 
						  style="width: 95px;" onChange="validar(this)">
							<option value="">-SELECCIONE-</option>
							<option value="ELECTRICA">ELECTRICA</option>
							<option value="ELECTRONICA">ELECTRONICA</option>
							<option value="INDUSTRIAL">INDUSTRIAL</option>
							<option value="MECANICA">MECANICA</option>
							<option value="METALURGIA">METALURGIA</option>
						</select>
					</td>
					<td>
						<select name="estatus" class="datospf" 
						  style="width: 95px;" onChange="validar(this)">
							<option value="">-SELECCIONE-</option>
							<option value="ACTIVO">ACTIVO</option>
							<option value="JUBILADO">JUBILADO</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr class="datosp">
					<td style="width: 100px;">Clasificacion:</td>
					<td style="width: 100px;">Dedicacion:</td>
					<td style="width: 95px;" colspan="2">Asignaturas que dicta actualmente:</td>
				</tr>
				<tr>
					<td>
						<select name="clasif" id="clasif_S_1" class="datospf" 
						  style="width: 125px;" onChange="validar(this)">
							<option value="">-SELECCIONE-</option>
							<option value="INSTRUCTOR">INSTRUCTOR</option>
							<option value="ASISTENTE">ASISTENTE</option>
							<option value="AGREGADO">AGREGADO</option>
							<option value="ASOCIADO">ASOCIADO</option>
							<option value="TITULAR">TITULAR</option>
							<option value="BECARIO">BECARIO</option>
							<option value="AUXILIAR">AUXILIAR DOCENTE</option>
						</select>
					</td>
					<td>
						<select name="dedic" id="dedic_S_1" class="datospf" 
						  style="width: 145px;" onChange="validar(this)">
							<option value="">-SELECCIONE-</option>
							<option value="MT">MEDIO TIEMPO</option>
							<option value="TC">TIEMPO COMPLETO</option>
							<option value="TP">TIEMPO PARCIAL</option>
							<option value="DE">DEDICACION EXCLUSIVA</option>
						</select>
					</td>
					<td class="datosp" colspan="2">
						n&uacute;mero de secciones:&nbsp;
						<select name="seccS" id="mat_S_1" class="datospf" 
						  style="width: 50px;" onChange="with(document.datos_p){ if (this.value !='0'){enviaM();}} validar(this);">
							<option value="0">-SEL-</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
						<SCRIPT LANGUAGE="JavaScript">
						<!--
						function enviaM() {
							var a = document.getElementById("mat").value;
							var b = document.getElementById("ci_N_7").value;
							window.open('materias.php?materias=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
							
						}

						//-->
						</SCRIPT>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr class="datosp">
					<td style="width: 100px;">
						<div>Ejerce un Cargo de jefatura <BR>en la UNEXPO:</div></td>
					<td>
						<select name="jefaturaS" id="jefatura_S_1" class="datospf" 
							  style="width: 100px;" onChange="with(document.datos_p){ if (this.value =='SI')  {jefatura.style.display='block'; jefatura.focus(); document.getElementById('jefaturaEtq').style.color='#000000';} else {jefatura.style.display='none'; jefatura.value ='0'; document.getElementById('jefaturaEtq').style.color='#D2DEF0';}} validar(this);">
								<option value="">-SELECCIONE-</option>
								<option value="NO">NO</option>
								<option value="SI">SI</option>
						</select>
					</td>
					<td style="width: 190px; color:#D2DEF0;" colspan="2">
						<div id="jefaturaEtq">Indique tipo:<select name="jefatura" id="jefatura" class="datospf" 
							  style="width: 190px; display:none;"
								<option value="">&nbsp;&nbsp;- - - - - - SELECCIONE - - - - - -</option>
								<option value="JEFE SECC">JEFE DE SECCION</option>
								<option value="JEFE COORD">JEFE DE COORDINACION</option>
								<option value="JEFE DPTO">JEFE DE DEPARTAMENTO</option>
								<option value="JEFE UNID">JEFE DE UNIDAD</option>
								<option value="DIR INV POST">DIR. INVEST. Y POSTG.</option>
								<option value="DIR ADM">DIRECTOR ADMINISTRATIVO</option>
								<option value="DIR ACAD">DIRECTOR ACADEMICO</option>
								<option value="VICERRECTOR">VICERRECTOR REGIONAL</option>
								<option value="VICE ADM">VICERRECTOR ADMINISTRATIVO</option>
								<option value="VICE ACAD">VICERRECTOR ACADEMICO</option>
								<option value="SECRETARIO">SECRETARIO</option>
								<option value="RECTOR">RECTOR</option>
							</select></div>
					</td>
					
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr> 
				<tr class="datosp">
					<td style="width: 300px;">Ejerce o ha ejercido otros <BR>Cargos en la Adm. P&uacute;blica:</td>
					<td style="width: 150px;">
					<select name="otrocS" id="math_S_1" class="datospf" 
						  style="width: 100px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_otroc.style.display='block'; c_otroc.focus(); datosotroc.style.display='block'; document.getElementById('otrocEtq').style.color='#000000';} else {c_otroc.style.display='none'; c_otroc.value ='0'; datosotroc.style.display='none'; document.getElementById('otrocEtq').style.color='#D2DEF0';}} validar(this);">
							<option value="">-SELECCIONE-</option>
							<option value="NO">NO</option>
							<option value="SI">SI</option>
					</select>
					</td>
					<td style="width: 260px; color:#D2DEF0;" colspan="2">
						<div id="otrocEtq">Indique Cuantos:<INPUT TYPE="text" NAME="c_otroc" id="otroc" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);"><INPUT TYPE="button" id="datosotroc" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaOC();"></div></td>
					<td>
						
					</td>
						<SCRIPT LANGUAGE="JavaScript">
							<!--
							function enviaOC() {
								var a = document.getElementById("otroc").value;
								var b = document.getElementById("ci_N_7").value;
								window.open('otrosc.php?cargos=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
								
							}

							//-->
					</SCRIPT>

					
				</tr>
				
			</tbody>
		</table>
    </td>
    </tr>	

	<tr>
    <td width="750">
		<br>
        <div class="tit14" style="text-align:left;">
		Perfil Historico:
		</div>
        <table align="center" border="0" cellpadding="1" cellspacing="2" 
		 width="740" style="border-collapse:collapse;border-color:white; border-style:solid; background:#D2DEF0;">
            <tbody>
                <tr class="datosp">
					<td style="width: 250px;">Ha dictado otras materias en la UNEXPO: &nbsp; 
					<select name="mathS" id="math_S_1" class="datospf" 
						  style="width: 100px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_math.style.display='block'; c_math.focus(); datosmh.style.display='block'; document.getElementById('mathEtq').style.color='#000000';} else {c_math.style.display='none'; c_math.value ='0'; datosmh.style.display='none'; document.getElementById('mathEtq').style.color='#D2DEF0';}} validar(this);">
							<option value="">-SELECCIONE-</option>
							<option value="NO">NO</option>
							<option value="SI">SI</option>
					</select><BR>
					<span class="titulo" style="color:gray; font-size:11px;">
					(Completar solo si son distintas a las incluidas anteriormente)</span>
					</td>
					<td style="width: 60px; color:#D2DEF0;" colspan="2">
						<div id="mathEtq">Indique Cuantas:</div>
					</td>
					<td class="datosp" style="width: 30px;">
						<INPUT TYPE="text" NAME="c_math" id="math" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);">
					</td>
					<td class="datosp" style="width: 70px;">
						<INPUT TYPE="button" id="datosmh" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaMH();">
					</td>

						<SCRIPT LANGUAGE="JavaScript">
							<!--
							function enviaMH() {
								var a = document.getElementById("math").value;
								var b = document.getElementById("ci_N_7").value;
								window.open('materiash.php?materias=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
								
							}

							//-->
					</SCRIPT>

					
				</tr>
				<tr>
					<td>&nbsp;</td>
					
				</tr> 
				<tr class="datosp">
					<td style="width: 250px;">Ha ejercido otros cargos en la UNEXPO: &nbsp; 
					<select name="otrochS" id="otroch_S_1" class="datospf" 
						  style="width: 100px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_otroch.style.display='block'; c_otroch.focus(); datosoch.style.display='block'; document.getElementById('otrochEtq').style.color='#000000';} else {c_otroch.style.display='none'; c_otroch.value ='0'; datosoch.style.display='none'; document.getElementById('otrochEtq').style.color='#D2DEF0';}} validar(this);">
							<option value="">-SELECCIONE-</option>
							<option value="NO">NO</option>
							<option value="SI">SI</option>
					</select>
					</td>
					<td style="width: 60px; color:#D2DEF0;" colspan="2">
						<div id="otrochEtq">Indique Cuantos:</div>
					</td>
					<td class="datosp" style="width: 30px;">
						<INPUT TYPE="text" NAME="c_otroch" id="och" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);">
					</td>
					<td class="datosp" style="width: 70px;">
						<INPUT TYPE="button" id="datosoch" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaOCH();">
					</td>

						<SCRIPT LANGUAGE="JavaScript">
							<!--
							function enviaOCH() {
								var a = document.getElementById("och").value;
								var b = document.getElementById("ci_N_7").value;
								window.open('otrosch.php?cargos=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
								
							}

							//-->
					</SCRIPT>

					
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
        <div class="tit14" style="text-align:left;">
		Datos Acad&eacute;micos:
		</div>
        <table id="dAcad" align="center" border="0" cellpadding="1" cellspacing="2" 
		 width="740" style="border-collapse:collapse;border-color:white; border-style:solid; background:#D2DEF0;">
            <tbody>
                <tr class="datosp">
					<td style="width: 200px;">
						Cantidad T&iacute;tulos Pregrado:
						<select name="pregS" id="preg_S_1" class="datospf" 
						  style="width: 50px;" onChange="with(document.datos_p){ if (this.value !='0'){enviaPreG();}} validar(this);">
							<option value="0">-SEL-</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
						<SCRIPT LANGUAGE="JavaScript">
						<!--
						function enviaPreG() {
							var a = document.getElementById("preg").value;
							var b = document.getElementById("ci_N_7").value;
							window.open('pregrado.php?pregrado=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
							
						}

						//-->
						</SCRIPT>
					
					</td>
					
					<td style="width: 120px;">
						 Estudios de PostGrado:
					</td>
					<td style="width: 60px;">
						<select name="postgS" id="postgrados_S_1" class="datospf" 
							  style="width: 50px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_postg.style.display='block'; c_postg.focus(); datospostg.style.display='block'; document.getElementById('postgEtq').style.color='#000000';} else {c_postg.style.display='none'; c_postg.value ='0'; datospostg.style.display='none'; document.getElementById('postgEtq').style.color='#D2DEF0';}} validar(this);">
								<option value="">-SEL-</option>
								<option value="NO">NO</option>
								<option value="SI">SI</option>
						</select>
					</td>
					<td style="width: 100px; color:#D2DEF0;" colspan="2">
						<div id="postgEtq">Indique Cuantos:</div>
					</td>
					<td class="datosp" style="width: 30px;">
						<INPUT TYPE="text" NAME="c_postg" id="postg" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);">
					</td>
					<td class="datosp" style="width: 170px;">
						<INPUT TYPE="button" id="datospostg" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaPostG();">
					</td>

						<SCRIPT LANGUAGE="JavaScript">
							<!--
							function enviaPostG() {
								var a = document.getElementById("postg").value;
								var b = document.getElementById("ci_N_7").value;
								window.open('postgrado.php?postgrados=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
								
							}

							//-->
					</SCRIPT>
                </tr>
				
				<tr class="datosp">
					<td style="width: 200px;">&nbsp;</td>
					<td style="width: 120px;">
						 Ha realizado cursos:
					</td>
					<td style="width: 60px;">
						<select name="cursosS" id="cursos_S_1" class="datospf" 
							  style="width: 50px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_cursos.style.display='block'; c_cursos.focus(); datoscursos.style.display='block'; document.getElementById('cursosEtq').style.color='#000000';} else {c_cursos.style.display='none'; c_cursos.value ='0'; datoscursos.style.display='none'; document.getElementById('cursosEtq').style.color='#D2DEF0';}} validar(this);">
								<option value="">-SEL-</option>
								<option value="NO">NO</option>
								<option value="SI">SI</option>
						</select>
					</td>
					<td style="width: 80px; color:#D2DEF0;" colspan="2">
						<div id="cursosEtq">Indique Cuantos:</div>
					</td>
					<td class="datosp" style="width: 30px;">
						<INPUT TYPE="text" NAME="c_cursos" id="cursos" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);">
					</td>
					<td class="datosp" style="width: 70px;">
						<INPUT TYPE="button" id="datoscursos" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaCUR();">
					</td>

						<SCRIPT LANGUAGE="JavaScript">
							<!--
							function enviaCUR() {
								var a = document.getElementById("cursos").value;
								var b = document.getElementById("ci_N_7").value;
								window.open('cursos.php?cursos=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
								
							}

							//-->
					</SCRIPT>
					</td>
					
				</tr>

				<tr class="datosp">
					<td style="width: 200px;">&nbsp;</td>
					<td style="width: 150px;">
						 Ha realizado publicaciones:
					</td>
					<td style="width: 60px;">
						<select name="publiS" id="publi_S_1" class="datospf" 
							  style="width: 50px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_publi.style.display='block'; c_publi.focus(); datospubli.style.display='block'; document.getElementById('publiEtq').style.color='#000000';} else {c_publi.style.display='none'; c_publi.value ='0'; datospubli.style.display='none'; document.getElementById('publiEtq').style.color='#D2DEF0';}} validar(this);">
								<option value="">-SEL-</option>
								<option value="NO">NO</option>
								<option value="SI">SI</option>
						</select>
					</td>
					<td style="width: 80px; color:#D2DEF0;" colspan="2">
						<div id="publiEtq">Indique Cuantas:</div>
					</td>
					<td class="datosp" style="width: 30px;">
						<INPUT TYPE="text" NAME="c_publi" id="publi" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);">
					</td>
					<td class="datosp" style="width: 70px;">
						<INPUT TYPE="button" id="datospubli" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaPUB();">
					</td>

						<SCRIPT LANGUAGE="JavaScript">
							<!--
							function enviaPUB() {
								var a = document.getElementById("publi").value;
								var b = document.getElementById("ci_N_7").value;
								window.open('publicaciones.php?publi=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
								
							}

							//-->
					</SCRIPT>
					</td>
					
				</tr>

				<tr class="datosp">
					<td style="width: 200px;">&nbsp;</td>
					<td style="width: 155px;">
						 Participaci&oacute;n en Concursos:
					</td>
					<td style="width: 60px;">
						<select name="concurS" id="concur_S_1" class="datospf" 
							  style="width: 50px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_concur.style.display='block'; c_concur.focus(); datosconcur.style.display='block'; document.getElementById('concurEtq').style.color='#000000';} else {c_concur.style.display='none'; c_concur.value ='0'; datosconcur.style.display='none'; document.getElementById('concurEtq').style.color='#D2DEF0';}} validar(this);">
								<option value="">-SEL-</option>
								<option value="NO">NO</option>
								<option value="SI">SI</option>
						</select>
					</td>
					<td style="width: 80px; color:#D2DEF0;" colspan="2">
						<div id="concurEtq">Indique Cuantos:</div>
					</td>
					<td class="datosp" style="width: 30px;">
						<INPUT TYPE="text" NAME="c_concur" id="concur" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);">
					</td>
					<td class="datosp" style="width: 70px;">
						<INPUT TYPE="button" id="datosconcur" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaCON();">
					</td>

						<SCRIPT LANGUAGE="JavaScript">
							<!--
							function enviaCON() {
								var a = document.getElementById("concur").value;
								var b = document.getElementById("ci_N_7").value;
								window.open('concursos.php?concur=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
								
							}

							//-->
					</SCRIPT>
					</td>
					
				</tr>

				<tr class="datosp">
					<td style="width: 200px;">&nbsp;</td>
					<td style="width: 155px;">
						 Participa en Investigaciones:
					</td>
					<td style="width: 60px;">
						<select name="invesS" id="inves_S_1" class="datospf" 
							  style="width: 50px;" onChange="with(document.datos_p){ if (this.value =='SI')  {c_inves.style.display='block'; c_inves.focus(); datosinves.style.display='block'; document.getElementById('invesEtq').style.color='#000000';} else {c_inves.style.display='none'; c_inves.value ='0'; datosinves.style.display='none'; document.getElementById('invesEtq').style.color='#D2DEF0';}} validar(this);">
								<option value="">-SEL-</option>
								<option value="NO">NO</option>
								<option value="SI">SI</option>
						</select>
					</td>
					<td style="width: 80px; color:#D2DEF0;" colspan="2">
						<div id="invesEtq">Indique Cuantas:</div>
					</td>
					<td class="datosp" style="width: 30px;">
						<INPUT TYPE="text" NAME="c_inves" id="inves" class="datospf" maxlength="2" style="width: 30px; display:none;" onKeyUp="validarN(this);">
					</td>
					<td class="datosp" style="width: 70px;">
						<INPUT TYPE="button" id="datosinves" value="incluir datos" class="boton" style="width: 90px; display: none;" onClick="enviaIN();">
					</td>

						<SCRIPT LANGUAGE="JavaScript">
							<!--
							function enviaIN() {
								var a = document.getElementById("inves").value;
								var b = document.getElementById("ci_N_7").value;
								window.open('investigaciones.php?invest=' + a +'&doc=' + b,'POPUP','toolbar=no,status=no,scrollbars=yes,resizable=no,height=400,width=650,left=110, screenX=0,top=150,screenY=0');
								
							}

							//-->
					</SCRIPT>
					</td>
					
				</tr>
				<tr>
					<td colspan="7">&nbsp;</td>										
				</tr>
                <tr class="datosp">
					<td colspan="7">Idiomas que domina:&nbsp;&nbsp;
						<INPUT TYPE="checkbox" NAME="idiomas" value="ingles" onChange="if (datos_p.idioma[0].disabled == true){datos_p.ingles.value='SI'} else{datos_p.ingles.value='no'}">Ingles</input>&nbsp;&nbsp;&nbsp;
						<INPUT TYPE="hidden" name="ingles">
						
						<INPUT TYPE="checkbox" NAME="idiomas" value="frances">Frances</input>&nbsp;&nbsp;&nbsp;
						<INPUT TYPE="hidden" name="frances">

						<INPUT TYPE="checkbox" NAME="idiomas" value="aleman">Aleman</input>&nbsp;&nbsp;&nbsp;
						<INPUT TYPE="checkbox" NAME="idiomas" value="porugues">Portugues</input>&nbsp;&nbsp;&nbsp;
						<INPUT TYPE="checkbox" NAME="idiomas" value="italiano">Italiano</input>
					</td>										
				</tr>
				<tr>
					<td colspan="7">&nbsp;</TEXTAREA></td>										
				</tr>

			</tbody>
		</table>
    </td>
    </tr>	
	<tr>
    <td width="750"><br>
        <div class="tit14" style="text-align:left;">
		Otros Datos:
		</div>
        <table id="dSocioE" align="center" border="0" cellpadding="1" cellspacing="2" 
		 width="740" style="border-collapse:collapse;border-color:white; border-style:solid; background:#D2DEF0;">
            <tbody>
                
P001;
	
	
	
	print <<<P002
						
						
				<tr><td>&nbsp;</td></tr>



			</tbody>
		</table>
	</td></tr>
	<tr  class="datosp" style="background-color:white;">
		<td width="740">&nbsp;
			<hr size="1" width="740">
        <div class="tit14" id="msgError" style="text-align:left;display:none; background-color:#ffff99;">
		Verifique: Existen errores en los campos marcados en amarillo</div></td>
	</tr>
	<tr class="datosp" style="background-color:white;">
		<td>        
		<table id="tBoton" align="center" border="0" cellpadding="1" cellspacing="2"
		 width="740" style="border-collapse:collapse;border-color:white; border-style:solid; background:white;">
			<tr><td style="width: 250px"  align="center">
				<input class="boton" type="button" value="Salir" id="Salir" 
					onclick="window.close();">
				<input type="hidden" name="contra" value="">
				</td>
				<td style="width: 250px" align="center">
				<input class="boton" type="reset" value="Borrar Todo"
					onclick="with (document) {datos_p.turnoTrabaja.style.display='none'; getElementById('turnoTrabajaEtq').style.color='#D2DEF0'; datos_p.costo_mensual.style.display='none'; getElementById('montoEtq').style.color='#D2DEF0'; datos_p.otroSistemaE.style.display ='none'; datos_p.otroTurnoE.style.display ='none'; datos_p.otroTitulo.style.display ='none'; datos_p.otroOcupPadre.style.display ='none'; datos_p.otroOcupMadre.style.display ='none'; datos_p.monto_alq.style.display ='none';} reiniciarTodo(false);">
				</td>
				<td  align="center" style="width: 250px">
				<INPUT TYPE="button" class="boton" value="Procesar" onclick="return validarF(document.datos_p);">
				</td>
			</tr>
		</table></td>
	</tr>
	
	</form>
	</table>
P002
; 
       
       
    
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
        mensaje = "La cedula no esta registrada o es incorrecta.\n";
		mensaje = mensaje + "Es posible que usted deba solicitar REINGRESO\n";
		mensaje = mensaje + "si se retiro en el semestre anterior.";
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
    //$_POST['cedula']='17583838';
    //$_POST['contra']='827ccb0eea8a706c4c34a16891f84e7b';       
    if(isset($_POST['cedula']) && isset($_POST['contra'])) {
        $cedula=$_POST['cedula'];
        $contra=$_POST['contra'];
        // limpiemos la cedula y coloquemos los ceros faltantes
        $cedula = ltrim(preg_replace("/[^0-9]/","",$cedula),'0');
       // $cedula = substr("00000000".$cedula, -8);
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
<script language="javascript1.2">
<!--
var caldef1 =
{
	firstday : 0,
	dtype : 'yyyy-MM-dd',
	width : 275,
	windoww : 300,
	windowh : 200,
	border_width : 0,
	border_color : 'White',
	multi : true,
	spr : '\r\n',
	dn_css : 'clsDayName',
	cd_css : 'clsCurrentDay',
	tw_css : 'clsCurrentWeek',
	wd_css : 'clsWorkDay',
	we_css : 'clsWeekEnd',
	wdom_css : 'clsWorkDayOtherMonth',
	weom_css : 'clsWeekEndOtherMonth',
	wdomcw_css : 'clsWorkDayOthMonthCurWeek',
	weomcw_css : 'clsWeekEndOthMonthCurWeek',
	wecd_css : 'clsWeekEndCurDay',
	wecw_css : 'clsWeekEndCurWeek',
	preview_css : 'clsPreview',
	highlight_css : 'clsHighLight',
	headerstyle :
	{
		type : 'buttons',
		css : 'clsDayName',
		imgnextm : 'img/next.gif',
		imgprevm : 'img/prev.gif',
		imgnexty : 'img/next_year.gif',
		imgprevy : 'img/prev_year.gif'
	},
	imgapply : 'img/apply.gif',
	preview : true,
	monthnames : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
		'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
	daynames : ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
	txt : ['Año Anterior', 'Mes Anterior', 'Mes Siguiente', 'Año Siguiente', 'Aceptar']
};
var c1 = new CodeThatCalendar(caldef1); 
//-->

function validarL(campo) {

	var cadena = campo.value;
    var nums="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú " + "Üü";
	if (campo.alt == 'Apellidos' || campo.alt == 'Nombres') {
		nums = nums + "'";
	}
    var i=0;
    var cl=cadena.length;
    while(i < cl)  {
		cTemp= cadena.substring (i, i+1);
        if (nums.indexOf (cTemp, 0)==-1) {
            cadT = cadena.split(cTemp);
            var cadena = cadT.join("");
            campo.value=cadena;
            i=-1;
            cl=cadena.length;
		}
        i++;
    }
	campo.value = campo.value.toUpperCase();
}

</script>




<title><?php echo $tProceso .' '. $lapso; ?></title>



<?
        $cedYclave = cedula_valida($cedula,$contra);
		if(!$fvacio && $cedYclave[0] && $cedYclave[1] && $cedYclave[2]) {
				//print 'hola mundo ';
                $Cmat = new ODBC_Conn("DACEPOZ","N","N");
				//print $Cmat;
				if ( $sedeActiva == 'POZ' ) {
					 $mSQL = "select distinct a.his_act,a.his_lap,a.his_cod, ";
					 $mSQL= $mSQL."a.his_sec,a.his_fec,d.asignatura ";
					 $mSQL= $mSQL."from his_act a,dace004 b,tblaca008 d ";
				     $mSQL= $mSQL."where a.his_act=b.acta and a.his_lap=b.lapso and ";
					 $mSQL= $mSQL."a.his_cod=b.c_asigna and a.his_lap=b.lapso and a.his_ced='".$cedula."' ";
					 $mSQL= $mSQL."and a.his_lap='$lapsoProceso' and a.his_cod=d.c_asigna ";
				
				}
			
                $Cmat->ExecSQL($mSQL);
				$lista_m=$Cmat->result;
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

<SCRIPT LANGUAGE="JavaScript">
<!--

	function validarN(campo) {

			var cadena = campo.value;
			var nums="1234567890";
			var i=0;
			var cl=cadena.length;
			while(i < cl)  {
				cTemp= cadena.substring (i, i+1);
				if (nums.indexOf (cTemp, 0)==-1) {
					cadT = cadena.split(cTemp);
					var cadena = cadT.join("");
					campo.value=cadena;
					i=-1;
					cl=cadena.length;
				}
				i++;
			}
		}

	function checkFields() {
		missinginfo = "";
		if (document.cacta.acta.value == "") {
			missinginfo += "\n     -  Acta";
		}
		if (missinginfo != "") {
			missinginfo ="EL NÚMERO DE ACTA ES REQUERIDO PARA CONTINUAR";
			alert(missinginfo);
			return false;
		}
		else return true;
		}

	
//-->
</SCRIPT>

