<?php
    /**
     * Grab Snaffles and try to throw them through the opponent's goal!
     * Move towards a Snaffle and use your team id to determine where you need to throw it.
     **/

    fscanf(STDIN, "%d",
        $myTeamId // if 0 you need to score on the right of the map, if 1 you need to score on the left
    );

    $GOAL = array(
        0 => array('x' => 0, 'y' => 3750),
        1 => array('x' => 16000, 'y' => 3750)
    );

    $team = [];
    $opponent = abs($myTeamId - 1);

    // game loop
    while (true) {
        $myTeam = [];
        $opponentTeam = [];
        $snaffle = [];

        fscanf(STDIN, "%d %d",
            $myScore,
            $myMagic
        );
        fscanf(STDIN, "%d %d",
            $opponentScore,
            $opponentMagic
        );
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
                    $myTeam['SHEILD'] = $item;
                } else {
                    $myTeam['SPRINTER'] = $item;
                }

            } elseif ($item['entityType'] == 'OPPONENT_WIZARD') {
                $opponentTeam[] = $item;

            } elseif ($item['entityType'] == 'SNAFFLE') {
                $snaffle[] = $item;
            }
        }

        $myTeam['SHEILD']['dist'] = get_distance($GOAL[$myTeamId], $myTeam['SHEILD']);
        $myTeam['SPRINTER']['dist'] = get_distance($GOAL[$myTeamId], $myTeam['SPRINTER']);

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        if ($myTeam['SPRINTER']['dist'] < $myTeam['SHEILD']['dist']) {
            list($myTeam['SPRINTER'], $myTeam['SHEILD']) = array($myTeam['SPRINTER'], $myTeam['SHEILD']);
        }

        $myTeam['SHEILD']['dist'] = get_distance($GOAL[$myTeamId], $myTeam['SHEILD']);
        $myTeam['SPRINTER']['dist'] = get_distance($GOAL[$myTeamId], $myTeam['SPRINTER']);

        if ($myTeam['SPRINTER']['dist'] < $myTeam['SHEILD']['dist']) {
            list($myTeam['SPRINTER'], $myTeam['SHEILD']) = array($myTeam['SPRINTER'], $myTeam['SHEILD']);
        }


        foreach ($myTeam as $type => $player) {
            //error_log(var_export($player, true));
            if (!$player['state']) {
                $distances = [];

                foreach ($snaffle as $k => $one) {
                    if ($type == 'SHEILD') {
                        $distance = get_distance($GOAL[$myTeamId], $one);
                    } else {
                        $distance = get_distance($player, $one);
                    }


                    $distances[$distance] = $k;
                    //error_log(var_export($snaffle[$k]['entityId']." = ".$distance, true));
                }
                ksort($distances);
                $team[$type] = $k = current($distances);
                //error_log(var_export($snaffle, true));

                if (isset($snaffle[$k]['x'])) {
                    echo("MOVE {$snaffle[$k]['x']} {$snaffle[$k]['y']} 150\n");
                } else {
                    echo("MOVE {$GOAL[$myTeamId]['x']} {$GOAL[$myTeamId]['y']} 150\n");
                }


            } else {
                $gX = $GOAL[$opponent]['x'];
                $gY = $GOAL[$opponent]['y'];
                //error_log(var_export($GOAL[$opponent], true));

                $distanceToGoal = get_distance($GOAL[$opponent], $player);

                echo("THROW {$gX} {$gY} 500\n");
            }
            // Write an action using echo(). DON'T FORGET THE TRAILING \n
            // To debug (equivalent to var_dump): error_log(var_export($var, true));


            // Edit this line to indicate the action for each wizard (0 ≤ thrust ≤ 150, 0 ≤ power ≤ 500)
            // i.e.: "MOVE x y thrust" or "THROW x y power"

        }
    }

    function get_distance($one, $two)
    {
        $d = sqrt(pow($one['x'] - $two['x'], 2) + pow($one['y'] - $two['y'], 2));

        return ceil($d);
    }
