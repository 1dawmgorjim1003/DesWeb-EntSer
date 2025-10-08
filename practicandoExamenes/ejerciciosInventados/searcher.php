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
function readCSVFile($routeFile) {
    $table = [];
    if (!is_readable($routeFile)) {
        echo 'No se puede leer el archivo ' . $routeFile;
    } else {
        if(($pointer = fopen($routeFile, 'r')) !== false)  {
            while (($row = fgetcsv($pointer)) !== false) {
                if ($row === null | $row === [null]) {continue; }
                $table[] = $row;
            }
            fclose($pointer);
        }
    }
    return $table;
}

function readInput() {
    $search = filter_input(INPUT_GET, 'buscar');
    if(isset($search) && is_string($search)) {
        return $search;
    }
    return null;
}

function createMessages($fileMarkup, $search) {
    $output = '';
    if(isset($fileMarkup)) {
        $output = 'No se encontraron resultados para "' . $search . '"';
        return $output;
    }
}

$routeFile = "data/words.csv";
$file = readCSVFile($routeFile);
$search = readInput();
//dump($file);

//LÓGICA DE PRESENTACIÓN
function getFileMarkup($file, $search) {
    $output = '';
    if(isset($file)) {
        foreach($file as $rowIndex => $rowData) {
            foreach($rowData as $colIndex => $colData) {
                if(isset($search)) {
                    if($colData == $search) {
                        $output .= '<p>' . $colData . '</p>';
                    }
                } else {
                    $output .= '<p>' . $colData . '</p>';
                }
            }
        }
    }
    return $output;
}

function getMessagesMarkup($messages, $fileMarkup) {
    $output = '';
    if(isset($fileMarkup)) {
        $output = '<p>' . $messages . '</p>';
        return $output;
    }
    $output = 'OK';
    return $output;
}

$fileMarkup = getFileMarkup($file, $search);
//dump($fileMarkup);
$messages = createMessages($fileMarkup, $search);
//dump($messages);
$messagesMarkup = getMessagesMarkup($messages, $fileMarkup);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <?php echo $fileMarkup;?>
    </div>
    <br>
    <?php echo $messagesMarkup;?>
</body>
</html>