<?
    fscanf(STDIN, "%d",
        $N // the number of points used to draw the surface of Mars.
    );

    $coords = [];
    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d %d",
            $LAND_X, // X coordinate of a surface point. (0 to 6999)
            $LAND_Y // Y coordinate of a surface point. By linking all the points together in a sequential fashion, you form the surface of Mars.
        );
        $coords[] = ['x' => $LAND_X, 'y' => $LAND_Y];

        if ($i > 0 && $coords[$i]['y'] == $coords[$i - 1]['y']) {
            $Y_landing = $coords[$i]['y'];
            $X1_landing = $coords[$i - 1]['x'];
            $X2_landing = $coords[$i]['x'];

            error_log(var_export($Y_landing, true));
            error_log(var_export($X1_landing, true));
            error_log(var_export($X2_landing, true));
        }
    }
    $X_landing = ($X1_landing + $X2_landing) / 2;

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

        error_log(var_export($X_landing - $X, true));
        if (abs($X_landing - $X) < 750) {
            if ($HS > 12) {
                echo("50 4\n");
            } else if ($HS < -12) {
                echo("-50 4\n");
            } else {
                if ($VS < -39) {
                    echo("0 4\n");
                } else {
                    echo("0 2\n");
                }
            }
        } else {
            if (($X_landing - $X) > 0) {
                if ($HS > 40) {
                    echo("20 4\n");
                } else {
                    echo("-20 4\n");
                }
            } else if (($X_landing - $X) < 0) {
                if ($HS < -40) {
                    echo("-20 4\n");
                } else {
                    echo("20 4\n");
                }
            }
        }

        //echo("0 3\n"); // R P. R is the desired rotation angle. P is the desired thrust power.
    }
