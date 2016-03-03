<?php

fscanf(STDIN, "%d", $N);
fscanf(STDIN, "%d", $C);

error_log(var_export($N.' '.$C, true));

$MONEY = array();
$CASH = array();
for ($i = 0; $i < $N; $i++){
    fscanf(STDIN, "%d", $B);
    $CASH[] = $B;
}

sort($CASH);

foreach($CASH as $i => $B) {
    if($C > 0) {
        $part = floor($C/($N-$i));
        
        if($B > $part) {
            $MONEY[] = $part;
            $C -= $part;
        } else {
            $MONEY[] = $B;
            $C -= $B;
        }
    } else {
        $MONEY[] = 0;
    }
}

if($C == 0) {
    echo implode("\n", $MONEY);
} else {
    echo "IMPOSSIBLE\n";
}


// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));
