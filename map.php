<?PHP
/*
map.php
Displays a map of the World color-coded to correspond with hit data
*/

$svg = file_get_contents('map.svg');

$colors = array();

$color1 = (array_key_exists('color1', $_GET)) ? ($_GET['color1']) : ("660099");
$color2 = (array_key_exists('color2', $_GET)) ? ($_GET['color2']) : ("0000FF");
$color3 = (array_key_exists('color3', $_GET)) ? ($_GET['color3']) : ("00FF00");
$color4 = (array_key_exists('color4', $_GET)) ? ($_GET['color4']) : ("FFFF00");
$color5 = (array_key_exists('color5', $_GET)) ? ($_GET['color5']) : ("FF0000");

$cutoff1 = (array_key_exists('cutoff1', $_GET)) ? ($_GET['cutoff1']) : ("2");
$cutoff2 = (array_key_exists('cutoff2', $_GET)) ? ($_GET['cutoff2']) : ("5");
$cutoff3 = (array_key_exists('cutoff3', $_GET)) ? ($_GET['cutoff3']) : ("10");
$cutoff4 = (array_key_exists('cutoff4', $_GET)) ? ($_GET['cutoff4']) : ("20");


foreach ($_GET as $key => $value) {
    $key = strtolower($key);
    if(strlen($key) == 2){
        if($value >= $cutoff4){
            $colors[$key] = $color5;
            continue;
        } else if($value >= $cutoff3){
            $colors[$key] = $color4;
            continue;
        } else if($value >= $cutoff2){
            $colors[$key] = $color3;
            continue;
        } else if($value >= $cutoff1){
            $colors[$key] = $color2;
            continue;
        } else if($value > 0){
            $colors[$key] = $color1;
            continue;
        }
    }
}

$styleString = ".oceanxx { fill: #B5D6FE; }\n.landxx, .coastxx, .antxx { stroke: #000; stroke-width:0.3; }\n";

if(count($colors)){
    foreach ($colors as $region => $color) {
        $styleString .= ".".$region." { fill: #".$color."; }\n";
    }
}

$svg = str_replace("/** STYLE **/", $styleString, $svg);

header('Content-type: image/svg+xml');
print $svg;

?>
