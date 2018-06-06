<?php
    fscanf(STDIN, "%d %d %d %d",
        $LX, // the X position of the light of power
        $LY, // the Y position of the light of power
        $TX, // Thor's starting X position
        $TY // Thor's starting Y position
    );
    $X = $TX;
    $Y = $TY;
    // game loop
    while (true) {
        fscanf(STDIN, "%d", $E); // The level of Thor's remaining energy, representing the number of moves he can still make.

        if ($Y < $LY) {
            echo 'S';
            $Y++;
        } elseif ($TY > $LY) {
            echo 'N';
            $Y--;
        }

        if ($X < $LX) {
            echo 'E';
            $X++;
        } elseif ($X > $LX) {
            echo 'W';
            $X--;
        }

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        echo "\n"; // A single line providing the move to be made: N NE E SE S SW W or NW
    }
