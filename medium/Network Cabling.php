<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/
fscanf(STDIN, "%d",
    $N
);


for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%d %d",
        $X,
        $Y
    );
    $X_ARR[] = $X;
    $Y_ARR[] = $Y;
}

// find the left-most house, and the right-most house (minX, maxX)
$minX = min($X_ARR);
$maxX = max($X_ARR);

// compute the average vertical point for all houses
$avgY = floor(array_sum($Y_ARR)/count($Y_ARR));


$len['Low'] = 0;
$len['Mid'] = 0;
$len['High'] = 0;
   
// because the coordinates are integers the average may be a floating point value
// so we need to consider both integers around the average value
foreach($Y_ARR as $y) {
    $len['Low'] += abs($y - ($avgY - 1));
    $len['Mid'] += abs($y - $avgY);
    $len['High'] += abs($y - ($avgY + 1));
}
//error_log(var_export($lenLow.' '.$lenMid.' '.$lenHigh, true));

// the minimum will be one of the three values (plus the length of horizontal cable)
$LENGTH = ($maxX - $minX) + min($len);

echo($LENGTH."\n");
