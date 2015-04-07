<?php

$L=10000;
$C=10;
$N=5;
/* fscanf(STDIN, "%d %d %d",
    $L, # limited number of places.
    $C, # number of times per day.
    $N  # number of groups.
);
error_log(var_export('L='.$L.'C='.$C.'N='.$N, true)); */

$GROUPS = array(100,200,300,400,500);

/* for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%d",
        $GROUPS[]
    );
} */
error_log(var_export($GROUPS, true));

$indexG = 0;
$STOP = 0;
$LAST = count($GROUPS)-1;
$Rez = array();
for($c=$C; $c>0; $c--) {
    $Rez[$c] = $GROUPS[$indexG];
    $indexG = ($indexG < $LAST) ? $indexG+1 : 0;
    $NEXT = $Rez[$c] + $GROUPS[$indexG];
    
    while($Rez[$c] < $L && $indexG != $STOP && $NEXT <= $L) {
        $Rez[$c] = $NEXT;
        $indexG = ($indexG < $LAST) ? $indexG+1 : 0;
        $NEXT = $Rez[$c] + $GROUPS[$indexG];
    }
    $STOP = $indexG;
}

echo array_sum($Rez)."\n";
die;
