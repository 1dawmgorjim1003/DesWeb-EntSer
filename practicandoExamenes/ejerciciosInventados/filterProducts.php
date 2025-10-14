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
        echo 'No se puede leer el archivo CSV';
    } else {
        if (($pointer = fopen($routeFile, 'r')) !== false) {
            while (($row = fgetcsv($pointer)) !== false) {
                if ($row === null || $row === [null]) { continue; }
                $data[] = [
                    'nombre' => $row[0],
                    'precio' => $row[1]
                ];
            }
            fclose($pointer);
        }
        return $data;
    }
}

function readURL() {
    $max = filter_input(INPUT_GET, 'max', FILTER_VALIDATE_INT);

    if (isset($max) && is_numeric($max)) {
        return $max;
    }
    return null;
}

function getMessages($max) {
    $output = [];
    if ($max != null) {
        $output[0] = 'No hay produtos con un precio igual o menor a ' . $max . '€.';
        return $output;
    }
    return null;
    
}

$data = readCSV('data/products.csv');
$max = readURL();
$messages = getMessages($max);
//dump($messages);
//dump($data);

//LÓGICA DE PRESENTACIÓN
function getDataMarkup($data, $max) {
    $output = ""; $count = 0;
    foreach($data as $rowIndex => $rowData) {
        if (isset($max)) {
            if ($rowData['precio'] <= $max) {
                $output .= '<p>Producto: ' . $rowData['nombre'] . ' // Precio: ' . $rowData['precio']. '</p>';
                $output .= '<br>';
                $count ++;        
            }
        } else {
            $output .= '<p>Producto: ' . $rowData['nombre'] . ' // Precio: ' . $rowData['precio']. '</p>';
            $output .= '<br>';
        }
        
    }
    return [$output, $count];
}

function getMessagesMarkup($dataMarkup, $messages, $max) {
    $output = '';
    if (empty($dataMarkup[0]) && isset($messages)) {
        $output = '<p>' . $messages[0] . '</p>';
    } else if (isset($max)) {
        $output = '<p>Se han encontrado ' . $dataMarkup[1] . ' productos.</p>';
    }
    return $output;
}

$dataMarkup = getDataMarkup($data, $max);
$messagesMarkup = getMessagesMarkup($dataMarkup, $messages, $max);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <h1>Filtrado de productos</h1>
    <?php 
        echo $dataMarkup[0];
        echo $messagesMarkup; 
    ?>
</body>
</html>