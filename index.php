<?php

/***** Inicialización del entorno ******/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('lib/functions.php');


/***** Lógica de negocio ******/
//procesaRedirect();
$posPersonaje = procesarInput();
dump($posPersonaje);

//$arrows = getArrows($posPersonaje);

$tablero = leerArchivoCSV('data/tablero.csv');
//$mensajes =  getMensajes($posPersonaje);


//*****Lógica de presentación*******
$tableroMarkup = getTableroMarkup($tablero, $posPersonaje);
//$mensajesUsuarioMarkup = getMensajesMarkup($mensajes);
$formMarkup = getFormMarkup($posPersonaje);

include_once('templates/index.tpl.php');

?>