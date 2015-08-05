<?php
    // game loop
    while (true) {
        $SX = $SY = 0;
        $mountain_heights = array();
        fscanf(STDIN, "%d %d", $SX, $SY);

        for ($i = 0; $i < 8; $i++) {
            fscanf(STDIN, "%d", $MH);
            $mountain_heights[$i] = $MH;
        }
        $max_height = max($mountain_heights);
        $mountain = array_search($max_height, $mountain_heights);
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        if ($SX == $mountain) {
            echo("FIRE\n"); // either:  FIRE (ship is firing its phase cannons) or HOLD (ship is not firing).
        } else {
            echo("HOLD\n");
        }
    }
