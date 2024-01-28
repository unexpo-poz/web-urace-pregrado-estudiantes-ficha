function checkFields() {
missinginfo = "";
if (document.datos_p.correo_e.value == "") {
missinginfo += "\n     -  Correo Electronico";
}

if (missinginfo != "") {
missinginfo ="_____________________________\n" +
"Existen errores en los siguientes campos:\n" +
missinginfo + "\n_____________________________" +
"\nPor favor rectifique e intente de nuevo!";
alert(missinginfo);
return false;
}
else return true;
}