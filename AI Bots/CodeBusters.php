<?php
    fscanf(STDIN, "%d", $bustersPerPlayer); // the amount of busters you control
    fscanf(STDIN, "%d", $ghostCount); // the amount of ghosts on the map
    fscanf(STDIN, "%d", $myTeamId); // if this is 0, your base is on the top left of the map, if it is one, on the bottom right

    $lastPos = [];
    $searchPos = [];
    $BusterStunRecharge = [];

    $cleanMap = newMap();

    // game loop
    $ghosts = [];
    while (true) {
        $myBusters = [];
        $enemyBusters = [];

        fscanf(STDIN, "%d", $entities); // the number of busters and ghosts visible to you

        for ($i = 0; $i < $entities; $i++) {
            $some = [];
            fscanf(STDIN, "%d %d %d %d %d %d",
                $some['entityId'], // buster id or ghost id
                $some['x'],
                $some['y'], // position of this buster / ghost
                $some['entityType'], // the team id if it is a buster, -1 if it is a ghost.
                $some['state'], // For busters: 0=idle, 1=carrying a ghost.
                $some['value'] // For busters: Ghost id being carried. For ghosts: number of busters attempting to trap this ghost.
            );

            if ($some['entityType'] == '-1') {
                $ghosts[$some['entityId']] = $some;

            } elseif ($some['entityType'] == $myTeamId) {
                $myBusters[$some['entityId']] = $some;
                if (!isset($BusterStunRecharge[$some['entityId']])) {
                    $BusterStunRecharge[$some['entityId']] = 0;
                } else {
                    if ($BusterStunRecharge[$some['entityId']] > 0) {
                        $BusterStunRecharge[$some['entityId']]--;
                        $BusterStunRecharge[$some['entityId']]--;
                    }
                }

            } else {

                if ($some['state'] == 2 && $some['value'] > 0) {
                } else {
                    $enemyBusters[$some['entityId']] = $some;
                }
            }
        }
        //error_log(var_export($ghosts, true));

        foreach ($myBusters as $bID => $buster) {
            //error_log(var_export($buster, true));
            if ($buster['state'] == 1) { // full ---------------------------------------------------------------------
                if (count($enemyBusters) && false) {
                    $enemyDist = [];
                    foreach ($enemyBusters as $id => $enemy) {
                        $enemyDist[$id] = getDistance($enemy['x'], $enemy['y'], $buster['x'], $buster['y']);
                    }

                    $min = min($enemyDist);
                    if ($min < 1760) {
                        $id = array_search($min, $enemyDist);
                        echo "STUN stop\n";
                        $BusterStunRecharge[$bID] = 20;
                        unset($enemyBusters[$id]);
                    }
                } else {
                    if ($myTeamId) {
                        $baseX = 16000;
                        $baseY = 9000;
                    } else {
                        $baseX = 0;
                        $baseY = 0;
                    }
                    $distanceToBase = getDistance($baseX, $baseY, $buster['x'], $buster['y']);

                    if ($distanceToBase < 1600) {
                        echo "RELEASE\n"; // MOVE x y | BUST id | RELEASE
                    } else {
                        echo "MOVE {$baseX} {$baseY} I'm going home\n"; // MOVE x y | BUST id | RELEASE
                    }
                }

            } else { // empty ----------------------------------------------------------------------------------------
                $stunWork = 0;

                if (count($enemyBusters) && !$BusterStunRecharge[$bID]) {
                    $enemyDist = [];
                    foreach ($enemyBusters as $id => $enemy) {
                        $enemyDist[$id] = getDistance($enemy['x'], $enemy['y'], $buster['x'], $buster['y']);
                    }

                    $min = min($enemyDist);
                    $id = array_search($min, $enemyDist);

                    //error_log(var_export($id . ' ' . $min, true));

                    if (($enemyBusters[$id]['x'] < 14000 || $enemyBusters[$id]['y'] < 7000)) {
                        if ($min < 1760) {
                            error_log(var_export($BusterStunRecharge, true));
                            echo "STUN {$id} you're under arrest\n";
                            $BusterStunRecharge[$bID] = 20;
                            unset($enemyBusters[$id]);
                        } else {
                            echo "MOVE {$enemyBusters[$id]['x']} {$enemyBusters[$id]['y']} I'll catch you\n";
                        }

                        $stunWork = 1;
                    }
                }

                if (!$stunWork) {
                    $distances = [];

                    if (isset($lastPos[$bID])) {
                        if (count($ghosts)) {
                            $strength = [];
                            foreach ($ghosts as $id => $ghost) {
                                $strength[$id] = $ghost['state'];
                            }

                            $min = min($strength);

                            //error_log(var_export($strength, true));
                            //error_log(var_export($min, true));
                            //error_log(var_export($ghosts, true));
                            foreach ($ghosts as $id => $ghost) {
                                if ($ghost['state'] <= $min) {
                                    $distances[$id] = getDistance($ghost['x'], $ghost['y'], $buster['x'], $buster['y']);
                                }
                            }

                            unset($lastPos[$bID], $min);
                            $min = min($distances);

                            $id = array_search($min, $distances);

                            if ($min > 1760) {
                                echo "MOVE {$ghosts[$id]['x']} {$ghosts[$id]['y']} pursuit2\n";
                            } else {
                                echo "BUST {$id} fire\n"; // MOVE x y | BUST id | RELEASE

                                if ($ghosts[$id]['state'] == 1) {
                                    unset($ghosts[$id]);
                                }
                            }

                        } else {
                            if ($lastPos[$bID]['x'] != $buster['x'] && $lastPos[$bID]['y'] != $buster['y']) {
                                echo "MOVE {$lastPos[$bID]['x']} {$lastPos[$bID]['y']} back to pos\n";
                            } else {
                                unset($lastPos[$bID]);
                                drawCleanMap($cleanMap, $buster);
                                $searchPos[$bID] = getCoordinates3($myTeamId, $cleanMap, $searchPos);
                                echo "MOVE {$searchPos[$bID]['x']} {$searchPos[$bID]['y']} search2\n"; // MOVE x y | BUST id | RELEASE
                            }
                        }

                    } else {
                        foreach ($ghosts as $id => $ghost) {
                            $distances[$id] = getDistance($ghost['x'], $ghost['y'], $buster['x'], $buster['y']);
                        }

                        if (count($distances)) {
                            $min = min($distances);
                            $id = array_search($min, $distances);

                            if ($min > 1760) {
                                echo "MOVE {$ghosts[$id]['x']} {$ghosts[$id]['y']} pursuit\n";
                            } else {
                                echo "BUST {$id} fire\n"; // MOVE x y | BUST id | RELEASE
                                $ghosts[$id]['state']--;

                                if ($ghosts[$id]['state'] < 1) {
                                    $lastPos[$bID] = array('x' => $buster['x'], 'y' => $buster['y']);
                                    unset($ghosts[$id]);
                                }
                            }
                        } else {
                            drawCleanMap($cleanMap, $buster);
                            //showMap($cleanMap);

                            if (isset($searchPos[$bID])) {
                                $destination = getDistance($searchPos[$bID]['x'], $searchPos[$bID]['y'], $buster['x'], $buster['y']);
                                if ($destination < 100) {
                                    $searchPos[$bID] = getCoordinates3($myTeamId, $cleanMap, $searchPos);
                                }
                            } else {
                                $searchPos[$bID] = getCoordinates3($myTeamId, $cleanMap, $searchPos);
                            }


                            echo "MOVE {$searchPos[$bID]['x']} {$searchPos[$bID]['y']} search\n"; // MOVE x y | BUST id | RELEASE
                        }
                    }
                }
            }
        }
    }

    function drawCleanMap(&$cleanMap, $buster)
    {
        $rows = count($cleanMap);
        $cells = count($cleanMap[0]);

        $x = intval(floor($buster['x'] / (16000 / $cells)));
        $y = intval(floor($buster['y'] / (9000 / $rows)));
        if ($x > 0) $x--;
        if ($y > 0) $y--;

        $cleanMap[$y][$x] = '#';
    }

    function getCoordinates3($myTeamId, &$cleanMap, $searchPos)
    {
        $rows = count($cleanMap);
        $cells = count($cleanMap[0]);

        if ($myTeamId) {
            $route = array(
                array(array(0, 0), array(2, 0), array(0, 2)),

                array(array(4, 6), array(3, 6), array(3, 7)),  // y, x
                array(array(2, 7), array(2, 6), array(2, 5), array(3, 5), array(4, 5)),
                array(array(4, 4), array(3, 4), array(2, 4), array(1, 4), array(1, 5), array(1, 6), array(1, 7)),
                /*array(array(0, 7), array(0, 6), array(0, 5), array(0, 4), array(0, 3), array(1, 3), array(2, 3), array(3, 3), array(4, 3)),*/
                /*array(array(4, 2), array(3, 2), array(2, 2), array(1, 2), array(0, 2)),
                array(array(0, 1), array(1, 1), array(2, 1), array(3, 1), array(4, 1)),
                array(array(4, 0), array(3, 0), array(2, 0), array(1, 0), array(0, 0))*/
            );

        } else {
            $route = array(
                array(array(4, 7), array(2, 7), array(4, 5)),

                array(array(0, 1), array(1, 1), array(1, 0)),  // y, x
                array(array(2, 0), array(2, 1), array(2, 2), array(1, 2), array(0, 2)),
                array(array(0, 3), array(1, 3), array(2, 3), array(3, 3), array(3, 2), array(3, 1), array(3, 0)),
                /*array(array(4, 0), array(4, 1), array(4, 2), array(4, 3), array(4, 4), array(3, 4), array(2, 4), array(1, 4), array(0, 4)),*/
                /*array(array(0, 5), array(1, 5), array(2, 5), array(3, 5), array(4, 5)),
                array(array(4, 6), array(3, 6), array(2, 6), array(1, 6), array(0, 6)),
                array(array(0, 7), array(1, 7), array(2, 7), array(3, 7), array(4, 7))*/
            );
        }

        foreach ($route as $group) {
            foreach ($group as $pos) {
                // error_log(var_export($pos[0] . ' ' . $pos[1] . ' = ' . $cleanMap[$pos[0]][$pos[1]], true));

                if (!$cleanMap[$pos[0]][$pos[1]]) {
                    $cleanMap[$pos[0]][$pos[1]] = '+';
                    $y = $pos[0] + 1;
                    $x = $pos[1] + 1;
                    $coordinate['x'] = $x * intval(floor(16000 / $cells));
                    $coordinate['y'] = $y * intval(floor(9000 / $rows));
                    //error_log(var_export($y . ' ' . $x, true));
                    //error_log(var_export($coordinate, true));

                    return $coordinate;
                }
            }
        }

        // error_log(var_export('point not found', true));

        if ($myTeamId) { // go to enemy base
            $coordinate['y'] = 1000;
            $coordinate['x'] = 1000;
        } else {
            $coordinate['y'] = 8000;
            $coordinate['x'] = 15000;
        }

        $already = 0;
        foreach ($searchPos as $pos) {
            if ($pos['x'] == $coordinate['x'] && $pos['y'] == $coordinate['y']) {
                $already = 1;
                break;
            }
        }

        if ($already) { // continue search
            if ($myTeamId) {
                $route = array(
                    array(array(0, 7), array(0, 6), array(0, 5), array(0, 4), array(0, 3), array(1, 3), array(2, 3), array(3, 3), array(4, 3)),
                    array(array(4, 2), array(3, 2), array(2, 2), array(1, 2), array(0, 2)),
                    array(array(0, 1), array(1, 1), array(2, 1), array(3, 1), array(4, 1)),
                    array(array(4, 0), array(3, 0), array(2, 0), array(1, 0), array(0, 0))
                );

            } else {
                $route = array(
                    array(array(4, 0), array(4, 1), array(4, 2), array(4, 3), array(4, 4), array(3, 4), array(2, 4), array(1, 4), array(0, 4)),
                    array(array(0, 5), array(1, 5), array(2, 5), array(3, 5), array(4, 5)),
                    array(array(4, 6), array(3, 6), array(2, 6), array(1, 6), array(0, 6)),
                    array(array(0, 7), array(1, 7), array(2, 7), array(3, 7), array(4, 7))
                );
            }

            foreach ($route as $group) {
                foreach ($group as $pos) {
                    //error_log(var_export($pos[0] . ' ' . $pos[1] . ' = ' . $cleanMap[$pos[0]][$pos[1]], true));

                    if (!$cleanMap[$pos[0]][$pos[1]]) {
                        $cleanMap[$pos[0]][$pos[1]] = '+';
                        $y = $pos[0] + 1;
                        $x = $pos[1] + 1;
                        $coordinate['x'] = $x * intval(floor(16000 / $cells));
                        $coordinate['y'] = $y * intval(floor(9000 / $rows));

                        return $coordinate;
                    }
                }
            }
        }


        return $coordinate;
    }

    function getDistance($X1, $Y1, $X2, $Y2)
    {
        return sqrt(pow($X1 - $X2, 2) + pow($Y1 - $Y2, 2));
    }

    function showMap($cleanMap)
    {
        foreach ($cleanMap as $line) {
            error_log(var_export(implode('|', $line), true));
        }
    }

    function newMap()
    {
        return array(
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0),
        );
    }
