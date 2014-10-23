<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

fscanf(STDIN, "%d %d",
    $building_W, // width of the building.
    $building_H // height of the building.
);
fscanf(STDIN, "%d",
    $N // maximum number of turns before game over.
);
fscanf(STDIN, "%d %d",
    $batman_X,
    $batman_Y
);

fscanf(STDIN, "%s",
    $BOMB_DIR // the direction of the bombs from batman's current location (U, UR, R, DR, D, DL, L or UL)
);

$X_LEFT = $X_RIGHT = $Y_TOP = $Y_BOTTOM = 0;
for($i=0; $i<strlen($BOMB_DIR); $i++) {
    if ($BOMB_DIR[$i] == 'L') {
        $X_RIGHT = $batman_X;
    }
    elseif ($BOMB_DIR[$i] == 'R') {
        $X_LEFT = $batman_X;
        $X_RIGHT = --$building_W;
    }
    elseif ($BOMB_DIR[$i] == 'U') {
        $Y_BOTTOM = $batman_Y;
    }
    elseif ($BOMB_DIR[$i] == 'D') {
        $Y_TOP = $batman_Y;
        $Y_BOTTOM = --$building_H;
    }
}
error_log(var_export($X_LEFT.' '.$Y_TOP, true));
error_log(var_export($X_RIGHT.' '.$Y_BOTTOM, true));

$round = 0;
// game loop
$r = 1;
$jump_to_x = $jump_to_y = 0;
while (TRUE) {
    if($round) {
        fscanf(STDIN, "%s",
            $BOMB_DIR // the direction of the bombs from batman's current location (U, UR, R, DR, D, DL, L or UL)
        );
        $r = 1;
    
        for($i=0; $i<strlen($BOMB_DIR); $i++) {
            if ($BOMB_DIR[$i] == 'L') {
                $X_RIGHT = $jump_to_x;
            }
            elseif ($BOMB_DIR[$i] == 'R') {
                $X_LEFT = $jump_to_x;
            }
            elseif ($BOMB_DIR[$i] == 'U') {
                $Y_BOTTOM = $jump_to_y;
            }
            elseif ($BOMB_DIR[$i] == 'D') {
                $Y_TOP = $jump_to_y;
            }
        }
    }

    error_log(var_export($BOMB_DIR, true));
    error_log(var_export($X_LEFT.' '.$Y_TOP, true));
    error_log(var_export($X_RIGHT.' '.$Y_BOTTOM, true));
    
    for($i=0; $i<strlen($BOMB_DIR); $i++) {
        if ($BOMB_DIR[$i] == 'L' || $BOMB_DIR[$i] == 'R') {
            
            $maybe_jump_x = $X_LEFT + floor(($X_RIGHT-$X_LEFT)/(2*$r));
            
            if ($maybe_jump_x == $jump_to_x) {
                $jump_to_x++;
            } else {
                $jump_to_x = $maybe_jump_x;
            }
        }
        elseif ($BOMB_DIR[$i] == 'U' || $BOMB_DIR[$i] == 'D') {
            
            $maybe_jump_y = $Y_TOP + floor(($Y_BOTTOM-$Y_TOP)/(2*$r));
            
            if ($maybe_jump_y == $jump_to_y) {
                $jump_to_y++;
            } else {
                $jump_to_y = $maybe_jump_y;
            }
        }
    }
    $round++;
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    
    //echo $BOMB_DIR."\n";
    //echo $X0.' '.$Y0;
    echo("{$jump_to_x} {$jump_to_y}\n"); // the location of the next window Batman should jump to.

}
?>