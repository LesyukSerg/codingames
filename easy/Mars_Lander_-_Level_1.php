<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d",
    $N // the number of points used to draw the surface of Mars.
);

$coords = array();
for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%d %d",
        $LAND_X, // X coordinate of a surface point. (0 to 6999)
        $LAND_Y // Y coordinate of a surface point. By linking all the points together in a sequential fashion, you form the surface of Mars.
    );
    $coords[] = array('x'=>$LAND_X, 'y'=>$LAND_Y);
    
    if($i > 0 && $coords[$i]['y'] == $coords[$i-1]['y']) {
        $Y_landing = $coords[$i]['y'];
        $X1_landing = $coords[$i-1]['x'];
        $X2_landing = $coords[$i]['x'];
        
        error_log(var_export($Y_landing, true));
        error_log(var_export($X1_landing, true));
        error_log(var_export($X2_landing, true));
        
        die;
    }
}




// game loop
while (TRUE)
{
    fscanf(STDIN, "%d %d %d %d %d %d %d",
        $X,
        $Y,
        $HS, // the horizontal speed (in m/s), can be negative.
        $VS, // the vertical speed (in m/s), can be negative.
        $F, // the quantity of remaining fuel in liters.
        $R, // the rotation angle in degrees (-90 to 90).
        $P // the thrust power (0 to 4).
    );

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo("-20 3\n"); // R P. R is the desired rotation angle. P is the desired thrust power.
}
?>