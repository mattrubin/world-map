<?PHP
/*
map.php
Displays a map of the World color-coded to correspond with hit data
*/

// connect to a database & load sales data into an array $states
// with the state code as the key & current sales data as value
//GET DATA!


$image = imageCreatefrompng('WorldMapSmall.png') ;
// set predetermined index for each region in image
$index = array ('US' => 00, //United States
				'CA' =>  1, //Canada
				'IT' =>  2, //Italy
				'BM' =>  3, //Bermuda
				'AR' =>  4, //Argentina
				'SV' =>  5, //El Salvador
				'NO' =>  6, //Norway
// for some reason, setting panama as 08 or use as 09 makes them 0???
				'PA' =>  8, //Panama
				'AE' =>  9, //United Arab Emirates
				'IE' => 10, //Ireland
				'GB' => 11 //Great Britain
);
/*
				'MX' => 38, //Mexico
				'BZ' => 39, //Belize
				'CU' => 40, //Cuba
				'DO' => 41, //Dominican Republic
				'CR' => 42, //Costa Rica
				'HN' => 43, //Honduras
				'HT' => 44, //Haiti
				'GT' => 45, //Guatemala
				'NI' => 46, //Nicaragua
				'PR' => 47, //Puerto Rico
				'SV' => 48, //El Salvador
*/
$colors = array();


$color1 = (array_key_exists('color1',$_GET))?(substr($_GET['color1'],1)):("660099");
$color2 = (array_key_exists('color2',$_GET))?(substr($_GET['color2'],1)):("0000FF");
$color3 = (array_key_exists('color3',$_GET))?(substr($_GET['color3'],1)):("00FF00");
$color4 = (array_key_exists('color4',$_GET))?(substr($_GET['color4'],1)):("FFFF00");
$color5 = (array_key_exists('color5',$_GET))?(substr($_GET['color5'],1)):("FF0000");

$cutoff1 = (array_key_exists('cutoff1',$_GET))?($_GET['cutoff1']):("2");
$cutoff2 = (array_key_exists('cutoff2',$_GET))?($_GET['cutoff2']):("5");
$cutoff3 = (array_key_exists('cutoff3',$_GET))?($_GET['cutoff3']):("10");
$cutoff4 = (array_key_exists('cutoff4',$_GET))?($_GET['cutoff4']):("20");


//print_r($index);


// Set all indexed regions to white
foreach ($index as $key => $value) {
    imageColorset($image,$index[$key],255,255,255);
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


/*

// convert sales data values to a color range
$normalized = gradientFromRange($_GET);


// In order to fade from blue to white, maximize the blue component
// and then increase the other two components by the same value
if(count($normalized)){
	foreach ($normalized as $state => $color) {
		$state = strtoupper($state);
		if(array_key_exists($state, $index)){
		    imageColorset($image,$index[$state],255,$color,$color);
		}
	}
}

*/


header('Content-type: image/png'); 
imagePng($image);
imageDestroy($image);

/**
 * @return array
 * @param $usa array states
 * @desc Normalizes an aray of a range of float values to integers
 *        representing gradient color values from 0 to 255
 */
function gradientFromRange($data) {
	$MAX_VALUE = 150;
	$MIN_VALUE = 0;
	if(count($data) == 0 || (max($data)-min($data) == 0)) return;
    // calculate what we can outside of the for loop
    $lowest = min($data);
	if(count($data) == 1) $lowest = 0;
    $ratio = $MAX_VALUE / ( max($data) - $lowest ) ;
    foreach ($data as $key => $value) {
        // subtract the lowest sales from the current sale to zero the figure
        // then multiply by the $ratio to scale highest value to 255.
        // Subtract total from 255 since color is additive,
        // making lowest sales = highest color value (255)
        // & higest sales = lowest color value (0)
		$gradient[$key] = $MAX_VALUE + $MIN_VALUE - round(($value - $lowest) * $ratio)  ;
    }
    return $gradient ;
}

?>