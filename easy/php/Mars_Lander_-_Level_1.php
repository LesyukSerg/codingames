<?php

    fscanf(STDIN, "%d", $N); // the number of points used to draw the surface of Mars.

    $coords = array();
    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d %d",
            $LAND_X, // X coordinate of a surface point. (0 to 6999)
            $LAND_Y // Y coordinate of a surface point. By linking all the points together in a sequential fashion, you form the surface of Mars.
        );
        $coords[] = array('x' => $LAND_X, 'y' => $LAND_Y);

        if ($i > 0 && $coords[$i]['y'] == $coords[$i - 1]['y']) {
            $Y_landing = $coords[$i]['y'];
            $X1_landing = $coords[$i - 1]['x'];
            $X2_landing = $coords[$i]['x'];

            error_log(var_export($Y_landing, true));
            error_log(var_export($X1_landing, true));
            error_log(var_export($X2_landing, true));
        }
    }


    $flag = $i = 0;
    // game loop
    while (true) {
        $i++;
        fscanf(STDIN, "%d %d %d %d %d %d %d",
            $X,
            $Y,
            $HS, // the horizontal speed (in m/s), can be negative.
            $VS, // the vertical speed (in m/s), can be negative.
            $F, // the quantity of remaining fuel in liters.
            $R, // the rotation angle in degrees (-90 to 90).
            $P // the thrust power (0 to 4).
        );

        if ($VS < -44)
            $flag++;


        if ($flag)
            echo("0 4\n");
        else
            echo("0 0\n");
    }
