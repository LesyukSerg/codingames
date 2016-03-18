<?php
    /**
     * Auto-generated code below aims at helping you parse
     * the standard input according to the problem statement.
     **/

    fscanf(STDIN, "%d %d", $W, $H);
    fscanf(STDIN, "%d %d", $X, $Y);
    $n = 0;

    $map = [];
    for ($i = 0; $i < $H; $i++) {
        fscanf(STDIN, "%s",

        );
        error_log(var_export($row, true));

        if (strstr($row, '1')) {
            $n++;
        }

        if($n > 8) $n = 8;
    }

    echo $n . "\n";
