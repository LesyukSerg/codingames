<?php
    fscanf(STDIN, "%d %d", $a, $b);
    $res = $b * $a;
    $plus = [];

    if ($a < $b) list($a, $b) = [$b, $a];

    echo "{$a} * {$b}\n";

    while ($b) {
        if ($b % 2 == 0) {
            $a *= 2;
            $b /= 2;
        } else {
            $b--;
            $plus[] = $a;
        }
        echo "= {$a} * {$b}";

        if (count($plus)) {
            echo " + " . implode(" + ", $plus);
        }

        echo "\n";
    }

    echo "= {$res}\n";

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
