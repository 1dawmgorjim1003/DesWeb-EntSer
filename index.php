<?php

//INICIALIZACIÓN DEL ENTORNO
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<<<<<<< HEAD
/*Zona de declaración de funciones */
//FUNCIÓN DE DEBUGUEO
function dump($var){
    echo '<pre>'.print_r($var,1).'</pre>';
}

//LÓGICA DE NEGOCIO
function cargarTablero($rutaCSV) {
    $tablero = [];
    if (!is_readable($rutaCSV)) {
        echo 'No se puede leer el fichero CSV: ' . $rutaCSV;
    } else {
        if (($puntero = fopen($rutaCSV, 'r')) !== false) {
            while (($fila = fgetcsv($puntero)) !== false) {
                if ($fila === null || $fila === [null]) { continue; }
                $tablero[] = $fila;
            }
            fclose($puntero);
        }
        return $tablero;
    }
}

$archivoCSV = __DIR__ . '/data/tablero.csv';
$tablero = cargarTablero($archivoCSV);

function leerInput(){
    
    $col = filter_input(INPUT_GET, 'col', FILTER_VALIDATE_INT);
    $row = filter_input(INPUT_GET, 'row', FILTER_VALIDATE_INT);

    return ((isset($col) && is_int($col)) && (isset($row)) && is_int($row))? array(
            'row' => $row,
            'col' => $col
        ) : null;    

    // dump($row);
}


//LÓGICA DE PRESENTACIÓN
function getTableroMarkup ($tablero, $posPersonaje){
    $output = '';
    foreach ($tablero as $filaIndex => $datosFila) {
        foreach ($datosFila as $columnaIndex => $tileType) {
            if(isset($posPersonaje)&&($filaIndex == $posPersonaje['row'])&&($columnaIndex == $posPersonaje['col'])){
                $output .= '<div class = "tile ' . $tileType . '"><img src="src/character.png"></div>';    
            }else{
                $output .= '<div class = "tile ' . $tileType . '"></div>';
            }
        }
    }
    return $output;
}
=======
include('lib/functions.php');
>>>>>>> 26f53a54c9cd81c99d848f71af3ac2ff26268430

//LOGICA DE NEGOCIO
$posPersonaje = leerInput();
$arrows = getArrows($posPersonaje);
$tablero = cargarTablero('data/tablero.csv');


<<<<<<< HEAD
function displayError() {
    $output;
    if (leerInput() == null) {
        $output = '<p>El personaje no ha podido cargarse correctamente. Se han dado posiciones inválidas o no se han indicado.</p>';
    } else {
        $output = '<p>El personaje se ha podido cargar correctamente.</p>';
    }
    return $output;
}

function getArrowsMarkup($posPersonaje) {
    $arriba = '?row=' . ($posPersonaje['row']-1) . '&col=' . $posPersonaje['col'];
    $abajo = '?row=' . ($posPersonaje['row']+1) . '&col=' . $posPersonaje['col'];
    $derecha = '?row=' . ($posPersonaje['row']) . '&col=' . ($posPersonaje['col']-1);
    $izquierda = '?row=' . ($posPersonaje['row']) . '&col=' . ($posPersonaje['col']+1);

    $output = '
        <a href="' . $arriba . '">Arriba</a>
        <a href="' . $abajo . '">Abajo</a>
        <a href="' . $izquierda . '">Izquierda</a>
        <a href="' . $derecha . '">Derecha</a>';
    return $output;
    
}

//Lógica de negocio
//El tablero es un array bidimensional en el que cada fila contiene 12 palabras cuyos valores pueden ser:
// agua
// fuego
// tierra
// hierba

//Lógica de presentación

=======
//LOGICA DE PRESENTACIÓN
$tableroMarkup = getTableroMarkup($tablero, $posPersonaje);
$arrowsMarkup = getArrowsMarkup($arrows);
>>>>>>> 26f53a54c9cd81c99d848f71af3ac2ff26268430

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Minified version -->
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <style>
        .contenedorTablero{
            width: 604px;
            height: 604px;
            border-radius: 5px;
            border: solid 2px grey;
            box-shadow: grey;
            /* Propiedades CSS para crear un tablero 12 x 12 */
            display: grid;
            grid-template-columns: repeat(12, auto);
            grid-template-rows: repeat(12, auto);
        }
        .tile{
            width: 50px;
            height: 50px;
            margin:0;
            padding:0;
            border-width:0;
            /*Insertar imagen y ajustar el tamaño a nuestro gusto*/
            background-image: url("./src/464.jpg");
            background-size: 209px;
        }

        /*Background-position para ir buscando los cuadrados de nuestro tablero*/
        .fuego{
            background-position: -105px -52px;
        }
        .agua{
            background-position: -53px 0px;
        }
        .tierra{
            background-position: -157px 0px;
        }
        .hierba{
            background-position: 0px 0px;
        }
    </style>
</head>
<body>
    <h1>Tablero juego super rol DWES</h1>
    <div style="display: flex;">
        <?php echo $arrowsMarkup; ?>
    </div>
    <div class="contenedorTablero">
        <?php echo $tableroMarkup;?>
    </div>
    <?php echo getArrowsMarkup($posPersonaje) ?>
    <?php echo displayError() ?>
</body>
</html>