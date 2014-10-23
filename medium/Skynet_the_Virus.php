<?php

fscanf(STDIN, "%d %d %d",
    $N, // the total number of nodes in the level, including the gateways
    $L, // the number of links
    $E // the number of exit gateways
);

$BLOCKED = array();
$NODES = array();
$GATEWAY = array();

for ($i = 0; $i < $L; $i++) {
    fscanf(STDIN, "%d %d",
        $N1, // N1 and N2 defines a link between these nodes
        $N2
    );
    $NODES[$N1.'_'.$N2] = array(1 => $N1, 2 => $N2);
}

for ($i = 0; $i < $E; $i++) {
    fscanf(STDIN, "%d",
        $EI // the index of a gateway node
    );
    $GATEWAY[] = $EI;
}

// game loop
while (TRUE)
{
   // error_log(var_export('0000', true));
    fscanf(STDIN, "%d",
        $SI // The index of the node on which the Skynet agent is positioned this turn
    );
    
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    error_log(var_export('Skynet - '.$SI, true));
   
    foreach($GATEWAY as $G){
        if(isset($NODES[$SI.'_'.$G])) {
            $NODE = $NODES[$SI.'_'.$G];
            break;
        } elseif (isset($NODES[$G.'_'.$SI])) {
            $NODE = $NODES[$G.'_'.$SI];
            break;
        } else {
            $NODE = false;
        }
    }
    
    if ($NODE && !isset($BLOCKED[$NODE[1].'_'.$NODE[2]])) {
       //$BLOCKED[$NODE[1].'_'.$NODE[2]] = 1;
        
        echo($NODE[1]." ".$NODE[2]."\n");
        
    } else {
        foreach($NODES as $N){
            if(($N[1] == $EI || $N[2] == $EI) && !isset($BLOCKED[$N[1].'_'.$N[2]])) {
               $BLOCKED[$N[1].'_'.$N[2]] = 1;
               
               echo($N[1]." ".$N[2]."\n");
               break;
            }
        }
    }
    
    // echo("$SI $EI\n"); // Example: 0 1 are the indices of the nodes you wish to sever the link between
}
?>