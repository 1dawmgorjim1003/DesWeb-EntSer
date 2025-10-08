<?php

/*INICIALIZACIÓN DE ENTORNO */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*Zona de declaración de funciones */
//FUNCIÓN DE DEBUGUEO
function dump($var){
    echo '<pre>'.print_r($var,1).'</pre>';
}

//LÓGICA DE NEGOCIO
function processURL() {
    if (count($_GET) == 0) {
        header('Location: index.php?col=0&row=0');
        exit();
    }
}

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

$archivoCSV = __DIR__ . '../../data/tablero.csv';
$tablero = cargarTablero($archivoCSV);

function leerInput(){
    $col = filter_input(INPUT_GET, 'col', FILTER_VALIDATE_INT);
    $row = filter_input(INPUT_GET, 'row', FILTER_VALIDATE_INT);

    return ((isset($col) && is_numeric($col)) && (isset($row)) && is_numeric($row))? array(
            'row' => $row,
            'col' => $col
        ) : null;    

}

$posPersonaje = leerInput();

function getArrows($posPersonaje) {
    if(isset($posPersonaje)) {
        $arrows = array(
            'arriba' => array(
                'col' => $posPersonaje['col'],
                'row'=> $posPersonaje['row'] - 1,
            ),
            'abajo' => array(
                'col' => $posPersonaje['col'],
                'row' => $posPersonaje['row'] + 1,
            ),
            'izquierda' => array(
                'col'=> $posPersonaje['col'] - 1,
                'row' => $posPersonaje['row'],
            ),
            'derecha' => array(
                'col' => $posPersonaje['col'] + 1,
                'row' => $posPersonaje['row'],
            ),
        );
        return $arrows;
    }
    return null;
}

//LÓGICA DE PRESENTACIÓN
function getTableroMarkup ($tablero, $posPersonaje){
    $output = '';
    foreach ($tablero as $filaIndex => $datosFila) {
        foreach ($datosFila as $columnaIndex => $tileType) {
            if(isset($posPersonaje)&&($filaIndex == $posPersonaje['row'])&&($columnaIndex == $posPersonaje['col'])){
                if ($posPersonaje['row'] >= 0 && $posPersonaje['row'] < 12 && $posPersonaje['col'] >= 0 && $posPersonaje['col'] < 12) {
                    $output .= '<div class = "tile ' . $tileType . '"><img src="src/character.png"></div>';    
                }
            }else{
                $output .= '<div class = "tile ' . $tileType . '"></div>';
            }
        }
    }
    return $output;
}

$tableroMarkup = getTableroMArkup($tablero, $posPersonaje);

function displayError() {
    $output;
    global $posPersonaje;
    if (leerInput() == null) {
        $output = '<p>El personaje no ha podido cargarse correctamente. No se han indicado posiciones.</p>';
    } else {
        if ($posPersonaje['row'] >= 0 && $posPersonaje['row'] < 12 && $posPersonaje['col'] >= 0 && $posPersonaje['col'] < 12) {
            $output = '<p>El personaje se ha podido cargar correctamente.</p>';
        } else {
            $output = '<p>El personaje no ha podido cargarse correctamente. Se han dado posiciones inválidas.</p>';
        }
        
    }
    return $output;
}

function getArrowsMarkup($arrows) {
    $output = '';
    if (isset($arrows)) {
        foreach ($arrows as $arrayI => $arrayJ) {
            $output .= '<p><a href="index.php?col='.$arrayJ['col'].'&row='.$arrayJ['row'].'">'.$arrayI.'</a></p><p>// </p>';
        }
    }
    return $output;
}

//Lógica de negocio
//El tablero es un array bidimensional en el que cada fila contiene 12 palabras cuyos valores pueden ser:
// agua
// fuego
// tierra
// hierba

//Lógica de presentación


?>