<?
    $GOAL = array(
        0 => array('x' => 0, 'y' => 3750),
        1 => array('x' => 16000, 'y' => 3750)
    );

    fscanf(STDIN, "%d",
        $myTeamId // if 0 you need to score on the right of the map, if 1 you need to score on the left
    );

    $manna = array(0 => 0, 1 => 0);
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

        $distances = [];

        foreach ($snaffle as $k => $one) {
            $distances[] = get_distance($GOAL[$myTeamId], $one);
        }
        sort($distances);
        $closest = current($distances);

        $myTeam[0]['dist'] = get_distance($GOAL[$myTeamId], $myTeam[0]);
        $myTeam[1]['dist'] = get_distance($GOAL[$myTeamId], $myTeam[1]);

        if ($myTeam[0]['dist'] > $closest || $myTeam[1]['dist'] > $closest) {
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
                    foreach ($snaffle as $k => $one) {
                        $distance = get_distance($GOAL[$myTeamId], $one);
                        $distances[$distance] = $k;
                    }


                } else {
                    foreach ($snaffle as $k => $one) {
                        $distance = get_distance($player, $one);
                        $distances[$distance] = $k;
                    }
                }

                ksort($distances);
                $k = current($distances);
                error_log(var_export($k, true));
                error_log(var_export($snaffle[$k], true));

                $player['dist'] = get_distance($GOAL[$myTeamId], $player);
                $distance = get_distance($GOAL[$myTeamId], $snaffle[$k]);

                if ($player['dist'] > $distance && $manna[$N] > 20) {
                    echo "ACCIO {$snaffle[$k]['entityId']}\n";
                    $manna[$N] -= 20;

                } else {
                    if (isset($snaffle[$k]['x'])) {
                        echo "MOVE {$snaffle[$k]['x']} {$snaffle[$k]['y']} 150 {$player['type']}\n";

                        if (count($snaffle) > 1) {
                            unset($snaffle[$k]);
                        }

                    } else {
                        echo "MOVE {$GOAL[$myTeamId]['x']} {$GOAL[$myTeamId]['y']} 150 {$player['type']}\n";
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
            $manna[$N]++;
        }
    }


    function get_distance($one, $two)
    {
        $d = sqrt(pow($one['x'] - $two['x'], 2) + pow($one['y'] - $two['y'], 2));

        return ceil($d);
    }
