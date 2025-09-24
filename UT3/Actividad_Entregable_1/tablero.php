<?php
//INICIALIZACIÓN ENTORNO
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//FUNCIÓN DE DEBUGUEO
function dump($var){
    echo '<pre>'.print_r($var,1).'</pre>';
}

//LÓGICA DE NEGOCIO
function getImagenes() {
    $imagenes;
    $option = 1;
    switch ($option) {
        case 1:
            $imagenes = array("./tiles/musgo.png", "./tiles/piedra.png", "./tiles/fuego.png", "./tiles/hielo.png");
            break;
        case 2:
            $imagenes = array("./tiles/hielo.png", "./tiles/fuego.png", "./tiles/piedra.png", "./tiles/musgo.png");
            break;
    }
    return $imagenes;
}

//LOGICA DE PRESENTACIÓN
function createTable($imagenes) {
    $output = '<table>';
    $cont = 0;
    for ($i = 0; $i < 12; $i++) {
        $output .= '<tr>';
        for ($j = 0; $j < 12 ; $j++) {
            $output .= '<td><img src="'. $imagenes[$cont] . '" width="50px" height="50px"/></td>';
            $cont++;
            if ($cont == count($imagenes)) {
                $cont = 0;
            }
        }
        $output .= '</tr>';
    }
    $output .= '</table>';
    return $output;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            text-align: center;
        }

        table {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <h1>Actividad entregable 1</h1>
    <p>El objetivo es implementar un script php que muestre en pantalla un tablero a partir de los 'tiles' proporcionados como recurso. Debes crear una estructura de datos (array) en PHP que sirva para dar 'soporte lógico' al propio tablero, y posteriormente generar el marcado HTML correspondiente, a partir de esa estructura de datos. Sigue las instrucciones proporcionadas en clase como guía.</p>
    <?php echo createTable(getImagenes()); ?>
</body>
</html>