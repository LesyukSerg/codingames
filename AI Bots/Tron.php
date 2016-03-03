<?php
/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/


// game loop
$FILLED = array();
$MOVE = '';
while (TRUE)
{
    fscanf(STDIN, "%d %d",
        $N, // total number of players (2 to 4).
        $P // your player number (0 to 3).
    );
    
    for ($i = 0; $i < $N; $i++)
    {
        fscanf(STDIN, "%d %d %d %d",
            $X0, // starting X coordinate of lightcycle (or -1)
            $Y0, // starting Y coordinate of lightcycle (or -1)
            $X1, // starting X coordinate of lightcycle (can be the same as X0 if you play before this player)
            $Y1 // starting Y coordinate of lightcycle (can be the same as Y0 if you play before this player)
        );
        $FILLED[$X1.'_'.$Y1] = array('x'=>$X1,'y'=>$Y1);
        
        if($i == $P) {
            $CRNT = $FILLED[$X1.'_'.$Y1];
        } else {
            $T = $FILLED[$X1.'_'.$Y1];
        }
    }
    //error_log(var_export($FILLED, true));
    $MOVE = analize($FILLED, $CRNT, $MOVE);

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    
    echo($MOVE."\n"); // A single line with UP, DOWN, LEFT or RIGHT
}



function analize($FILLED, $C, $MOVE)
{
    if($MOVE == '') {
        if($C['x'] < abs($C['x']-30)) {
            $MOVE = 'LEFT';
        } else {
            $MOVE = 'RIGHT';
        }
    } else {
        if($MOVE == 'LEFT') {
            $C_NEXT['x'] = $C['x']-1;
            $C_NEXT['y'] = $C['y'];
            
            if(!can_i_go_same($C_NEXT, $FILLED)) {
                $C_UP['x'] = $C['x'];
                $C_UP['y'] = $C['y']-1;
                if(can_i_go_same($C_UP, $FILLED)) {
                    $MOVE = 'UP';    
                } else {
                    $MOVE = 'DOWN';
                }
            } 
        }
        elseif($MOVE == 'UP') {
            $C_NEXT['x'] = $C['x'];
            $C_NEXT['y'] = $C['y']-1;
            
            if(!can_i_go_same($C_NEXT, $FILLED)) {
                $C_RIGHT['x'] = $C['x']+1;
                $C_RIGHT['y'] = $C['y'];
                if(can_i_go_same($C_RIGHT, $FILLED)) {
                    $MOVE = 'RIGHT';    
                } else {
                    $MOVE = 'LEFT';
                }
            }
        }
        elseif($MOVE == 'RIGHT') {
            $C_NEXT['x'] = $C['x']+1;
            $C_NEXT['y'] = $C['y'];
            
            if(!can_i_go_same($C_NEXT, $FILLED)) {
                $C_DOWN['x'] = $C['x'];
                $C_DOWN['y'] = $C['y']+1;
                if(can_i_go_same($C_DOWN, $FILLED)) {
                    $MOVE = 'DOWN';    
                } else {
                    $MOVE = 'UP';
                }
            }
        }
        elseif($MOVE == 'DOWN') {
            $C_NEXT['x'] = $C['x'];
            $C_NEXT['y'] = $C['y']+1;
            
            if(!can_i_go_same($C_NEXT, $FILLED)) {
                $C_LEFT['x'] = $C['x']-1;
                $C_LEFT['y'] = $C['y'];
                if(can_i_go_same($C_LEFT, $FILLED)) {
                    $MOVE = 'LEFT';    
                } else {
                    $MOVE = 'RIGHT';
                }
            }
        }
    }
    
    return $MOVE;
}


function can_i_go_same($CN, $FILLED)
{
    error_log(var_export($FILLED[$CN['x'].'_'.$CN['y']], true));
    if(
        ($CN['y'] >= 0 && $CN['y'] < 20)
        &&
        ($CN['x'] >= 0 && $CN['x'] < 30)
        &&
        empty($FILLED[$CN['x'].'_'.$CN['y']])
    )
        return true;
    else 
        return false;
}


