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
function readCSV($routeFile) {
    $data = [];
    if (!is_readable($routeFile)) {
        echo 'No se ha podido leer el archivo ' . $routeFile;
    } else {
        if (($pointer = fopen($routeFile, 'r')) !== false) {
            while (($row = fgetcsv($pointer)) !== false) {
                if ($row == null || $row == [$row]) {continue ;}
                $data[] = $row;
            }
            fclose($pointer);
        }
        return $data;
    }
}

function readURL() {
    $row = filter_input(INPUT_GET, 'row', FILTER_VALIDATE_INT);
    $col = filter_input(INPUT_GET, 'col', FILTER_VALIDATE_INT);

    if ((isset($row) && is_numeric($row)) && (isset($col) && is_numeric($col))) {
        return [
            'row' => $row,
            'col' => $col
        ];
    }
    return null;
}

function getMessages($urlValues) {
    $output = '';
    if (isset($urlValues) && $urlValues['row'] >= 0 && $urlValues['row'] < 12 && $urlValues['col'] >= 0 && $urlValues['col'] < 12 ) {
        $output = 'El personaje se ha imprimido correctamente.';
    } else if ($urlValues == null) {
        $output = 'Se ha especificado valores inapropiados para el personaje o no se han especificado.';
    } else if ((isset($urlValues)) && ($urlValues['row'] > 11 || $urlValues['row'] < 0) || ($urlValues['col'] > 11 || $urlValues['col'] < 0)) {
        $output = 'Se han especificado posiciones para el personaje fuera del límite de la tabla';
    }
    return $output;
}

function getArrows($urlValues) {
    $arrows = [];
    if (isset($urlValues)) {
        $arrows = [
            'izquierda' => [
                'row' => $urlValues['row'],
                'col' => $urlValues['col'] - 1,
            ],
            'derecha' => [
                'row' => $urlValues['row'],
                'col' => $urlValues['col'] + 1,
            ],
            'arriba' => [
                'row' => $urlValues['row'] - 1,
                'col' => $urlValues['col'],
            ],
            'abajo' => [
                'row' => $urlValues['row'] + 1,
                'col' => $urlValues['col'],
            ],
        ];
        return $arrows;
    }
    return null;
}

$data = readCSV('../data/tablero.csv');
$urlValues = readURL();
$messages = getMessages($urlValues);
$arrows = getArrows($urlValues);
//dump($arrows);
//dump($data);

//LÓGICA DE PRESENTACIÓN 
function getDataMarkup($data, $urlValues) {
    $output = '';
    foreach($data as $rowIndex => $rowData) {
        foreach($rowData as $colIndex => $colData) {
            $output .= '<div class="tile ' . $colData . '">';
            if (isset($urlValues)) {
                if (($urlValues['row'] == $rowIndex && $urlValues['row'] >= 0 && $urlValues['row'] < 12) && ($urlValues['col'] == $colIndex && $urlValues['col'] >= 0 && $urlValues['col'] < 12)) {
                    $output .= '<img src="../src/character2.png" width="50px" height="50px"/>';
                }
            }
            $output .= '</div>';
        }
    }
    return $output;
}

function getMessagesMarkup($messages) {
    return '<p>' . $messages . '</p>';
}

function getArrowsMarkup($arrows) {
    $output = '';
    if (isset($arrows)) {
        foreach($arrows as $i => $j) {
            $output .= '<a href="?row=' . $j['row'] . '&col=' . $j['col'] . '">' . $i . '</a> || ';
        }
        return $output;
    }
}

$dataMarkup = getDataMarkup($data, $urlValues);
$messagesMarkup = getMessagesMarkup($messages);
$arrowsMarkup = getArrowsMarkup($arrows);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            background-image: url("../src/464.jpg");
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
    <h1>Tablero</h1>
    <?php echo $arrowsMarkup; ?>
    <p></p>
    <br>
    <div class="contenedorTablero">
        <?php
            echo $dataMarkup;
        ?>
    </div>
    <?php echo $messagesMarkup;?>
</body>
</html>