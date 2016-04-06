/*  
 * @author Carlos García Gómez      neorazorx@gmail.com
 * @copyright 2015, Carlos García Gómez. All Rights Reserved. 
 */ 

var provincia_list = [
    {value: 'ITAPUA'},
    {value: 'CENTRAL'},
    {value: 'CONCEPCIÓN'},
    {value: 'CORDILLERA'},
    {value: 'GUAIRÁ'},
    {value: 'MISIONES'},
    {value: 'ÑEEMBUCÚ'},
    {value: 'PARAGUARÍ'},
    {value: 'PRESIDENTE HAYES'},
    {value: 'SAN PEDRO'},
    {value: 'ALTO PARAGUAY'},
    {value: 'ALTO PARANÁ'},
    {value: 'AMAMBAY'},
    {value: 'BOQUERÓN'},
    {value: 'CAAGUAZÚ'},
    {value: 'CAAZAPÁ'},
    {value: 'CANINDEYÚ'}
];

//var ciudad_list=[
//   {value: 'ASUNCION'},
//   {value: 'LUQUE'},
//   {value: 'SAN LORENZO'},
//   {value: 'ENCARNACION'}
//];
var f= new Array();
f['ITAPUA']=['ENCARNACIÓN','CAMBYRETÁ','SAN PEDRO DEL PARANÁ','TOMÁS ROMERO PEREIRA','EDELIRA','SAN RAFAEL DEL PARANÁ',
    'NATALIO','CORONEL JOSÉ FÉLIX BOGADO','CARLOS ANTONIO LÓPEZ','ALTO VERÁ','ITAPÚA POTY','OBLIGADO',
    'MAYOR JULIO DIONISIO OTAÑO','HOHENAU','CAPITÁN MEZA','BELLA VISTA','YATYTAY','CAPITÁN MIRANDA','GENERAL ARTIGAS',
    'FRAM','SAN COSME Y DAMIÁN','SAN JUAN DEL PARANÁ','TRINIDAD','PIRAPÓ','CARMEN DEL PARANÁ','GENERAL DELGADO',
    'NUEVA ALBORADA','JESÚS','JOSÉ LEANDRO OVIEDO','LA PAZ'];
f['CENTRAL']=['ASUNCIÓN','LUQUE','SAN LORENZO','CAPIATÁ','LAMBARÉ','FERNANDO DE LA MORA','LIMPIO','ÑEMBY',
    'ITAUGUÁ','MARIANO ROQUE ALONSO','VILLA ELISA','ITÁ','AREGUÁ','SAN ANTONIO','JULIÁN AUGUSTO SALDÍVAR','YPANÉ',
    'VILLETA','GUARAMBARÉ','YPACARAÍ','NUEVA ITALIA'];
f['SAN PEDRO']=['AQUINO','LIBERACIÓN','TACUATÍ','YRYBUCUÁ','YATAITY DEL NORTE','LIMA','ITACURUBÍ DEL ROSARIO',
    'VILLA DEL ROSARIO','25 DE DICIEMBRE','UNIÓN','NUEVA GERMANIA','ANTEQUERA','SAN PABLO'];
f['PRESIDENTE HAYES']=['VILLA HAYES','TENIENTE PRIMERO MANUEL IRALA FERNÁNDEZ','BENJAMÍN ACEVAL','PUERTO PINASCO',
    'NANAWA','JOSÉ FALCÓN','TENIENTE ESTEBAN MARTÍNEZ','GENERAL JOSÉ MARÍA BRUGUEZ'];
f['PARAGUARÍ']=['CARAPEGUÁ','YAGUARÓN','YBYCUÍ','PARAGUARÍ','QUIINDY','PIRAYÚ','ACAHAY','MBUYAPEY',
    'SAN ROQUE GONZÁLEZ DE SANTA CRUZ','ESCOBAR','CAAPUCÚ','QUYQUYHÓ','YBYTYMÍ','GENERAL BERNARDINO CABALLERO',
    'SAPUCAI','LA COLMENA','TEBICUARYMÍ'];
f['ÑEEMBUCÚ']=['PILAR','ALBERDI','SAN JUAN BAUTISTA DEL ÑEEMBUCÚ','CERRITO','MAYOR JOSÉ DE JESÚS MARTÍNEZ',
    'GENERAL JOSÉ EDUVIGIS DÍAZ','TACUARAS','VILLA OLIVA','LAURELES','HUMAITÁ','ISLA UMBÚ','VILLALBÍN','GUAZÚ CUÁ',
    'PASO DE PATRIA','DESMOCHADOS','VILLA FRANCA'];
f['MISIONES']=['SAN IGNACIO GUAZÚ','SAN JUAN BAUTISTA','SANTA ROSA DE LIMA','AYOLAS','SANTA MARÍA DE FE','SANTIAGO',
    'SAN MIGUEL','SAN PATRICIO','VILLA FLORIDA','YABEBYRY'];
f['GUAIRÁ']=['VILLARRICA','INDEPENDENCIA','PASO YOBÁI','CAPITÁN MAURICIO JOSÉ TROCHE','BORJA','ITURBE',
    'MBOCAYATY DEL GUAIRÁ','GENERAL EUGENIO ALEJANDRINO GARAY','ITAPÉ','JOSÉ A. FASSARDI','CORONEL MARTÍNEZ',
    'FÉLIX PÉREZ CARDOZO','NATALICIO TALAVERA','YATAITY DEL GUAIRÁ','ÑUMÍ','TEBICUARY','SAN SALVADOR','DOCTOR BOTTRELL'];
f['CORDILLERA']=['CAACUPÉ','TOBATÍ','PIRIBEBUY','ARROYOS Y ESTEROS','EUSEBIO AYALA','EMBOSCADA','ATYRÁ','ALTOS',
    'CARAGUATAY','SAN BERNARDINO','ITACURUBÍ DE LA CORDILLERA','ISLA PUCÚ','VALENZUELA','JUAN DE MENA',
    'PRIMERO DE MARZO','SANTA ELENA','SAN JOSÉ OBRERO','MBOCAYATY DEL YHAGUY','NUEVA COLOMBIA','LOMA GRANDE'];
f['CONCEPCIÓN']=['CONCEPCIÓN','HORQUETA','YBY YAÚ','LORETO','BELÉN','SAN LÁZARO','AZOTEY','SARGENTO JOSÉ FÉLIX LÓPEZ',
    'SAN ALFREDO','PASO BARRETO','SAN CARLOS DEL APA'];
f['CANINDEYÚ']=['CURUGUATY','SALTO DEL GUAIRÁ','YASY CAÑY','VILLA YGATIMÍ','YBYRAROBANÁ','NUEVA ESPERANZA',
    'GENERAL FRANCISCO CABALLERO ÁLVAREZ','YBY PYTÁ','CORPUS CHRISTI','LA PALOMA DEL ESPÍRITU SANTO','KATUETÉ','YPEJHÚ','ITANARÁ'];
f['CAAZAPÁ']=['SAN JUAN NEPOMUCENO','ABAÍ','CAAZAPÁ','YUTY','3 DE MAYO','TAVAÍ','FULGENCIO YEGROS',
    'GENERAL HIGINIO MORÍNIGO','BUENA VISTA','DOCTOR MOISÉS SANTIAGO BERTONI','MACIEL'];
f['CAAGUAZÚ']=['CAAGUAZÚ','CORONEL OVIEDO','DOCTOR JUAN EULOGIO ESTIGARRIBIA','YHÚ','REPATRIACIÓN','DOCTOR JUAN MANUEL FRUTOS',
    'SAN JOSÉ DE LOS ARROYOS','SAN JOAQUÍN','RAÚL ARSENIO OVIEDO','CARAYAÓ','TEMBIAPORÁ','SANTA ROSA DEL MBUTUY',
    'VAQUERÍA','JOSÉ DOMINGO OCAMPOS','TRES DE FEBRERO','R. I. TRES CORRALES','DOCTOR CECILIO BÁEZ',
    'MARISCAL FRANCISCO SOLANO LÓPEZ','SIMÓN BOLÍVAR','LA PASTORA','NUEVA TOLEDO','NUEVA LONDRES'];
f['ALTO PARAGUAY']=['PUERTO CASADO','CAPITÁN CARMELO PERALTA','FUERTE OLIMPO','BAHÍA NEGRA'];
f['AMAMBAY']=['PEDRO JUAN CABALLERO','CAPITÁN BADO','BELLA VISTA NORTE','ZANJA PYTÁ','KARAPAÍ'];
f['ALTO PARANÁ']=['CIUDAD DEL ESTE','PRESIDENTE FRANCO','MINGA GUAZÚ','HERNANDARIAS','ITAKYRY','SANTA RITA',
    'JUAN EMILIO O LEARY','DOCTOR JUAN LEÓN MALLORQUÍN','MINGA PORÁ','SAN ALBERTO','YGUAZÚ','SAN CRISTÓBAL',
    'LOS CEDRALES','ÑACUNDAY','MBARACAYÚ','DOCTOR RAÚL PEÑA','TAVAPY','SANTA ROSA DEL MONDAY','NARANJAL','IRUÑA',
    'DOMINGO MARTÍNEZ DE IRALA','SANTA FE DEL PARANÁ'];
f['BOQUERÓN']=['MARISCAL JOSÉ FÉLIX ESTIGARRIBIA','FILADELFIA','LOMA PLATA'];
//var ciudad_list=[]

$(document).ready(function() {
   $("#ac_provincia, #ac_provincia2").autocomplete({
      lookup: provincia_list,
   });


    //alert(dpto);
    $("#ciudad, #ciudad2").focus(function(){
        var dpto=$("#ac_provincia").val();
        if (dpto==null||dpto==""){
            dpto=$("#ac_provincia2").val();

        }
        $("#ciudad, #ciudad2").autocomplete({
            lookup: f[dpto],
        });


    });

});
