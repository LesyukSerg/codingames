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
        $snaffle = [];

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
                $snaffle[$item['entityId']] = $item;
            }
        }



        $dist[0] = min_distance_to($snaffle, $myTeam[0]);
        $dist[1] = min_distance_to($snaffle, $myTeam[1]);

        if ($dist[0]['item']['entityId'] == $dist[1]['item']['entityId'] && count($snaffle) > 1) {
            unset($snaffle[$dist[0]['item']['entityId']]);

            if ($dist[1]['dist'] > $dist[0]['dist']) {
                $dist[1] = min_distance_to($snaffle, $myTeam[1]);
            } else {
                $dist[0] = min_distance_to($snaffle, $myTeam[0]);
            }
        }

        $closest = min_distance_to($snaffle, $GOAL[$myTeamId]);

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
                $distances = [];

                if ($player['type'] == 'SHEILD') {
                    $closest = min_distance_to($snaffle, $GOAL[$myTeamId]);

                } else {
                    $closest = $dist[$N];
                }

                //error_log(var_export($closest, true));


                $player['dist'] = get_distance($GOAL[$myTeamId], $player);
                $distance = get_distance($GOAL[$myTeamId], $closest['item']);
                //error_log(var_export('player to my - '.$player['dist'], true));
                //error_log(var_export('dot to my - '.$distance, true));


                if ((abs($closest['item']['vx']) > 1500 || $distance < 500) && $manna > 10) {
                    echo "PETRIFICUS {$closest['item']['entityId']}\n";
                    $manna -= 10;

                } elseif ($player['dist'] > $distance && $manna > 20) {
                    echo "ACCIO {$closest['item']['entityId']}\n";
                    $manna -= 20;


                } else {
                    if (isset($closest['item']['x'])) {
                        echo "MOVE {$closest['item']['x']} {$closest['item']['y']} 150\n";

                    } else {
                        echo "MOVE {$GOAL[$myTeamId]['x']} {$GOAL[$myTeamId]['y']} 150\n";
                    }
                }

            } else {
                $gX = $GOAL[$opponent]['x'];
                $gY = $GOAL[$opponent]['y'];
                //error_log(var_export($GOAL[$opponent], true));

                $distanceToGoal = get_distance($GOAL[$opponent], $player);

                echo "THROW {$gX} {$gY} 500\n";
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