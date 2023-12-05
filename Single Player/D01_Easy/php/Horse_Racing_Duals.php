<?php

    fscanf(STDIN, "%d", $quantity);
    $horses = [];
    $difference = [];

    for ($i = 0; $i < $quantity; $i++) {
        fscanf(STDIN, "%d", $horses[]);
    }

    sort($horses);


    for ($i = 1; $i < $quantity; $i++) {
        $difference[] = abs($horses[$i - 1] - $horses[$i]);
    }

    echo min($difference) . "\n";

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));