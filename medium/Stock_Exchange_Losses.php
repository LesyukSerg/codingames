<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d",
    $n
);
$vs = stream_get_line(STDIN, pow(2, 31), "\n");

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));
$V = explode(' ', $vs);

arsort($V);
$MAX_V = $V;

asort($V);
$MIN_V = $V;


$REST1 = 'D';
foreach ($MAX_V as $k1 => $max) {
    foreach ($MIN_V as $k2 => $min) {
        if ($k2 > $k1) {
            $REST1 = $max - $min;

            break;
        }
    }
    if ($REST1 != 'D') break;
}

$REST2 = 'D';
foreach ($MIN_V as $k2 => $min) {
    foreach ($MAX_V as $k1 => $max) {
        if ($k2 > $k1) {
            $REST2 = $max - $min;
            
            break;
        }
    }
    if ($REST2 != 'D') break;
}

if ($REST1 > 0 || $REST2 > 0) {
    if ($REST1 - $REST2 > 0)
        echo(-$REST1."\n");
    else
        echo(-$REST2."\n");
} else {
    echo("0\n");
}
