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
        echo 'No se puede leer el archivo ' . $routeFile;
    } else { 
        if (($pointer = fopen($routeFile, 'r')) !== false) {
            while (($row = fgetcsv($pointer)) !== false) {
                if ($row == null || $row == [null]) {
                    continue;
                }
                $data[] = [
                    'nombre' => $row[0],
                    'apellido' => $row[1],
                    'media' => $row[2]
                ];
            }
            fclose($pointer);
        }
        return $data;
    }
}

function readURL() {
    $min = filter_input(INPUT_GET, 'min', FILTER_VALIDATE_FLOAT);
    $ini = filter_input(INPUT_GET, 'ini', FILTER_DEFAULT);

    if ((isset($min) && is_float($min)) || (isset($ini) && is_string($ini))) {
        return [$min, $ini];
    }
    return null;

}

$data = readCSV('data/students.csv');
$urlValues = readURL();
//dump($urlValues);;
//dump($data);

//LÓGICA DE PRESENTACIÓN
function getDataMarkup($data, $urlValues) {
    $output = ''; $media = 0; $count = 0;
    if (isset($data)) {
        foreach($data as $rowIndex => $rowData) {
            if(isset($urlValues[0]) && isset($urlValues[1])) {
                $character = substr($rowData['apellido'], 0 , 1);
                if ((strcasecmp($character, $urlValues[1]) == 0) && ($rowData['media'] >= $urlValues[0])) {
                    $output .= '<p>' . $rowData['nombre'] . ' ' . $rowData['apellido'] . ' ' . $rowData['media'] . '</p>';
                    $media += $rowData['media'];
                    $count += 1;
                    $output .= '<br>'; 
                }
            } else if(isset($urlValues[0])) {
                if ($rowData['media'] >= $urlValues[0]) {
                    $output .= '<p>' . $rowData['nombre'] . ' ' . $rowData['apellido'] . ' ' . $rowData['media'] . '</p>';
                    $media += $rowData['media'];
                    $count += 1;
                    $output .= '<br>';
                }
            } else if(isset($urlValues[1])) {
                $character = substr($rowData['apellido'], 0 , 1);
                if (strcasecmp($character, $urlValues[1]) == 0) {
                    $output .= '<p>' . $rowData['nombre'] . ' ' . $rowData['apellido'] . ' ' . $rowData['media'] . '</p>';
                    $media += $rowData['media'];
                    $count += 1;
                    $output .= '<br>';
                }
            } else {
                $output .= '<p>' . $rowData['nombre'] . ' ' . $rowData['apellido'] . ' ' . $rowData['media'] . '</p>';
                $media += $rowData['media'];
                $count += 1;
                $output .= '<br>';
            }
            
        }
        $mediaMarkup = 0;
        if ($media !== 0 || $count !== 0 ) {
            $mediaMarkup = ($media / $count);   
        }
        return [$output, $mediaMarkup];
    }
}

$dataMarkup = getDataMarkup($data, $urlValues);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Filtrado de alumnos</h1>
    <h4>Se filtran alumnos según inicial del primer apellido y/o según su nota mínima</h4>
    <br>
    <?php 
        echo $dataMarkup[0]; 
        echo '<h4>La media de los alumnos filtrados es de ' . $dataMarkup[1] . '</h4>';
    ?>    
</body>
</html>