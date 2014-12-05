<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/
fscanf(STDIN, "%d", $N);

$CALC = array();
for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%d %d", $J, $D );
    
    if(empty($CALC[$J])) {
        $CALC[$J] = $D;
    } else {
        if($CALC[$J] > $D)
            $CALC[$J] = $D;
    }
}

$CL = array();
foreach($CALC as $J=>$D) {
    $CL[] = array('J'=>$J, 'D'=>$J+$D);
}

sort($CL);
$cnt = count($CL);
$old_k = 0;

foreach($CL as $k=>$CRNT){
    if($CL[$old_k]['D'] > $CRNT['J'] && $old_k != $k) {
        if($CRNT['J'] > $CL[$old_k]['J'] && $CRNT['D'] < $CL[$old_k]['D']) {
            unset($CL[$old_k]);
            $old_k = $k;
        } else {
            unset($CL[$k]);
        }
    } else {
        $old_k = $k;
    }
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));
echo(count($CL)."\n");
