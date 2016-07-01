<?php
    fscanf(STDIN, "%d", $bustersPerPlayer); // the amount of busters you control
    fscanf(STDIN, "%d", $ghostCount); // the amount of ghosts on the map
    fscanf(STDIN, "%d", $myTeamId); // if this is 0, your base is on the top left of the map, if it is one, on the bottom right

    $lastPos = [];
    $cleanMap = [];
    $searchPos = [];
    $Stun = [];
    $BusterStun = [];

    if ($myTeamId) {
        $cleanMap = array(
            array(0, 0, 0, 0, 0, 0, 0, 0, '#'),
            array(0, 0, 0, 0, 0, 0, 0, 0, '#'),
            array(0, 0, 0, 0, 0, 0, 0, 0, '#'),
            array(0, 0, 0, 0, 0, 0, 0, 0, '#'),
            array('#', '#', '#', '#', '#', '#', '#', '#', '#'),
        );
    } else {
        $cleanMap = array(
            array('#', '#', '#', '#', '#', '#', '#', '#', '#'),
            array('#', 0, 0, 0, 0, 0, 0, 0, 0),
            array('#', 0, 0, 0, 0, 0, 0, 0, 0),
            array('#', 0, 0, 0, 0, 0, 0, 0, 0),
            array('#', 0, 0, 0, 0, 0, 0, 0, 0),
        );
    }

    // game loop
    $ghosts = [];
    while (true) {
        $myBusters = [];
        $enemyBusters = [];

        fscanf(STDIN, "%d", $entities); // the number of busters and ghosts visible to you

        foreach ($Stun as $id => $s) {
            $Stun[$id]--;
        }

        for ($i = 0; $i < $entities; $i++) {
            $something = [];
            fscanf(STDIN, "%d %d %d %d %d %d",
                $something['entityId'], // buster id or ghost id
                $something['x'],
                $something['y'], // position of this buster / ghost
                $something['entityType'], // the team id if it is a buster, -1 if it is a ghost.
                $something['state'], // For busters: 0=idle, 1=carrying a ghost.
                $something['value'] // For busters: Ghost id being carried. For ghosts: number of busters attempting to trap this ghost.
            );

            if ($something['entityType'] == '-1') {
                $ghosts[$something['entityId']] = $something;

            } elseif ($something['entityType'] == $myTeamId) {
                $myBusters[$something['entityId']] = $something;
                if (!isset($BusterStun[$something['entityId']])) {
                    $BusterStun[$something['entityId']] = 0;
                } else {
                    if ($BusterStun[$something['entityId']] > 0) {
                        $BusterStun[$something['entityId']]--;
                    }
                }

            } else {
                if (isset($Stun[$something['entityId']]) && $Stun[$something['entityId']] > 0) {
                } else {
                    unset($Stun[$something['entityId']]);
                    $enemyBusters[$something['entityId']] = $something;
                }
            }
        }
        //error_log(var_export($ghosts, true));

        foreach ($myBusters as $bID => $buster) {
            error_log(var_export($buster, true));
            if ($buster['state'] == 1) {
                if (count($enemyBusters) && false) {
                    $enemyDist = [];
                    foreach ($enemyBusters as $id => $enemy) {
                        $enemyDist[$id] = getDistance($enemy['x'], $enemy['y'], $buster['x'], $buster['y']);
                    }

                    $min = min($enemyDist);
                    if ($min < 1760) {
                        $id = array_search($min, $enemyDist);
                        echo "STUN stop\n";
                        $Stun[$id] = 10;
                        $BusterStun[$bID] = 20;
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

                    if ($distanceToBase < 1500) {
                        echo "RELEASE\n"; // MOVE x y | BUST id | RELEASE
                    } else {
                        echo "MOVE {$baseX} {$baseY} go home\n"; // MOVE x y | BUST id | RELEASE
                    }
                }

            } else {
                $stunWork = 0;
                if (count($enemyBusters) && !$BusterStun[$bID]) {
                    $enemyDist = [];
                    foreach ($enemyBusters as $id => $enemy) {
                        $enemyDist[$id] = getDistance($enemy['x'], $enemy['y'], $buster['x'], $buster['y']);
                    }

                    $min = min($enemyDist);
                    $id = array_search($min, $enemyDist);

                    $enemyDist = [];
                    foreach ($enemyBusters as $id => $enemy) {
                        $enemyDist[$id] = getDistance($enemy['x'], $enemy['y'], $buster['x'], $buster['y']);
                    }

                    if ($enemyBusters[$id]['x'] < 14000 && $enemyBusters[$id]['y'] < 7000 && $min < 1760) {
                        echo "STUN {$id} stop\n";
                        $Stun[$id] = 10;
                        $BusterStun[$bID] = 20;
                        unset($enemyBusters[$id]);
                        $stunWork = 1;
                    }
                }

                if (!$stunWork) {
                    $distances = [];

                    if (isset($lastPos[$bID])) {
                        foreach ($ghosts as $id => $ghost) {
                            $distances[$id] = getDistance($ghost['x'], $ghost['y'], $buster['x'], $buster['y']);
                        }

                        if (count($distances)) {
                            unset($lastPos[$bID]);
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
                                $cleanMap = drawCleanMap($cleanMap, $buster);
                                $searchPos[$bID] = getCoordinates2($myTeamId, $cleanMap, $buster);
                                echo "MOVE {$searchPos[$bID]['x']} {$searchPos[$bID]['y']} search\n"; // MOVE x y | BUST id | RELEASE
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
                            $cleanMap = drawCleanMap($cleanMap, $buster);

                            if (isset($searchPos[$bID])) {
                                $destination = getDistance($searchPos[$bID]['x'], $searchPos[$bID]['y'], $buster['x'], $buster['y']);
                                if ($destination < 100) {
                                    $searchPos[$bID] = getCoordinates2($myTeamId, $cleanMap, $buster);
                                }
                            } else {
                                $searchPos[$bID] = getCoordinates2($myTeamId, $cleanMap, $buster);
                            }


                            echo "MOVE {$searchPos[$bID]['x']} {$searchPos[$bID]['y']} search\n"; // MOVE x y | BUST id | RELEASE
                        }
                    }
                }
            }
        }
    }

    function drawCleanMap($cleanMap, $buster)
    {
        $x = floor($buster['x'] / 3200);
        $y = floor($buster['y'] / 2250);

        if ($cleanMap[$y][$x] === 0 || $cleanMap[$y][$x] == '+') {
            $cleanMap[$y][$x] = '#';
        }

        return $cleanMap;
    }

    function getCoordinates2($myTeamId, &$cleanMap, $buster)
    {
        $rows = count($cleanMap) - 1;
        $cells = count($cleanMap[0]) - 1;

        if ($myTeamId) {
            # ----
            # ----
            # --++
            # --++
            for ($y = $rows - 1; $y > $rows / 2; $y--) {
                if ($y % 2 == 1) {
                    for ($x = $cells - 1; $x > $cells / 2; $x--) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                } else {
                    for ($x = $cells / 2; $x < $cells; $x++) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                }
            }

            # ----
            # ----
            # ++--
            # ++--
            for ($y = $rows - 1; $y > $rows / 2; $y--) {
                if ($y % 2 == 1) {
                    for ($x = $cells / 2; $x > 0; $x--) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                } else {
                    for ($x = 1; $x < $cells / 2; $x++) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                }
            }

            # --++
            # --++
            # ----
            # ----
            for ($y = $rows / 2; $y > 0; $y--) {
                if ($y % 2 == 1) {
                    for ($x = $cells - 1; $x > $cells / 2; $x--) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                } else {
                    for ($x = $cells / 2; $x < $cells; $x++) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                }
            }
        } else {
            # ++--
            # ++--
            # ----
            # ----
            for ($y = 1; $y < $rows / 2; $y++) {
                if ($y % 2 == 1) {
                    for ($x = 1; $x < $cells / 2; $x++) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                } else {
                    for ($x = $cells / 2; $x > 0; $x--) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                }
            }

            # --++
            # --++
            # ----
            # ----
            for ($y = 1; $y < $rows / 2; $y++) {
                if ($y % 2 == 1) {
                    for ($x = $cells / 2; $x < $cells; $x++) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                } else {
                    for ($x = $cells - 1; $x > $cells / 2; $x--) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                }
            }

            # ----
            # ----
            # ++--
            # ++--
            for ($y = $rows / 2; $y < $rows; $y++) {
                if ($y % 2 == 1) {
                    for ($x = 1; $x < $cells / 2; $x++) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                } else {
                    for ($x = $cells / 2; $x > 0; $x--) {
                        if (!$cleanMap[$y][$x]) {
                            $cleanMap[$y][$x] = '+';
                            $pos['x'] = $x * floor(16000 / $cells);
                            $pos['y'] = $y * floor(9000 / $rows);

                            return $pos;
                        }
                    }
                }
            }
        }

        showMap($cleanMap);

        if ($myTeamId) {
            $cleanMap = array(
                array(0, 0, 0, 0, 0, 0, 0, 0, '#'),
                array(0, 0, 0, 0, 0, 0, 0, 0, '#'),
                array(0, 0, 0, 0, 0, 0, 0, 0, '#'),
                array(0, 0, 0, 0, 0, 0, 0, 0, '#'),
                array('#', '#', '#', '#', '#', '#', '#', '#', '#'),
            );
        } else {
            $cleanMap = array(
                array('#', '#', '#', '#', '#', '#', '#', '#', '#'),
                array('#', 0, 0, 0, 0, 0, 0, 0, 0),
                array('#', 0, 0, 0, 0, 0, 0, 0, 0),
                array('#', 0, 0, 0, 0, 0, 0, 0, 0),
                array('#', 0, 0, 0, 0, 0, 0, 0, 0),
            );
        }

        return getCoordinates2($myTeamId, $cleanMap, $buster);
    }

    function getDistance($X1, $Y1, $X2, $Y2)
    {
        return sqrt(pow($X1 - $X2, 2) + pow($Y1 - $Y2, 2));
    }

    function showMap($cleanMap)
    {
        foreach ($cleanMap as $line) {
            error_log(var_export(implode(' | ', $line), true));
            error_log(var_export('----------------------', true));
        }
    }

