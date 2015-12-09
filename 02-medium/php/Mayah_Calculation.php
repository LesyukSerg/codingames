<?php
$NUMERS = array();
$OPERAND1 = array();
$OPERAND2 = array();

fscanf(STDIN, "%d %d", $L, $H);
//error_log(var_export($L.' '.$H, true));

for ($i = 0; $i < $H; $i++) {
    fscanf(STDIN, "%s", $numeral);
    //error_log(var_export($numeral, true));
    $NUMERS[$i] = $numeral;
}

fscanf(STDIN, "%d", $S1);
//error_log(var_export($S1, true));

for ($i = 0; $i < $S1; $i++){
    fscanf(STDIN, "%s", $num1Line);
    //error_log(var_export($num1Line, true));
    
    $n = floor($i/$L);
    $r = $i % $L;
    $OPERAND1[$n][$r] = $num1Line;
}

fscanf(STDIN, "%d", $S2);
//error_log(var_export($S2, true));

for ($i = 0; $i < $S2; $i++) {
    fscanf(STDIN, "%s", $num2Line);
    //error_log(var_export($num2Line, true));
    
    $n = floor($i/$L);
    $r = $i % $L;
    $OPERAND2[$n][$r] = $num2Line;
}

fscanf(STDIN, "%s", $OP);
//error_log(var_export($OP, true));

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));
error_log(var_export('======================', true));
//mayatodec($OPERAND1, $NUMERS); die;

#--------Maya to decimal -------------------------
$cnt = count($OPERAND1);
$D1 = 0;
foreach($OPERAND1 as $N){
    $D1 += mayatodec($N, $NUMERS, $L) * pow(20, --$cnt);
}

$cnt = count($OPERAND2);
$D2 = 0;
foreach($OPERAND2 as $N){
    $D2 += mayatodec($N, $NUMERS, $L) * pow(20, --$cnt);
}
unset($OPERAND1, $OPERAND2);
#-------------------------------------------------

#-------- Operation -------------------------
if($OP == '+') {
    $R_DEC = $D1 + $D2;
}
elseif($OP == '-') {
    $R_DEC = $D1 - $D2;
}
elseif($OP == '*') {
    $R_DEC = $D1 * $D2;
}
elseif($OP == '/') {
    $R_DEC = $D1 / $D2;
}
unset($D1, $D2);
#-------------------------------------------------
error_log(var_export($R_DEC, true));


$R = dectomaya($R_DEC, $NUMERS, $L);
echo implode("\n", $R);


function mayatodec($N, $NMRS, $L) // $N - number $NMRS - array of mayah $L - length one leter
{
    $found = 0;
    for($rw = 0; $rw<$L; $rw++) { // $rw - letter row
        for($l=$found; $l<$L*20; $l=$l+$L) {
            $SLINE = substr($NMRS[$rw], $l, $L);
            if($SLINE != $N[$rw]) {
                if($found && $rw > 0) $rw=0;
                continue;
            } else {
                //error_log(var_export($l/4, true));
                $found = $l;
                break;
            }
        }
    }
    
    //error_log(var_export($l/4, true));
    if($l > 0)
        return $l/$L;
    else 
        return 0;
}

function dectomaya($N, $NMRS, $L) // $N - number $NMRS - array of mayah $L - length one leter
{
    $REZ_DEC = array();
    
    
    do {
        $REM = $N % 20;
        $REZ_DEC[] = $REM;
        $N = floor($N/20);
    } while($N);
    
    //error_log(var_export($REZ_DEC, true));
    krsort($REZ_DEC);
    
    foreach($REZ_DEC as $i=>$R) {
        foreach($NMRS as $LINE) {
            $REZ[] = substr($LINE, $R*$L, $L);
        }
    }
    
    return $REZ;
}
