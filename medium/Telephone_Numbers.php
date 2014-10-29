<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

function buldTree($number, &$T, $N)
{
    $cnt = 0;
    if($N < strlen($number)) {
        if(!isset($T[$number[$N]])) {
            $T[$number[$N]] = array();
            $cnt++;
        }
        
        return $cnt+buldTree($number, $T[$number[$N]], ++$N);
    }
    return 0;
}

fscanf(STDIN, "%d",
    $N
);

$COUNT = 0;
$TT = array();
for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%s",
        $telephone
    );

    $COUNT += buldTree($telephone, $TT, 0);
}

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

echo($COUNT."\n"); // The number of elements (referencing a number) stored in the structure.
