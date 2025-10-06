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

//Creamos una función que lea las query string de la URL
function readURL() {
    //Obtenemos los valores de row y col de manera correcta, con filter_input()
    $row = filter_input(INPUT_GET, 'row', FILTER_VALIDATE_INT);
    $col = filter_input(INPUT_GET, 'col', FILTER_VALIDATE_INT);
    //Si $row y $col existen y ambos son numéricos, se devuelve un array con los valores.
    //Si no es así, se devuelve null
    if (isset($row) && is_numeric($row) && isset($col) && is_numeric($col)) {
        return [
            'row' => $row,
            'col' => $col,
        ];
    }
    return null;
}

//Creamos una función para definir los mensajes de éxito / error
function getMessages() {
    //Declaramos y creamos un array con todos los mensajes.
    $messagesArray = [
        'No se han especificado posiciones para el personaje o no son números.', 
        'El personaje se ha cargado exitosamente.', 
        'Se han especificado posiciones de personaje fuera del tablero.'];
    return $messagesArray;
}

//Creamos una función donde especificar la lógica de negocio de las flechas según el tipo de flecha
function getArrows($positionCharacter) {
    //Si las posiciones del personaje existen...
    if (isset($positionCharacter)) {
        //Se declara y se crea un array con los movimientos
        $arrows = [
            'izquierda' => [
                'row' => $positionCharacter['row'],
                'col' => $positionCharacter['col'] - 1,
            ],
            'derecha' => [
                'row' => $positionCharacter['row'],
                'col' => $positionCharacter['col'] + 1,
            ],
            'abajo' => [
                'row' => $positionCharacter['row'] + 1,
                'col' => $positionCharacter['col'],
            ],
            'arriba' => [
                'row' => $positionCharacter['row'] - 1,
                'col' => $positionCharacter['col'],
            ],
        ];
        return $arrows;
    }
    return null;
}


//Cargamos las distintas funciones en variables.
$positionCharacter = readURL();
$messages = getMessages();
$arrows = getArrows($positionCharacter);
//dump($positionCharacter);
//Cargamos la función con la lógica para usarla en la presentación
$table = loadTable('../data/tablero.csv');



//LOGICA DE PRESENTACIÓN
function getTableMarkup($table, $positionCharacter) {
    $output = '';
    //Se lee el array. Primero se lee la fila
    foreach($table as $rowIndex => $rowData) {
        //Se lee cada posición de columna
        foreach($rowData as $colIndex => $colData) {
            //Se va pintando
            $output .= '<div class="tile ' . $colData . '">';
            //Si $positionCharacter existe...
            if (isset($positionCharacter)) {
                //Si el valor de I y el valor de J son iguales a sus valores I y J, y los valores de posición del personaje no se 
                // salen de los límites del tablero se pinta el personaje
                if ($rowIndex == $positionCharacter['row'] && $positionCharacter['row'] >= 0 && $positionCharacter['row'] < 12 && $colIndex == $positionCharacter['col'] 
                && $positionCharacter['col'] >= 0 && $positionCharacter['col'] < 12) {
                $output .= '<img src="../src/character2.png" witdh="50px" height="50px">';
                }
            } 
            $output .= '</div>';
        }
    }
    return $output;
}

//Creamos una función para pintar los mensajes
function getMessagesMarkup($positionCharacter, $messages) {
    $output = '';
    //Si la posición del personaje es nula...
    if ($positionCharacter == null) {
        //Pintamos el mensaje número 1
        $output = $messages[0];
    } else {
        //Si la posición del personaje coincide con los valores máximos de la tabla...
        if ($positionCharacter['row'] >= 0 && $positionCharacter['row'] < 12 && $positionCharacter['col'] >= 0 && $positionCharacter['col'] < 12 ) {
            //Se pinta el mensaje 2
            $output = $messages[1];
        } else {
            //Si no se cumple ninguna de las condiciones anteriores, se pinta el mensaje 3
            $output = $messages[2];
        }
    }
    return $output;
}

//Creamos una función donde se pinte la lógica de flechas
function getArrowsMarkup($arrows) {
    $output = '';
    //Si la lógica existe...
    if(isset($arrows)) {
        //Se recorre el I (izquierda, derecha...) y luego se recorre J (row, col)
        foreach ($arrows as $arrayI => $arrayJ) {
            $output .= '<p><a href="ejercicio3.php?row=' . $arrayJ['row']. '&col=' . $arrayJ['col'] . '">' . $arrayI . '</a></p>';
        }
        return $output;
    }
}

//Cargamos la función en una variable para pintarla con un echo más tarde
$tableMarkup = getTableMarkup($table, $positionCharacter);
$messageMarkup = getMessagesMarkup($positionCharacter, $messages);
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
    <?php echo $arrowsMarkup; ?>
    <div class="contenedorTablero">
        <?php echo $tableMarkup;?>
    </div>
    <?php echo $messageMarkup;?>
</body>
</html>