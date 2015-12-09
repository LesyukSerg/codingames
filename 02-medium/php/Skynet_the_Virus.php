<?php
    fscanf(STDIN, "%d %d %d",
        $N, // the total number of nodes in the level, including the gateways
        $L, // the number of links
        $E // the number of exit gateways
    );

    $GATEWAY = $NODES = [];

    for ($i = 0; $i < $L; $i++) {
        fscanf(STDIN, "%d %d", $N1, $N2); // N1 and N2 defines a link between these nodes
        $NODES[$N1 . '_' . $N2] = array(1 => $N1, 2 => $N2);
    }

    for ($i = 0; $i < $E; $i++) {
        fscanf(STDIN, "%d", $EI); // the index of a gateway node
        $GATEWAY[] = $EI;
    }
    error_log(var_export($GATEWAY, true));

    // game loop
    while (true) {
        $NODE = 0;
        fscanf(STDIN, "%d", $SI); // The index of the node on which the Skynet agent is positioned this turn

        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        error_log(var_export('Skynet - ' . $SI, true));

        foreach ($GATEWAY as $G) {
            if (isset($NODES[$SI . '_' . $G])) {
                $NODE = $NODES[$SI . '_' . $G];
                break;
            } elseif (isset($NODES[$G . '_' . $SI])) {
                $NODE = $NODES[$G . '_' . $SI];
                break;
            } else {
                $NODE = false;
            }
        }
        error_log(var_export('NODE', true));
        error_log(var_export($NODE, true));

        if ($NODE) {
            unset($NODES[$NODE[1] . '_' . $NODE[2]]);
            echo($NODE[1] . " " . $NODE[2] . "\n");

        } else {
            foreach ($NODES as $N) {
                if ($N[1] == $SI || $N[2] == $SI) {
                    unset($NODES[$N[1] . '_' . $N[2]]);
                    echo($N[1] . " " . $N[2] . "\n");
                    break;
                }
            }
        }
    }
