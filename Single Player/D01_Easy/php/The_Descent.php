<?php
    /**
     * Auto-generated code below aims at helping you parse
     * the standard input according to the problem statement.
     **/

    // game loop
    while (1) {
        $mountains = [];

        for ($i = 0; $i < 8; $i++) {
            fscanf(STDIN, "%d", $mountainHeight); // represents the height of one mountain, from 9 to 0.
            $mountains[$mountainHeight] = $i;
        }
        krsort($mountains);

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        echo current($mountains) . "\n"; // The number of the mountain to fire on.
    }
