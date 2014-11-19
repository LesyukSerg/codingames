<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/


// game loop
while (TRUE)
{
    fscanf(STDIN, "%d %d",
        $SX,
        $SY
    );
    $mountain_heights = array();
    for ($i = 0; $i < 8; $i++)
    {
        fscanf(STDIN, "%d",
            $MH // represents the height of one mountain, from 9 to 0. Mountain heights are provided from left to right.
        );
        $mountain_heights[$i] = $MH;
    }
    $max_height = max($mountain_heights);
    $mountain = array_search($max_height, $mountain_heights);
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    if($SX == $mountain){
        echo("FIRE\n"); // either:  FIRE (ship is firing its phase cannons) or HOLD (ship is not firing).
    }else {
        echo("HOLD\n");
    }
}
?>