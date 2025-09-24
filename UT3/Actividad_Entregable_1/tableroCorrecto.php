<?php

/* Inicialización del entorno */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* Zona de declaración de funciones */
//Funciones de debugueo
function dump($var){
    echo '<pre>'.print_r($var,1).'</pre>';
}

//LÓGICA DE NEGOCIO
function cargarTablero($rutaCSV) {
    $tablero = [];
    // Se comprueba si el archivo existe y tiene permisos de lectura
    if (!is_readable($rutaCSV)) {
        echo 'No se puede leer el fichero CSV: ' . $rutaCSV;
    } else {
        // Se abre el fichero en modo lectura (fopen y r de read)
        if (($puntero = fopen($rutaCSV, 'r')) !== false) {
            // Se lee el fichero línea a línea. Cada línea es un array, se separa por comas
            /* El separador por defecto es la coma, si lo quiero cambiar es fgetcsv($puntero, 0, ";"); El 0 
            indica que la longitud de la linea es ilimitada */ 
            while (($fila = fgetcsv($puntero)) !== false) {
                // Ignora líneas vacías, si esta vacía se pasa a la siguiente línea
                if ($fila === null || $fila === [null]) { continue; }
                // Vamos añadiendo la fila leída a $tablero
                $tablero[] = $fila;
            }
            // Cerramos la lectura del fichero
            fclose($puntero);
        }
        return $tablero;
    }
}

$archivoCSV = __DIR__ . '/tablero.csv';
$tablero = cargarTablero($archivoCSV);

//Función lógica presentación
function getTableroMarkup($tableroData){
    $output = '';
    // Recorre cada posición I del array de arrays
    foreach($tableroData as $filaIndex => $datosFila){
        // Recorre cada posición J del array de arrays
        foreach($datosFila as $columnaIndex => $tileType){
            $output .= '<div class="tile '.$tileType.'"></div>';
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
$tableroMarkup = getTableroMArkup($tablero);


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
            width:604px;
            height: 604px;
            border-radius: 5px;
            border: solid 2px grey;
            box-shadow: grey;
        }
        .tile{
            width: 50px;
            height: 50px;
            float:left;
            margin:0;
            padding:0;
            border-width:0;
        }
        .fuego{
            background-image: url("./tiles/fuego.png");
        }
        .hielo{
            background-image: url("./tiles/hielo.png");
        }
        .piedra{
            background-image: url("./tiles/piedra.png");
        }
        .musgo{
            background-image: url("./tiles/musgo.png");
        }
    </style>
</head>
<body>
    <h1>Tablero juego super rol DWES</h1>
    <div class="contenedorTablero">
        <?php echo $tableroMarkup; ?>
    </div>
</body>
</html>