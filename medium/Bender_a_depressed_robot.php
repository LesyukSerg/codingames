<?php

function turn(&$HEAD, $MAP, &$Y, &$X, &$REZ)
{
    if($HEAD == 'S') {
        if( !in_array($MAP[$Y+1][$X], array('X', '#')) ) {
            $Y++;
            
            $REZ .= 'SOUTH'."\n";
            
        } else {
            $HEAD = 'E';
            turn($HEAD, $MAP, $Y, $X, $REZ);
        }
    }
    elseif($HEAD == 'E') {
        if( !in_array($MAP[$Y][$X+1], array('X', '#')) ) {
            $X++;
            
            $REZ .= 'EAST'."\n";
            
        } else {
            if( !in_array($MAP[$Y+1][$X], array('X', '#')) ) {
                $HEAD = 'S';
            } else {
                $HEAD = 'N';
            }
            turn($HEAD, $MAP, $Y, $X, $REZ);
        }
    }
    elseif($HEAD == 'N') {
        if( !in_array($MAP[$Y-1][$X], array('X', '#')) ) {
            $Y--;
            
            $REZ .= 'NORTH'."\n";
            
        } else {
            if( !in_array($MAP[$Y][$X+1], array('X', '#')) ) {
                $HEAD = 'E';
            } else {
                $HEAD = 'W';
            }
            turn($HEAD, $MAP, $Y, $X, $REZ);
        }
    }
    elseif($HEAD == 'W') {
        if( !in_array($MAP[$Y][$X-1], array('X', '#')) ) {
            $X--;
            
            $REZ .= 'WEST'."\n";
            
        } else {
            $HEAD = 'S';
            turn($HEAD, $MAP, $Y, $X, $REZ);
        }
    }
}

function turnI(&$HEAD, $MAP, &$Y, &$X, &$REZ)
{
    if($HEAD == 'W') {
        if( !in_array($MAP[$Y][$X-1], array('X', '#')) ) {
            $X--;
            
            $REZ .= 'WEST'."\n";
            
        } else {
            $HEAD = 'N';
            turnI($HEAD, $MAP, $Y, $X, $REZ);
        }
    }
    elseif($HEAD == 'N') {
        if( !in_array($MAP[$Y-1][$X], array('X', '#')) ) {
            $Y--;
            
            $REZ .= 'NORTH'."\n";
            
        } else {
            if( !in_array($MAP[$Y][$X-1], array('X', '#')) ) {
                $HEAD = 'W';
            } else {
                $HEAD = 'E';
            }
            turnI($HEAD, $MAP, $Y, $X, $REZ);
        }
    }
    elseif($HEAD == 'E') {
        if( !in_array($MAP[$Y][$X+1], array('X', '#')) ) {
            $X++;
            
            $REZ .= 'EAST'."\n";
            
        } else {
            if( !in_array($MAP[$Y-1][$X], array('X', '#')) ) {
                $HEAD = 'N';
            }else {
                $HEAD = 'S';
            }
            turnI($HEAD, $MAP, $Y, $X, $REZ);
        }
    }
    elseif($HEAD == 'S') {
        if( !in_array($MAP[$Y+1][$X], array('X', '#')) ) {
            $Y++;
            
            $REZ .= 'SOUTH'."\n";
            
        } else {
            $HEAD = 'W';
            turnI($HEAD, $MAP, $Y, $X, $REZ);
        }
    }
}

function headModificator(&$HEAD, $P) {
    if( in_array($P, array('S','E','N','W')) ) {
        $HEAD = $P;
    }
}

function breaker($H, &$MAP, $Y, $X)
{
    $MOD['S'] = array('y' => $Y+1, 'x' => $X);
    $MOD['E'] = array('y' => $Y, 'x' => $X+1);
    $MOD['N'] = array('y' => $Y-1, 'x' => $X);
    $MOD['W'] = array('y' => $Y, 'x' => $X-1);
    
    $Y = $MOD[$H]['y'];
    $X = $MOD[$H]['x'];
    
    
    if($MAP[$Y][$X] == 'X') {
        $MAP[$Y][$X] = ' ';
        error_log(var_export("\n".implode("\n",$MAP), true));
    }
}


fscanf(STDIN, "%d %d",
    $L,
    $C
);

$MAP = array();
$T = array();
for ($i = 0; $i < $L; $i++) {
    $LINE = stream_get_line(STDIN, $C+1, "\n");
    $MAP[] = $LINE;
    
    $X = strpos($LINE, '@');
    if($X) { //BENDER
        $BenderX = $X;
        $BenderY = $i;
    }
    
    $X = strpos($LINE, '$');
    if($X) { //FINISH
        $PointX = $X;
        $PointY = $i;
    }
    
    $X = strpos($LINE, 'T');
    if($X && empty($TX)) { //TELEPORT
        $TX = $X;
        $TY = $i;
    } elseif($X && isset($TX)) {
        $T[$i][$X] = array('x'=>$TX, 'y'=>$TY);
        $T[$TY][$TX] = array('x'=>$X, 'y'=>$i);
    }
}
error_log(var_export("\n".implode("\n",$MAP), true));
error_log(var_export($BenderX.' '.$BenderY, true));
error_log(var_export($PointX.' '.$PointY, true));

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));
$HEAD = 'S';
$INVERT = 0;
$BREAK = 0;
$REZ = '';
$MAP_STATUS = array();
$try = 0;
while($MAP[$BenderY][$BenderX] != '$') {
    headModificator($HEAD, $MAP[$BenderY][$BenderX]);
    
    if($MAP[$BenderY][$BenderX] == 'I') { # INVERT MODE --------------
        $INVERT = abs($INVERT-1);
    }
    elseif($MAP[$BenderY][$BenderX] == 'B') { # BREAK MODE --------------
        $BREAK = abs($BREAK-1);
    }
    elseif($MAP[$BenderY][$BenderX] == 'T') {  # TELEPORT --------------
        list($TY, $TX) = array($BenderY, $BenderX);
        $BenderY = $T[$TY][$TX]['y'];
        $BenderX = $T[$TY][$TX]['x'];
    }
    
    if(empty($MAP_STATUS[$BenderY][$BenderX])) { # LOOP check --------------
        $MAP_STATUS[$BenderY][$BenderX]['I'] = $INVERT;
        $MAP_STATUS[$BenderY][$BenderX]['B'] = $BREAK;
        $MAP_STATUS[$BenderY][$BenderX]['H'] = $HEAD;
        $try = 0;
    } else {
        if($MAP_STATUS[$BenderY][$BenderX]['I'] == $INVERT && $MAP_STATUS[$BenderY][$BenderX]['B'] == $BREAK && $MAP_STATUS[$BenderY][$BenderX]['H'] == $HEAD) {
            if($try > 20) {
                echo "LOOP\n"; 
                exit();
            }
            $try++;
        }
    }
    
    if($BREAK) {
        breaker($HEAD, $MAP, $BenderY, $BenderX);
    }
    
    if($INVERT) {
        turnI($HEAD, $MAP, $BenderY, $BenderX, $REZ);
    } else {
        turn($HEAD, $MAP, $BenderY, $BenderX, $REZ);
    }
    
    continue;
}

echo $REZ; 
