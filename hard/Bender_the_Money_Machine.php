<?php
$timestart = microtime(1);
fscanf(STDIN, "%d", $N);

$ROOMS = array();
for ($i = 0; $i < $N; $i++){
    $room = stream_get_line(STDIN, 256, "\n");
    $room = explode(' ', $room);
    
    $ROOMS[$room[0]] = array(
        'cost' => $room[1],
        '0' => $room[2],
        '1' => $room[3],
        'total' => 0,
      //  'flag' => 0
    );
}
$ROOMS['E']['total'] = 0;
$ROOMS['E']['cost'] = 0;
$ROOMS[0]['total'] = $ROOMS[0]['cost'];

error_log(var_export('filling array - '.(microtime(1)-$timestart), true));

echo gextri($ROOMS)."\n";

error_log(var_export('total - '.(microtime(1)-$timestart), true));
/*
// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));
*/

# ====================================================================
function gextri($ROOMS)
{
    $timestart = microtime(1);
    $cnt = count($ROOMS)-1;
    for($i=0; $i<$cnt; $i++) {
        $r = $ROOMS[$i][0];
        $ROOMS[$r]['total'] = calcCost($ROOMS, $i, 0);
        
        $r = $ROOMS[$i][1];
        $ROOMS[$r]['total'] = calcCost($ROOMS, $i, 1);
    }
    
    error_log(var_export('calculate cost - '.(microtime(1)-$timestart), true));
    
    return searchMax($ROOMS);
}

function calcCost($ROOMS, $i, $N)
{
    $r = $ROOMS[$i][$N];
    $sum = $ROOMS[$i]['total'] + $ROOMS[$r]['cost'];
        
    if($ROOMS[$r]['total'] < $sum)
        return $sum;

    return $ROOMS[$r]['total'];
}

function searchMax($ROOMS) {
    $timestart = microtime(1);
    $REZ = array();
    
    $cnt = count($ROOMS)-1;
    for($i=0; $i<$cnt; $i++){
        $REZ[] = $ROOMS[$i]['total'];
    }
    
    error_log(var_export('search max - '.(microtime(1)-$timestart), true));
    return max($REZ);
}
# ====================================================================