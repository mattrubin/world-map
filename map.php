<?PHP
/*
map.php
Displays a map of the World color-coded to correspond with hit data
*/

$image = imagecreatefrompng('WorldMapSmall.png');

// set predetermined index for each region in image
$index = array ('US' =>  0, // United States
                'CA' =>  1, // Canada
                'IT' =>  2, // Italy
                'BM' =>  3, // Bermuda
                'AR' =>  4, // Argentina
                'SV' =>  5, // El Salvador
                'NO' =>  6, // Norway
                'ES' =>  7, // Spain
                'PA' =>  8, // Panama
                'AE' =>  9, // United Arab Emirates
                'IE' => 10, // Ireland
                'GB' => 11, // Great Britain
                'BD' => 12, // Bangladesh
                'TZ' => 13, // Tanzania
);

$colors = array();

$color1 = (array_key_exists('color1', $_GET)) ? (substr($_GET['color1'],1)) : ("660099");
$color2 = (array_key_exists('color2', $_GET)) ? (substr($_GET['color2'],1)) : ("0000FF");
$color3 = (array_key_exists('color3', $_GET)) ? (substr($_GET['color3'],1)) : ("00FF00");
$color4 = (array_key_exists('color4', $_GET)) ? (substr($_GET['color4'],1)) : ("FFFF00");
$color5 = (array_key_exists('color5', $_GET)) ? (substr($_GET['color5'],1)) : ("FF0000");

$cutoff1 = (array_key_exists('cutoff1', $_GET)) ? ($_GET['cutoff1']) : ("2");
$cutoff2 = (array_key_exists('cutoff2', $_GET)) ? ($_GET['cutoff2']) : ("5");
$cutoff3 = (array_key_exists('cutoff3', $_GET)) ? ($_GET['cutoff3']) : ("10");
$cutoff4 = (array_key_exists('cutoff4', $_GET)) ? ($_GET['cutoff4']) : ("20");

// Set all indexed regions to white
foreach ($index as $key => $value) {
    imageColorset($image, $index[$key], 255, 255, 255);
}

foreach ($_GET as $key => $value) {
    $key = strtoupper($key);
    if(array_key_exists($key,$index)){
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

if(count($colors)){
    foreach ($colors as $region => $color) {
        imageColorset($image,$index[$region],hexdec(substr($color,0,2)),hexdec(substr($color,2,2)),hexdec(substr($color,-2)));
    }
}

header('Content-type: image/png'); 
imagePng($image);
imageDestroy($image);

?>
