function contacto()
		{
		window.open('http://www.poz.unexpo.edu.ve/web/urace/contacto','contacto', 'location=0,resizable=0,scrollbars=0,toolbar=0,direcories=0,width=500,height=450,top=100,left=300')
		}

function prepdata() {
	with(document.datos_p) {
	// nacionalidad
		switch(nac_eS.selectedIndex){
			case 1 : nac_e.value = "V"; break;
			case 2 : nac_e.value = "E"; break;
		}
		//fecha de nacimiento	
		laFechaS =	1900 + parseInt(anioN.value,10); 
		laFechaS += '-';
		laFechaS +=	mesN.selectedIndex;
		laFechaS += '-';
		laFechaS +=	diaN.selectedIndex; 
		f_nac_e.value = laFechaS;
		edad.disabled =false;
		//estado civil
		edo_c_e.value = edo_c_eS.value;
		var edo = edo_c_eS.value;
		switch(edo){
			case 'SOLTERO' : edo_c_e.value = '1'; break;
			case 'CASADO' : edo_c_e.value = '2'; break;
			case 'CONCUBINO' : edo_c_e.value = '3'; break;
			case 'VIUDO' : edo_c_e.value = '4'; break;
		
		}

		// sexo
		sexo.value = sexoS.value;
		var sex = sexoS.value;
		switch(sex){
			case 'FEMENINO' : sexo.value = '0'; break;
			case 'MASCULINO' : sexo.value = '1'; break;
		}

		
		
		//opcion CNU
		opcion_cnu.value = opcion_cnuS.value;
		var lopcion = opcion_cnuS.value;
		switch(lopcion){
			case 'PRIMERA' : opcion_cnu.value = '1'; break;
			case 'SEGUNDA' : opcion_cnu.value = '2'; break;
			case 'TERCERA' : opcion_cnu.value = '3'; break;
		
		}
		// becario
		becario.value = becarioS.value;
		var lbeca = becarioS.value;
		switch(lbeca) {
			case 'SI'	: becario.value = 'SI'; break;
			case 'NO'	: becario.value = 'NO'; break;
		}
		// Tipo Extranjero
		res_extraj.value = res_extrajS.value;
		var lres = res_extrajS.value;
		switch(lres) {
			case 'RESIDENTE'	: res_extraj.value = 'RESIDENTE'; break;
			case 'TRANSEUNTE'	: res_extraj.value = 'TRANSEUNTE'; break;
		}

		// Documento de Identidad
		doc_ident.value = doc_identS.value;
		var lres = doc_identS.value;
		switch(lres) {
			case 'RESIDENTE'	: doc_ident.value = 'RESIDENTE'; break;
			case 'TRANSEUNTE'	: doc_ident.value = 'TRANSEUNTE'; break;
		}
		// direccion permanente
		dirp_e.value = avCalle.value+';'+barrio.value+';'+casa.value+';'+ciudad.value+';'+estado.value;
		//telefono permanente
		telfp_e.value = codT.value+'-'+telefono.value;
		// direccion de residencia
		dirr_e.value = avCalleR.value+';'+barrioR.value+';'+casaR.value+';'+ciudadR.value+';'+estadoR.value;
		//telefono de residencia
		telfr_e.value = codTR.value+'-'+telefonoR.value;
		//datos academicos:
		tipo_plantel.value = tipo_plantelS.value;

		if(sistema_estudioS.selectedIndex == (sistema_estudioS.length - 1)){
			sistema_estudio.value = 'OTRO:'+otroSistemaE.value;
		}
		else sistema_estudio.value = sistema_estudioS.value;

		if(turno_estudioS.selectedIndex == (turno_estudioS.length - 1)){
			turno_estudio.value = 'OTRO:'+otroTurnoE.value;
		}
		else turno_estudio.value = turno_estudioS.value;
		if(titulo_bS.selectedIndex == (titulo_bS.length - 1)){
			titulo_b.value = 'OTRO:'+otroTitulo.value;
		}
		else titulo_b.value = titulo_bS.value;
		promedio.value = promedioS.value;
		//datos socioeconomicos
		trabaja.value = trabajaS.selectedIndex;
		turno_trabajo.value = turnoTrabaja.value;

		instr_padre.value = instr_padreS.value;
		if(ocup_padreS.selectedIndex == (ocup_padreS.length - 1)){
			ocup_padre.value = 'OTRO:'+ otroOcupPadre.value;
		}
		else ocup_padre.value = ocup_padreS.value;
		instr_madre.value = instr_madreS.value;
		if(ocup_madreS.selectedIndex == (ocup_madreS.length - 1)){
			ocup_madre.value = 'OTRO:'+ otroOcupMadre.value;
		}
		else ocup_madre.value = ocup_madreS.value;
		tipo_vivienda.value = tipo_viviendaS.value;
		ingreso_f.value =ingreso_fS.value;
		ingreso_fBs.value =ingreso_fS.options[ingreso_fS.selectedIndex].text;
	}
}

function reiniciarTodo() {

	with (document.datos_p) {
		var opcion = 0;
		var opcioni = 0;
		
		//nacionalidad
		switch(nac_e.value) {
			case "V"	: opcion = 1; break;
			case "E"	: opcion = 2; break;
		}
		nac_eS.selectedIndex = opcion;
		//Actualizamos fecha de nacimiento:
		laFechaS = f_nac_e.value+"---"; //por si la fecha esta en blanco
		laFecha  = new Array();
		laFecha = laFechaS.split('-'); //anio,mes,dia
		//	alert('['+laFecha+']'+laFecha[2]+laFecha[1]+laFecha[0]);
		if (laFechaS != ""){
			diaN.selectedIndex = laFecha[2]; 
			mesN.selectedIndex = laFecha[1];
			anioN.value = laFecha[0].substr(2,4); 
		}
		calcularEdad();
		// seleccionamos edo civil
		
		edo_c_eS.selectedIndex = edo_c_e.value;

		//seleccionamos sexo
		switch(sexo.value) {
			case "0"	: opcion = 1; break;
			case "1"	: opcion = 2; break;
		}
		sexoS.selectedIndex = opcion;
		// Si trabaja, mostramos su turno de trabajo
		if(trabaja.value == "2") {
			switch (turno_trabajo.value){
				case "DIURNO"	: opcion = 1; break;
				case "NOCTURNO"	: opcion = 2; break;
				case "MIXTO"	: opcion = 3; break;
				default			: opcion = 0; 
			}
			trabajaS.selectedIndex = 2;
			turnoTrabaja.selectedIndex = opcion;
			turnoTrabaja.style.display = 'block';
		}
		else if (trabaja.value == "1")	{
			trabajaS.selectedIndex = 1;
		}
		// Si es extranjero, mostramos los datos adicionales
		// Residencia de Extranjeria
		if(nac_e.value == "E") {
			switch (res_extraj.value){
				case "RESIDENTE"	: opcion = 1; break;
				case "TRANSEUNTE"	: opcion = 2; break;				
				default			    : opcion = 0; 
			}
		// Tipo de Documento
			switch(doc_ident.value){
				case "CEDULA"	    : opcioni = 1; break;
				case "PASAPORTE"	: opcioni = 2; break;				
				default			    : opcioni = 0; 
			}
			nac_eS.selectedIndex = 2;
			res_extrajS.selectedIndex = opcion;
			res_extrajS.style.display = 'block';
			document.getElementById('tipoEtq').style.color='#000000';
			doc_identS.selectedIndex = opcioni;
			doc_identS.style.display = 'block';
			document.getElementById('docEtq').style.color='#000000';
			if (doc_ident.value == "PASAPORTE"){
			pasaporte_nro.style.display='block';
			document.getElementById('pasaporteEtq').style.color='#000000';
			}
		}
		else if (nac_e.value == "V")	{
			nac_eS.selectedIndex = 1;
			pasaporte_nro.style.display='none';
			document.getElementById('pasaporteEtq').style.color='#D2DEF0';
		}
		//
		
		
		// Mostramos la opcion CNU
		
			switch (opcion_cnu.value){
				case "1"	: opcion = 1; break;
				case "2"	: opcion = 2; break;
				case "2"	: opcion = 3; break;
				default			: opcion = 0; 
			}
			
			opcion_cnuS.selectedIndex = opcion;
		
		// Mostramos si es becario y por quien
		if (becario.value == "SI"){			
			organismo.style.display='block';
			document.getElementById('orgEtq').style.color='#000000';
			becarioS.selectedIndex = 1;				
			}			
		else if (becario.value == "NO")	{
			organismo.style.display='none';
			becarioS.selectedIndex = 2;	
		}

		// Mostramos si pertenece a etnia indigena
		if (etnia_indigena.value != ""){			
			etnia_indigena.style.display='block';
			document.getElementById('etniaEtq').style.color='#000000';
			etnia_indigenaS.selectedIndex = 1;				
			}			
		else if (etnia_indigena.value == "")	{
			etnia_indigena.style.display='none';
			etnia_indigenaS.selectedIndex = 2;	
		}
		
		
		
		// mostramos la direccion
		var direc = new Array();
		if(dirp_e.value != "") {
			direc			= dirp_e.value.split(";");
			avCalle.value	= direc[0];
			barrio.value	= direc[1];
			casa.value		= direc[2];
			ciudad.value	= direc[3];
			estado.value	= direc[4];
		}
		if (telfp_e.value !="") {
			direc			= telfp_e.value.split("-");
			codT.value		= direc[0];
			telefono.value	= direc[1];
		}
		if(dirr_e.value != "") {
			direc			= dirr_e.value.split(";");
			avCalleR.value	= direc[0];
			barrioR.value	= direc[1];
			casaR.value		= direc[2];
			ciudadR.value	= direc[3];
			estadoR.value	= direc[4];
		}
		if (telfr_e.value !="") {
			direc			= telfr_e.value.split("-");
			codTR.value		= direc[0];
			telefonoR.value	= direc[1];
		}
		// tipo plantel:
		if (tipo_plantel.value == "PRIVADO") {
			tipo_plantelS.selectedIndex = 2;
			costo_mensual.style.display='block'; 
			document.getElementById('montoEtq').style.color='#000000'; 
		}
		else if (tipo_plantel.value == "PUBLICO") {
			tipo_plantelS.selectedIndex = 1;
		}
		// sistema de estudio
		switch(sistema_estudio.value.substring(0,2)) {
			case "RE"	: opcion = 1; break;
			case "PA"	: opcion = 2; break;
			case "OT"	: opcion = 3; break;
			default		: opcion = 0;
		}
		sistema_estudioS.selectedIndex = opcion;
		if (opcion == 3) {
			direc = sistema_estudio.value.split(':');
			otroSistemaE.value = direc[1];
			otroSistemaE.style.display='block'; 
		}
		// turno de estudio
		switch(turno_estudio.value.substring(0,2)) {
			case "DI"	: opcion = 1; break;
			case "NO"	: opcion = 2; break;
			case "ES"	: opcion = 3; break;
			case "OT"	: opcion = 4; break;
			default		: opcion = 0;
		}
		turno_estudioS.selectedIndex = opcion;
		if (opcion == 4) {
			direc = turno_estudio.value.split(':');
			otroTurnoE.value = direc[1];
			otroTurnoE.style.display='block'; 
		}
		// titulo
		switch(titulo_b.value.substring(0,2)) {
			case "CI"	: opcion = 1; break;
			case "IN"	: opcion = 2; break;
			case "TE"	: opcion = 3; break;
			case "OT"	: opcion = 4; break;
			default		: opcion = 0;
		}
		titulo_bS.selectedIndex = opcion;
		if (opcion == 4) {
			direc = titulo_b.value.split(':');
			otroTitulo.value = direc[1];
			otroTitulo.style.display='block'; 
		}
		// promedio
		switch(promedio.value.substring(0,5)) {
			case "menor"	: opcion = 1; break;
			case "de 13"	: opcion = 2; break;
			case "de 16"	: opcion = 3; break;
			case "de 18"	: opcion = 4; break;
			default			: opcion = 0;
		}
		promedioS.selectedIndex = opcion;
		// instruccion del padre
		switch(instr_padre.value) {
			case "PRIMARIA INCOMPLETA"			: opcion = 1; break;
			case "PRIMARIA COMPLETA"			: opcion = 2; break;
			case "SECUNDARIA INCOMPLETA"		: opcion = 3; break;
			case "SECUNDARIA COMPLETA"			: opcion = 4; break;
			case "TECNICO SUP. UNIVERSITARIO"	: opcion = 5; break;
			case "UNIVERSITARIO"				: opcion = 6; break;
			default			: opcion = 0;
		}
		instr_padreS.selectedIndex = opcion;
		// ocupacion del padre
		direc = ocup_padre.value.split(':');
		switch(direc[0]) {
			case "HOGAR"						:	opcion = 1;	break;
			case "OBRERO"						:	opcion = 2;	break;
			case "COMERCIANTE INFORMAL"			:	opcion = 3;	break;
			case "COMERCIANTE FORMAL"			:	opcion = 4;	break;
			case "EMPLEADO"						:	opcion = 5;	break;
			case "TECNICO"						:	opcion = 6;	break;
			case "PROFESIONAL UNIVERISTARIO"	:	opcion = 7;	break;
			case "OTRO"							:	opcion = 8;	break;
			default								:	opcion = 0;
		}
		ocup_padreS.selectedIndex = opcion;
		if (opcion == 8) {
			otroOcupPadre.value = direc[1];
			otroOcupPadre.style.display='block'; 
		}
		// instruccion de la madre
		switch(instr_madre.value) {
			case "PRIMARIA INCOMPLETA"			: opcion = 1; break;
			case "PRIMARIA COMPLETA"			: opcion = 2; break;
			case "SECUNDARIA INCOMPLETA"		: opcion = 3; break;
			case "SECUNDARIA COMPLETA"			: opcion = 4; break;
			case "TECNICO SUP. UNIVERSITARIO"	: opcion = 5; break;
			case "UNIVERSITARIO"				: opcion = 6; break;
			default								: opcion = 0;
		}
		instr_madreS.selectedIndex = opcion;
		// ocupacion de la madre
		direc = ocup_madre.value.split(':');
		switch(direc[0]) {
			case "HOGAR"						:	opcion = 1;	break;
			case "OBRERA"						:	opcion = 2;	break;
			case "COMERCIANTE INFORMAL"			:	opcion = 3;	break;
			case "COMERCIANTE FORMAL"			:	opcion = 4;	break;
			case "EMPLEADA"						:	opcion = 5;	break;
			case "TECNICO"						:	opcion = 6;	break;
			case "PROFESIONAL UNIVERISTARIO"	:	opcion = 7;	break;
			case "OTRO"							:	opcion = 8;	break;
			default								:	opcion = 0;
		}
		ocup_madreS.selectedIndex = opcion;
		if (opcion == 8) {
			otroOcupMadre.value = direc[1];
			otroOcupMadre.style.display='block'; 
		}
		// tipo de vivienda
		switch(tipo_vivienda.value) {
			case "PROPIA"		:	opcion = 1;	break;
			case "HIPOTECADA"	:	opcion = 2;	break;
			case "ALQUILADA"	:	opcion = 3;	break;
			default				:	opcion = 0;
		}
		tipo_viviendaS.selectedIndex = opcion;
		if (opcion == 3) {
			monto_alq.style.display='block'; 
		}
		// ingreso familiar
		direc = ingreso_f.value.split(':');
		opcion = parseInt(direc[0],10);
		ingreso_fS.selectedIndex = opcion;
	}
}

function calcularEdad() {
	var hoy = new Date();
	with(document) {
		dia  = parseInt('0'+getElementById('diaN_S_1').selectedIndex,10);
		if (dia < 1){
			dia=1;
		}
		mes  = parseInt('0'+getElementById('mesN_S_1').value,10);
		anio = 1900 + parseInt('0'+getElementById('anioN_N_2').value,10);
	}
	var fnac= new Date(anio,mes,dia);
	var edad = new Date();
	if (fnac.getTime() < 0) {
		edad.setTime(hoy.getTime() - fnac.getTime());
		aniosEdad = edad.getYear();
		if (aniosEdad < 200) {
		aniosEdad = 1900 + aniosEdad;
		}
		aniosEdad = aniosEdad/1.0 + (edad.getMonth()+1)/12 + (edad.getDate())/365.25;
		aniosEdad = aniosEdad - 1970.0;
	}
	else {
		edad.setTime(hoy.getTime() - fnac.getTime());
		aniosEdad = edad.getYear()/1.0 + (edad.getMonth()+1)/12 + (edad.getDate())/365.25;
		if (aniosEdad < 200){
			aniosEdad = aniosEdad - 70.0;
		}
		else aniosEdad = aniosEdad - 1970.0;
	}
//	alert(aniosEdad);
	document.getElementById('edad').value = Math.floor(aniosEdad);
}

function soloBlancos (campo) {
	var i = 0;
	var c = 0;
	if(campo.value.length == 1) {
		return false;
	}
	for (i = 0; i < campo.value.length - 1; i++) {
		if (campo.value.charAt(i) != campo.value.charAt(i + 1)) {
			return false;
		}
   }
  campo.value = "";
  return true ;
 }

function validarN(campo) {

	var cadena = campo.value;
    var nums="1234567890.";
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

function validarA(campo) {

	var cadena = campo.value;
	var nums ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú " + "Üü" + "0123456789" + ".-()#ºª/,";
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


function validarLetras(campo, longitud, nValido, conMsg) {
	
	var valido = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú " + "Üü";
	if (nValido.length>0) {
		valido = nValido;
	}
	if (campo.alt == 'Apellidos' || campo.alt == 'Nombres') {
		valido = valido + "'";
	}
	var invalido = false;
	var temp;
	var msg ="";
	for (var i=0; i<campo.value.length; i++) {
		temp = "" + campo.value.substring(i, i+1);
		if (valido.indexOf(temp) == "-1") {
			invalido = true;
			msg = "- No se permite el caracter \""+temp+"\" en este campo.\n";
			break;
		}
	}
	var longInvalida = (campo.value.length < longitud);
	if (longInvalida || soloBlancos(campo) && (longitud > 0)) {
		msg = msg+"- No ha escrito un valor correcto para este campo.\n";
		invalido = true;
	}
	if (invalido) {
		msg = "Han ocurrido los siguientes errores:\n" + msg;
		if(conMsg){
			msg = msg + "Por favor corrija el campo " + campo.alt.toUpperCase();
			alert(msg);
		}
		campo.style.backgroundColor = "#FFFF99";
	}
	else {
		campo.style.backgroundColor = "#FFFFFF";
	}

	return !invalido;
}

function validarAlfaN(campo,longitud, conMsg) {

	var alfaC ="ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" + "abcdefghijklmnñopqrstuvwxyzáéíóú " + "Üü" + "0123456789" + ".-()#ºª/,";

	return validarLetras(campo,longitud, alfaC, conMsg);
}

function validarNum(campo, longitud, conMsg) {

	var cadena = campo.value;
    var nums="1234567890";
    var i=0;
    var cl=cadena.length;
    var checkc = false;
	var msg ="";
    while(i < cl)  {
		cTemp= cadena.substring (i, i+1);
        if (nums.indexOf (cTemp, 0)==-1) {
			if (!checkc){
				msg =" - Ha introducido caracteres no numéricos y se eliminarán\n";
                checkc = true;
            }
            cadT = cadena.split(cTemp);
            var cadena = cadT.join("");
            campo.value=cadena;
            i=-1;
            cl=cadena.length;
		}
        i++;
    }
	var longInvalida = (campo.value.length < longitud);
	if (longInvalida) {
		msg = msg+"- No ha escrito un valor correcto para este campo.\n";
		invalido = true;
	}
	if (checkc || longInvalida) {
		msg = "Han ocurrido los siguientes errores:\n" + msg;
		if(conMsg){
			msg = msg + "Por favor corrija el campo " + campo.alt.toUpperCase();
			alert(msg);
		}
		campo.style.backgroundColor = "#FFFF99";
	}
	else {
		campo.style.backgroundColor = "#FFFFFF";
	}
	return !(checkc || longInvalida);
}

function validarSelect(campo, conMsg) {
	if (campo.selectedIndex == 0) {
		campo.style.backgroundColor = "#FFFF99";
		if(conMsg) alert('Por favor, seleccione un valor de la lista');
		return false;
	}
	else {
		campo.style.backgroundColor = "#FFFFFF";
	}
	return true;
}

function validar(campo){
	with (campo){
		tempID = id.split('_');
		switch (tempID[1]) {
			case 'L' :	return validarLetras(campo,tempID[2],'',true);
						break;
			case 'A' :	return validarAlfaN(campo,tempID[2],true);
						break;
			case 'N' :	return validarNum(campo,tempID[2],true);
						break;
			case 'S' :	return validarSelect(campo,true);
						break;
		}
	}
}

function anyoBisiesto(anyo){
	var fin = anyo;
	if (fin % 4 != 0)
    return false;
    else {
		if (fin % 100 == 0) {
			if (fin % 400 == 0){
				return true;
			}
			else {
				return false;
			}
		}
		else {
		   return true;
		}
	}
}

function fechaValida(dia,mes,anyo, conMsg){
  var anyohoy = new Date();
  var Mensaje = "";
  var yearhoy = anyohoy.getYear();
  if (yearhoy < 1999)
    yearhoy = yearhoy + 1900;
  if(anyoBisiesto(anyo))
    febrero = 29;
  else
      febrero = 28;
  if ((mes == 2) && (dia > febrero) || (dia == 0)){
		Mensaje += "- Día de nacimiento inválido\r\n";
  }
  if (((mes == 4) || (mes == 6) || (mes == 9) || (mes == 11)) && (dia > 30)){
		Mensaje += "- Día de nacimiento inválido\r\n";
  }
  if (mes == 0){
		Mensaje += "- Mes de nacimiento inválido\r\n";
  }
  if ((anyo<1950) || (yearhoy - anyo < 15)){
		Mensaje += "- Año de nacimiento inválido\r\n";
  } 
  if (Mensaje != "") {
	   alert(Mensaje);
	   return false;
  }
  else {
	  return true;
  }
}


function mostrar_ayuda(ayudaURL) {
		window.open(ayudaURL,"instruciones","left=0,top=0,width=700,height=250,scrollbars=0,resizable=0,status=0");
}

function verificarFecha(f, conMsg){
	with(f){
		var dia = parseInt (diaN.selectedIndex);
		var mes = parseInt (mesN.selectedIndex);
		var anyo = parseInt ('0'+anioN.value,10) + 1900;

		if (!fechaValida(dia,mes,anyo, conMsg)) {
			diaN.style.backgroundColor = "#FFFF99";
			mesN.style.backgroundColor = "#FFFF99";
			anioN.style.backgroundColor = "#FFFF99";
			return false;
		}
		else {
			diaN.style.backgroundColor = "#FFFFFF";
			mesN.style.backgroundColor = "#FFFFFF";
			anioN.style.backgroundColor = "#FFFFFF";
			return true;
		}
	}
}

function validarF(f){

	var hayError = false;
	totalE = 0;
	with (f){
		for (i =0;i<elements.length ;i++ ){
			temp = elements[i].id.split('_');
			if (temp.length == 3 && elements[i].style.display !='none') {
				switch (temp[1]) {
					case 'L' :	hayError = !validarLetras(elements[i],temp[2],'',false);
								break;
					case 'A' :	hayError = !validarAlfaN(elements[i],temp[2],false);
								break;
					case 'N' :	hayError = !validarNum(elements[i],temp[2],false);
								break;
					case 'S' :  hayError = !validarSelect(elements[i],false);
								break;
				}
				if(hayError){
					totalE++;
				}
			}
		}
	}
	if (!verificarFecha(f,false)) totalE++;
	if(totalE>0){
		document.getElementById('msgError').style.display='block';
		alert('Verifique: \nExisten errores en los campos marcados en amarillo.');
		document.getElementById('Procesar').style.display='block';
		//return false;
	}
	else {
		f.submit();
		prepdata();
		if (f.apellidos.value.indexOf(f.contra.value) >= 0) {
			f.submit();
			return true;
		}
		else {
			alert('Disculpe, El apellido introducido no esta registrado.\n Por favor corrija e intente de nuevo.');
			f.apellidos.style.backgroundColor='#FFFF99';
			f.apellidos.focus();
			document.getElementById('Procesar').style.display='block';
			return false;
		}
	}
}
