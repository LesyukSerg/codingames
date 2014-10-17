<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/
$W = 100;
$H = 50;
$X = 13;
$Y = 25;

$BOMB_DIR = 'DR';
function say($X, $Y){
    $R = '';
    $XZ = 92;
    $YZ = 44;
    if ($X > $XZ) {
        $R .= 'L';
    }elseif ($X < $XZ) {
        $R .= 'R';
    }
    
    if ($Y > $YZ) {
        $R .= 'U';
    }elseif ($Y < $YZ) {
        $R .= 'D';
    }
    
    return $R;
}
/* fscanf(STDIN, "%d %d",
    $W, // width of the building.
    $H // height of the building.
);
fscanf(STDIN, "%d",
    $N // maximum number of turns before game over.
);
fscanf(STDIN, "%d %d",
    $X,
    $Y
);

fscanf(STDIN, "%s",
    $BOMB_DIR // the direction of the bombs from batman's current location (U, UR, R, DR, D, DL, L or UL)
);
 */
 
$BOMB_DIR = say($X, $Y);
for($i=0; $i<strlen($BOMB_DIR); $i++) {
    if ($BOMB_DIR[$i] == 'L') {
        $X0 = 0;
        $X1 = $X;
    }
    elseif ($BOMB_DIR[$i] == 'R') {
        $X0 = $X;
        $X1 = --$W;
    }
    elseif ($BOMB_DIR[$i] == 'U') {
        $Y0 = 0;
        $Y1 = $Y;
    }
    elseif ($BOMB_DIR[$i] == 'D') {
        $Y0 = $Y;
        $Y1 = --$H;
    }
}
error_log(var_export($X0.' '.$Y0, true));
error_log(var_export($X1.' '.$Y1, true));

$round = 0;
$f = 0.5;
// game loop
while (TRUE) {
    if($round > 100) die;
    /* if($round) {
        fscanf(STDIN, "%s",
            $BOMB_DIR // the direction of the bombs from batman's current location (U, UR, R, DR, D, DL, L or UL)
        );
    } */
    if($x && $y){
        $f = 1;
        $BOMB_DIR = say($x, $y);
    }
    
    
    

    for($i=0; $i<strlen($BOMB_DIR); $i++) {
        if ($BOMB_DIR[$i] == 'L') {
            $x = $X0 = ceil(($W-$X0)/(2*$f));
        }
        elseif ($BOMB_DIR[$i] == 'R') {
            $x = $X1 = ceil($X1/(2*$f));
        }
        elseif ($BOMB_DIR[$i] == 'U') {
            $y = $Y0 = ceil(($H-$Y0)/(2*$f));
        }
        elseif ($BOMB_DIR[$i] == 'D') {
            $y = $Y1 = ceil($Y1/(2*$f));
        }
    }
    $round++;
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    error_log(var_export($BOMB_DIR, true));
    error_log(var_export($X0.' '.$Y0, true));
    error_log(var_export($X1.' '.$Y1, true));
    //echo $BOMB_DIR."\n";
    //echo $X0.' '.$Y0;
    echo("{$x} {$y}\n"); // the location of the next window Batman should jump to.

}
?>