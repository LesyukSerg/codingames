<?php
    /**
     * Auto-generated code below aims at helping you parse
     * the standard input according to the problem statement.
     **/

    fscanf(STDIN, "%d", $R); // the length of the road before the gap.
    fscanf(STDIN, "%d", $G); // the length of the gap.
    fscanf(STDIN, "%d", $L); // the length of the landing platform.

    $after_jump = $R + $G - 1;
    $before_jump = $R;
    // game loop
    while (true) {
        fscanf(STDIN, "%d",
            $S // the motorbike's speed.
        );
        fscanf(STDIN, "%d",
            $X // the position on the road of the motorbike.
        );

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        if ($X + $S <= $before_jump) {
            if ($S <= $G)
                echo("SPEED\n");
            elseif ($S > $G + 1)
                echo("SLOW\n");
            else
                echo("WAIT\n");

        } elseif ($X > $after_jump) {
            echo("SLOW\n");
        } else {
            echo("JUMP\n");
        }
    }
