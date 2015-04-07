<?php

fscanf(STDIN, "%d %d %d",
    $L,
    $C,
    $N
);


$GROUPS = array();
$SUM = array();
$s = 0;
$SUM[$s] = 0;
for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%d",
        $Pi
    );
    $GROUPS[] = $Pi;
    //error_log(var_export($SUM, true));
    error_log(var_export($Pi, true));
    
    if($SUM[$s]+$Pi <= $L){
        $SUM[$s] +=$Pi;
    } else {
        if(count($SUM)+1 > $C) break;
        
        $SUM[++$s] = $Pi;
    }
}




//error_log(var_export($SUM, true));
$i = 0;
$P = $GROUPS[$i];
while($s > 0 && $P && $SUM[$s]+$P <= $L){
    $SUM[$s] += $P;
    $i++;
}
if(empty($GROUPS[$i])) $i = 0;
$scnt = count($SUM);
//error_log(var_export($scnt, true));
//error_log(var_export($C, true));

$REZ = 0;
if($scnt < $C){
    for($c = $C-$scnt; $c>0; $c--) {
        $total = 0;
        $P = $GROUPS[$i];
        $start_i = $i;
        
        while($P && $total+$P <= $L) {
            $total += $P;
            
            $i++;
            
            if($i >= $N) $i = 0;
            $P = $GROUPS[$i];
            
            if($start_i == $i) break;
        }
        $REZ += $total;
    }
}
error_log(var_export(array_sum($SUM), true));
$REZ += array_sum($SUM);

error_log(var_export($L, true));
//error_log(var_export($GROUPS, true));

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

echo($REZ."\n");
