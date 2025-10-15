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
    if(!is_readable($routeFile)) {
        echo 'No se ha podido leer el archivo ' . $routeFile;
    } else {
        if (($pointer = fopen($routeFile, 'r')) !== false) {
            while (($row = fgetcsv($pointer)) !== false) {
                if ($row == null || $row == [null]) {
                    continue;
                }
                $data [] = [
                    'nombre' => $row[0],
                    'categoria' => $row[1],
                    'precio' => $row[2]
                ];
            }
            fclose($pointer);
        }
        return $data;
    }
}

function readURL() {
    $cat = filter_input(INPUT_GET, 'cat', FILTER_DEFAULT);
    $min = filter_input(INPUT_GET, 'min', FILTER_VALIDATE_INT);
    $max = filter_input(INPUT_GET, 'max', FILTER_VALIDATE_INT);
    $orden = filter_input(INPUT_GET, 'orden', FILTER_DEFAULT);

    $catValido   = isset($cat) && is_string($cat) && in_array(strtolower($cat), ['electronica','gaming','oficina','hogar']);
    $minValido   = isset($min) && is_numeric($min);
    $maxValido   = isset($max) && is_numeric($max);
    $ordenValido = isset($orden) && is_string($orden) && in_array(strtolower($orden), ['nombre','precio']);

    if ($catValido || $minValido || $maxValido || $ordenValido) {
        return [$cat, $min, $max, $orden];
    }
    return null;

}

//ORDENAR UN ARRAY SEGÚN VALORES
function orderData($data, $urlValues) {
    if (isset($urlValues[3])) {
        if ($urlValues[3] === 'precio') {
            usort($data, function($a, $b) {
                return $a['precio'] <=> $b['precio'];
            });
        } elseif ($urlValues[3] === 'nombre') {
            usort($data, function($a, $b) {
                return strcasecmp($a['nombre'], $b['nombre']);
            });
        }
    }
    return $data;
}

$data = readCSV('data/catalog.csv');
$urlValues = readURL();
$sortedData = orderData($data, $urlValues);
//dump($urlValues);
//LÓGICA DE PRESENTACIÓN

function getDataMarkup($sortedData, $urlValues) {
    $output = '';
    foreach($sortedData as $rowIndex => $rowData) {
        if (isset($urlValues[0]) && isset($urlValues[1]) && isset($urlValues[2])) {
            if (($urlValues[0] == $rowData['categoria']) && ($rowData['precio'] >= $urlValues[1]) && ($rowData['precio'] <= $urlValues[2])) {
                $output .= '<p>Producto: ' . $rowData['nombre'] . ' || Categoría: ' .  $rowData['categoria'] . ' || Precio: ' . $rowData['precio'] . '</p>';
                $output .= '<br>';
            }
        } else if (isset($urlValues[0]) && isset($urlValues[1])) {
            if (($urlValues[0] == $rowData['categoria']) && ($rowData['precio'] >= $urlValues[1])) {
                $output .= '<p>Producto: ' . $rowData['nombre'] . ' || Categoría: ' .  $rowData['categoria'] . ' || Precio: ' . $rowData['precio'] . '</p>';
                $output .= '<br>';
            }
        } else if (isset($urlValues[0])) {
            if (($urlValues[0] == $rowData['categoria'])) {
                $output .= '<p>Producto: ' . $rowData['nombre'] . ' || Categoría: ' .  $rowData['categoria'] . ' || Precio: ' . $rowData['precio'] . '</p>';
                $output .= '<br>';
            }
        } else if (isset($urlValues[1])) {
            if ($rowData['precio'] >= $urlValues[1]) {
                $output .= '<p>Producto: ' . $rowData['nombre'] . ' || Categoría: ' .  $rowData['categoria'] . ' || Precio: ' . $rowData['precio'] . '</p>';
                $output .= '<br>';
            } 
        } else if (isset($urlValues[2])) {
            if ($rowData['precio'] <= $urlValues[2]) {
                $output .= '<p>Producto: ' . $rowData['nombre'] . ' || Categoría: ' .  $rowData['categoria'] . ' || Precio: ' . $rowData['precio'] . '</p>';
                $output .= '<br>';
            }
        } else {
            $output .= '<p>Producto: ' . $rowData['nombre'] . ' || Categoría: ' .  $rowData['categoria'] . ' || Precio: ' . $rowData['precio'] . '</p>';
            $output .= '<br>';
        }
    }
    return $output;
}

$dataMarkup = getDataMarkup($sortedData, $urlValues);
//dump($dataMarkup);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Catálogo</h1>
    <h4>Buscador de catálogo</h4>
    <br>
    <?php
        echo $dataMarkup;
    ?>
</body>
</html>