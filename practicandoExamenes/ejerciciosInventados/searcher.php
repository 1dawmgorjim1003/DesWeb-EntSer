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

function createMessage($file, $search) {
    $isFinded = false;
    $output = '';
    if(isset($file)) {
        foreach($file as $rowIndex => $rowData) {
            foreach($rowData as $colIndex => $colData) {
                if ($search == $colData) {
                    $isFinded = true;
                }
            }
        }
    }

    if ($isFinded == true) {
        $output = 'Se ha encontrado la palabra "' . $search . '"';
    } else {
        $output = 'No se ha encontrado la palabra "' . $search .'"';
    }

    return $output;

}



$routeFile = "data/words.csv";
$file = readCSVFile($routeFile);
$search = readInput();
$output = createMessage($file, $search);
//dump($file);

//LÓGICA DE PRESENTACIÓN
function getFileMarkup($file, $search) {
    $output = '';
    if(isset($file)) {
        foreach($file as $rowIndex => $rowData) {
            foreach($rowData as $colIndex => $colData) {
                if(isset($search)) {
                    if($search == $colData) {
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

function getMessageMarkup($output, $search) {
    if ($search !== null) {
        $output = '<p>' . $output . '</p>';
        return $output;
    }
}



$fileMarkup = getFileMarkup($file, $search);
$messageaMarkup = getMessageMarkup($output, $search);
//dump($fileMarkup);
//dump($messages);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
        <input type="text" name="buscar">
        <input type="submit" value="Buscar palabra">
    </form>
    <br>
    <a href="<?=$_SERVER['PHP_SELF']?>">Volver al listado de palabras</a>
    <div>
        <?php echo $fileMarkup;?>
    </div>
    <br>
    <?php echo $messageaMarkup;?>
</body>
</html>