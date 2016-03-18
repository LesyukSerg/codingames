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
        fscanf(STDIN, "%s", $map[]);
    }

    for ($y = $Y - 1; $y < $Y + 2; $y++) {
        for ($x = $X - 1; $x < $X + 2; $x++) {
            if ($x == $X && $y == $Y) {
                continue;
            } else {
                error_log(var_export($y . ' ' . $x . ' = ' . $map[$y][$x], true));
                if ($map[$y][$x]) {
                    $n++;
                }
            }
        }
    }

    echo $n . "\n";
