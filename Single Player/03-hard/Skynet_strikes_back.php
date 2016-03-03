<?php
    fscanf(STDIN, "%d %d %d",
        $N, // the total number of nodes in the level, including the gateways
        $L, // the number of links
        $E // the number of exit gateways
    );

    $nodeToG = $GATEWAY = $NODES = [];

    for ($i = 0; $i < $L; $i++) {
        fscanf(STDIN, "%d %d", $N1, $N2); // N1 and N2 defines a link between these nodes
        $NODES[$N1 . '_' . $N2] = array(1 => $N1, 2 => $N2);
    }

    for ($i = 0; $i < $E; $i++) {
        fscanf(STDIN, "%d", $EI); // the index of a gateway node
        $GATEWAY[] = $EI;

        foreach ($NODES as $N) {
            if ($N[1] == $EI) {
                $nodeToG[$N[2]][$EI] = 'G';

            } elseif ($N[2] == $EI) {
                $nodeToG[$N[1]][$EI] = 'G';
            }
        }
    }

// game loop
    while (true) {
        $found = 0;
        $NODE = false;
        fscanf(STDIN, "%d", $SI); // The index of the node on which the Skynet agent is positioned this turn

        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        error_log(var_export('Skynet - ' . $SI, true));

        $NODE = closeImmediately($GATEWAY, $NODES, $SI);


        if ($NODE) {
            error_log(var_export('closeImmediately', true));
            unsetFromArrays($NODES, $nodeToG, $NODE[1], $NODE[2]);
            echo($NODE[1] . " " . $NODE[2] . "\n");

        } else {
            $NODE = findMultiG($SI, $NODES, $GATEWAY, $nodeToG);

            if ($NODE) {
                error_log(var_export('findMultiG', true));
                unsetFromArrays($NODES, $nodeToG, $NODE[1], $NODE[2]);
                echo($NODE[1] . " " . $NODE[2] . "\n");

            } else {
                $K = closestNodes($NODES, $GATEWAY, $SI);
                error_log(var_export($K, true));

                if ($K) {
                    foreach ($K as $key => $kk1) {
                        $kk1 = explode('_', $kk1);
                        foreach ($K as $kk2) {
                            $kk2 = explode('_', $kk2);

                            $rr = array_diff($kk1, $kk2);

                            if (count($rr) == 1) break;
                        }

                        if (count($rr) == 1) break;
                    }

                    echo($NODES[$K[$key]][1] . " " . $NODES[$K[$key]][2] . "\n");
                    unset($NODES[$K[$key]]);

                    $found = 1;
                }
            }
        }
    }

    # ========================================================================
    # ========================================================================
    # ========================================================================

    function closeImmediately(&$GATEWAY, &$NODES, $SI)
    {
        foreach ($GATEWAY as $G) {
            if (isset($NODES[$SI . '_' . $G])) {
                return $NODES[$SI . '_' . $G];

            } elseif (isset($NODES[$G . '_' . $SI])) {
                return $NODES[$G . '_' . $SI];
            }
        }

        return 0;
    }


    function findMultiG($SI, &$NODES, &$GATEWAY, &$nodeToG)
    {
        $K = [];

        foreach ($NODES as $N) {
            if ($N[1] == $SI) {
                findClosestMulti($N[2], $GATEWAY, $NODES, $K);

            } elseif ($N[2] == $SI) {
                findClosestMulti($N[1], $GATEWAY, $NODES, $K);
            }
        }

        if (count($K)) {
            foreach ($K as $G1 => $G) {
                if (count($G) > 1) {
                    foreach ($G as $N => $v) {
                        return array(1 => $G1, 2 => $N);
                    }
                }
            }
        } else {
            //error_log(var_export($nodeToG, true));
            foreach ($nodeToG as $G1 => $G) {
                if (count($G) > 1) {
                    foreach ($G as $N => $v) {
                        return array(1 => $G1, 2 => $N);
                    }
                }
            }
        }

        return 0;
    }

    function closestNodes(&$NODES, &$GATEWAY, $SI)
    {
        $K = [];

        foreach ($NODES as $N) {
            if ($N[1] == $SI) {
                $K = array_merge($K, findClosestG($N[2], $GATEWAY, $NODES));

            } elseif ($N[2] == $SI) {
                $K = array_merge($K, findClosestG($N[1], $GATEWAY, $NODES));
            }
        }
        error_log(var_export('closest', true));
        error_log(var_export($K, true));

        return $K;
    }

    function findClosestG($N, &$GATEWAY, &$NODES)
    {
        $rez = [];

        foreach ($GATEWAY as $G) {
            if (isset($NODES[$N . '_' . $G])) {
                $rez[] = $N . '_' . $G;
            } elseif (isset($NODES[$G . '_' . $N])) {
                $rez[] = $G . '_' . $N;
            }
        }

        return $rez;
    }

    function findClosestMulti($N, &$GATEWAY, &$NODES, &$rez)
    {
        foreach ($GATEWAY as $G) {
            if (isset($NODES[$N . '_' . $G])) {
                $rez[$N][$G] = 'G';
            } elseif (isset($NODES[$G . '_' . $N])) {
                $rez[$N][$G] = 'G';
            }
        }
    }

    function unsetFromArrays(&$NODES, &$nodeToG, $N1, $N2)
    {
        if (isset($NODES[$N1 . '_' . $N2])) {
            unset($NODES[$N1 . '_' . $N2]);

        } elseif (isset($NODES[$N2 . '_' . $N1])) {
            unset($NODES[$N2 . '_' . $N1]);
        }

        if (isset($nodeToG[$N1][$N2])) {
            unset($nodeToG[$N1][$N2]);

        } elseif (isset($nodeToG[$N2][$N1])) {
            unset($nodeToG[$N2][$N1]);
        }
    }



