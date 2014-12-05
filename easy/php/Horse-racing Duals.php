<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d",
    $N
);

$horses = array();
for ($i = 0; $i < $N; $i++)
{
    fscanf(STDIN, "%d",
        $Pi
    );
    $horses[] = $Pi;
}

sort($horses);
$count = count($horses);
$D = $horses[$count-1]; //max

for ($i=0; $i<$count-1; $i++) {
    $D_temp = abs($horses[$i] - $horses[$i+1]);
    
    if($D > $D_temp) $D = $D_temp;
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

echo($D."\n");
