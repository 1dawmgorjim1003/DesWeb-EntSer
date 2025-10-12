<?php
/* Zona de declaración de funciones */
//*******Funciones de debugueo****
function dump($var){
    echo '<pre>'.print_r($var,1).'</pre>';
}


//LÓGICA DE NEGOCIO
function leerArchivoCSV($rutaArchivoCSV) {
    $tablero = [];

    if (($puntero = fopen($rutaArchivoCSV, "r")) !== FALSE) {
        while (($datosFila = fgetcsv($puntero)) !== FALSE) {
            $tablero[] = $datosFila;
        }
        fclose($puntero);
    }

    return $tablero;
}

/*function procesaRedirect(){
    if((!isset($_GET['col']))&&(!isset($_GET['row']))){
        header("HTTP/1.1 308 Permanent Redirect");
        header('Location: ./index.php?row=0&col=0');
    }
}*/

function procesarInput(){
    
    $posPersonajeActual = filter_input(INPUT_POST, 'pos_personaje', FILTER_DEFAULT);
    
    
    $posPersonajeActual = isset($posPersonajeActual)?unserialize(base64_decode($posPersonajeActual)):array(
        'row' => 0,
        'col' => 0,
    );
    
    $col = $posPersonajeActual['col'];
    $row = $posPersonajeActual['row'];

    

    $direccion = filter_input(INPUT_POST, 'direccion', FILTER_DEFAULT);
    
    if(isset($direccion)){
        switch($direccion){
            case 'arriba':
                $posPersonajeActual['row']--;
            break;
            case 'abajo':
                $posPersonajeActual['row']++;
            break;
            case 'derecha':
                $posPersonajeActual['col']++;
           break;
           case 'izquierda':
                $posPersonajeActual['col']--;
            break;    
        }
    }
    return $posPersonajeActual;
}

function getMensajes(&$posPersonaje){
    if(!isset($posPersonaje)){
        return array('La posición del personaje no está bien definida');
    }
    return array('');
}

/*function getArrows($posPersonaje){
    if(isset($posPersonaje)){

        $arrows = array(
            'izquierda' => array(
                'row' => $posPersonaje['row'],
                'col' => $posPersonaje['col'] -1,
            ),
            'arriba' => array(
                'row' => $posPersonaje['row'] -1,
                'col' => $posPersonaje['col'] ,
            ),
            'derecha' => array(
                'row' => $posPersonaje['row'],
                'col' => $posPersonaje['col'] +1,
            ),
            'abajo' => array(
                'row' => $posPersonaje['row'] +1,
                'col' => $posPersonaje['col'],
            ),
        );
        return $arrows;
    }
    return null;

}*/

//LÓGICA DE PRESENTACIÓN
function getTableroMarkup ($tablero, $posPersonaje){
    $output = '';
    foreach ($tablero as $filaIndex => $datosFila) {
        foreach ($datosFila as $columnaIndex => $tileType) {
            if(isset($posPersonaje)&&($filaIndex == $posPersonaje['row'])&&($columnaIndex == $posPersonaje['col'])){
                $output .= '<div class = "tile ' . $tileType . '"><img src="./src/character.png"></div>';    
            }else{
                $output .= '<div class = "tile ' . $tileType . '"></div>';
            }
        }
    }
    return $output;
}
function getMensajesMarkup($arrayMensajes){
    $output = '';
    foreach ($arrayMensajes as $mensaje){
        $output .= '<p>'.$mensaje.'</p>';
    }
    return $output;
    
}
function getFormMarkup($posPersonaje){
    
    $output = '<form action="'.$_SERVER['PHP_SELF'].'" method="post">
        <input type="submit" name="direccion" value="arriba">
        <input type="submit" name="direccion" value="izquierda">
        <input type="submit" name="direccion" value="derecha">
        <input type="submit" name="direccion" value="abajo">';
    if(isset($posPersonaje)){
        $output .= '<input type="hidden" name="pos_personaje" value="'.base64_encode(serialize($posPersonaje)).'">';
        //$output .= '<input type="hidden" name="col" value="'.$posPersonaje['col'].'">
        //<input type="hidden" name="row" value="'.$posPersonaje['row'].'">';
    }
    $output.='</form>';
    
    return $output;   
}

