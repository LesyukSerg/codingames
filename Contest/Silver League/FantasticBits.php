<?
    $GOAL = array(
        0 => array('x' => 0, 'y' => 3750),
        1 => array('x' => 16000, 'y' => 3750)
    );

    fscanf(STDIN, "%d",
        $myTeamId // if 0 you need to score on the right of the map, if 1 you need to score on the left
    );

    $manna = 0;
    $opponent = abs($myTeamId - 1);
    // game loop
    while (true) {
        $myTeam = [];
        $opponentTeam = [];
        $bludger = [];
        $snaffles = [];

        fscanf(STDIN, "%d",
            $entities // number of entities still in game
        );
        for ($i = 0; $i < $entities; $i++) {
            $item = [];

            fscanf(STDIN, "%d %s %d %d %d %d %d",
                $item['entityId'], // entity identifier
                $item['entityType'], // "WIZARD", "OPPONENT_WIZARD" or "SNAFFLE" (or "BLUDGER" after first league)
                $item['x'], // position
                $item['y'], // position
                $item['vx'], // velocity
                $item['vy'], // velocity
                $item['state'] // 1 if the wizard is holding a Snaffle, 0 otherwise
            );

            if ($item['entityType'] == 'WIZARD') {
                if (!isset($myTeam['SHEILD'])) {
                    $myTeam[] = $item;
                } else {
                    $myTeam[] = $item;
                }

            } elseif ($item['entityType'] == 'OPPONENT_WIZARD') {
                $opponentTeam[$item['entityId']] = $item;

            } elseif ($item['entityType'] == 'BLUDGER') {
                $bludger[$item['entityId']] = $item;

            } elseif ($item['entityType'] == 'SNAFFLE') {
                $snaffles[$item['entityId']] = $item;
            }
        }


        $dist[0] = min_distance_to($snaffles, $myTeam[0]);
        $dist[1] = min_distance_to($snaffles, $myTeam[1]);

        if ($dist[0]['item']['entityId'] == $dist[1]['item']['entityId'] && count($snaffles) > 1) {
            unset($snaffles[$dist[0]['item']['entityId']]);

            if ($dist[1]['dist'] > $dist[0]['dist']) {
                $dist[1] = min_distance_to($snaffles, $myTeam[1]);
            } else {
                $dist[0] = min_distance_to($snaffles, $myTeam[0]);
            }
        }

        $closest = min_distance_to($snaffles, $GOAL[$myTeamId]);

        $myTeam[0]['dist'] = get_distance($GOAL[$myTeamId], $myTeam[0]);
        $myTeam[1]['dist'] = get_distance($GOAL[$myTeamId], $myTeam[1]);

        if ($myTeam[0]['dist'] > $closest['dist'] || $myTeam[1]['dist'] > $closest['dist']) {
            if ($myTeam[0]['dist'] > $myTeam[1]['dist']) {
                $myTeam[1]['type'] = 'SHEILD';
                $myTeam[0]['type'] = 'ATTACK';
            } else {
                $myTeam[0]['type'] = 'SHEILD';
                $myTeam[1]['type'] = 'ATTACK';
            }
        } else {
            $myTeam[0]['type'] = 'ATTACK';
            $myTeam[1]['type'] = 'ATTACK';
        }

        foreach ($myTeam as $N => $player) {
            //error_log(var_export($player, true));
            if (!$player['state']) {
                $sVX = [];
                foreach ($snaffles as $k => $one) {
                    $sVX[$k] = abs($one['vx']);
                }
                $maxSpeed = max($sVX);
                $k = array_search($maxSpeed, $sVX);
                $fastestDist = get_distance($snaffles[$k], $GOAL[$myTeamId]);

                if ($fastestDist > 2000 && $fastestDist < 8000 && $maxSpeed > 1500 && $manna > 10) { //STOP
                    error_log(var_export($fastestDist, true));


                    echo "PETRIFICUS {$k} STOP\n";
                    $manna -= 10;

                } else {
                    $distances = [];

                    if ($player['type'] == 'SHEILD') {
                        $closest = min_distance_to($snaffles, $GOAL[$myTeamId]);

                    } else {
                        $closest = $dist[$N];
                    }

                    //error_log(var_export($closest, true));


                    $player['dist'] = get_distance($GOAL[$myTeamId], $player);
                    $distance = get_distance($GOAL[$myTeamId], $closest['item']);
                    //error_log(var_export('player to my - '.$player['dist'], true));
                    //error_log(var_export('dot to my - '.$distance, true));

                    if ($player['dist'] - $distance > 2000 && $manna > 20) {
                        echo "ACCIO {$closest['item']['entityId']}\n";
                        $manna -= 20;

                    } else {
                        if ($manna > 20 && $distance - $player['dist'] < 2000 && (($closest['item']['x']-$player['x'] > 500  && !$myTeamId) || ($player['x']-$closest['item']['x']>500 && $myTeamId))) {
                            echo "FLIPENDO {$closest['item']['entityId']}\n";
                            $manna -= 20;

                        } elseif (isset($closest['item']['x'])) {
                            echo "MOVE {$closest['item']['x']} {$closest['item']['y']} 150\n";

                        } else {
                            echo "MOVE {$GOAL[$myTeamId]['x']} {$GOAL[$myTeamId]['y']} 150\n";
                        }
                    }
                }

            } else {
                $gX = $GOAL[$opponent]['x'];
                $gY = $GOAL[$opponent]['y'];
                //error_log(var_export($GOAL[$opponent], true));

                $distanceToGoal = get_distance($GOAL[$opponent], $player);

                echo "THROW {$gX} {$gY} 500 PASS\n";
            }
            // Write an action using echo(). DON'T FORGET THE TRAILING \n
            // To debug (equivalent to var_dump): error_log(var_export($var, true));


            // Edit this line to indicate the action for each wizard (0 ≤ thrust ≤ 150, 0 ≤ power ≤ 500)
            // i.e.: "MOVE x y thrust" or "THROW x y power"
            $manna++;
        }
    }


    function get_distance($one, $two)
    {
        $d = sqrt(pow($one['x'] - $two['x'], 2) + pow($one['y'] - $two['y'], 2));

        return ceil($d);
    }

    function min_distance_to($elements, $item)
    {
        $distances = [];

        foreach ($elements as $k => $one) {
            $distances[$k] = get_distance($item, $one);
        }
        asort($distances);
        $closest = current($distances);
        $k = array_search($closest, $distances);

        return array('dist' => $closest, 'item' => $elements[$k]);
    }