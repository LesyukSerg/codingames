<?php
// Read inputs from STDIN. Print outputs to STDOUT.

function myDeg2Rad($deg)
{
    $deg = (float) str_replace(',', '.', $deg);
    
    return ($deg * M_PI / 180);
}

function getDistance($latA, $lonA, $latB, $lonB)
{
    $x = ($lonB - $lonA) * cos( ($latA + $latB)/2 );
    $y = $latB - $latA;
    
    return sqrt( pow($x,2) + pow($y,2) ) * 6371;
}


fscanf(STDIN, "%s", $LAT);
fscanf(STDIN, "%s", $LON);
fscanf(STDIN, "%d", $N);

$LAT = myDeg2Rad($LAT);
$LON = myDeg2Rad($LON);

$defib = array();
$distance = array();
for ($i = 0; $i < $N; $i++) {
    $LINE = stream_get_line(STDIN, 512, "\n");
    
    $defib[] = $LINE;
    
    $arLINE = explode(';', $LINE);

    $distance[] = getDistance($LAT, $LON, myDeg2Rad($arLINE[4]), myDeg2Rad($arLINE[5]));
}

$min = min($distance);
$k = array_search($min, $distance);

if (isset($defib[$k])) {
    $arRez = explode(';', $defib[$k]);
    
    echo $arRez[1]."\n";
}

//error_log(var_export($distance, true));
?>