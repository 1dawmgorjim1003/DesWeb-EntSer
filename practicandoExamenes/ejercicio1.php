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

//LOGICA DE NEGOCIO
function loadTable($routeFile) {
    $table = [];
    //Se comprueba si el archivo existe y se puede leer
    if(!is_readable($routeFile)) {
        echo 'No se puede leer el archivo CSV';
    } else {
        //Se comprueba si el archivo se puede abrir correctamente...
        if (($pointer = fopen($routeFile, 'r')) !== false) {
            //Se lee el archivo línea por línea
            while (($row = fgetcsv($pointer)) !== false) {
                //Si la línea está vacía se salta
                if ($row === null || $row === [null]) { continue; }
                //Se añade cada línea leída al array
                $table[] = $row;
            }
            fclose($pointer);
        }
        return $table;
    }
}
//Cargamos la función con la lógica para usarla en la presentación
$table = loadTable('../data/tablero.csv');

//LOGICA DE PRESENTACIÓN
function getTableMarkup($table) {
    $output = '';
    //Se lee el array. Primero se lee la fila
    foreach($table as $rowIndex => $rowData) {
        //Se lee cada posición de columna
        foreach($rowData as $colIndex => $colData) {
            //Se va pintando
            $output .= '<div class="tile ' . $colData . '"></div>';
        }
    }
    return $output;
}

//Cargamos la función en una variable para pintarla con un echo más tarde
$tableMarkup = getTableMarkup($table);
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
    <div class="contenedorTablero">
        <?php echo $tableMarkup;?>
    </div>
</body>
</html>