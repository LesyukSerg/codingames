<?php
    function attack($factories, $distances)
    {
        $selected = myBiggestFactory($factories['my']);
        //error_log(var_export($selected, true));
        $target = findTarget($selected, $distances, $factories['enemy']);

        if ($selected !== false) {
            return $target;
        } else {
            return false;
        }

    }

    function myBiggestFactory($mine)
    {
        $myFactories = [];

        foreach ($mine as $id => $one) {
            $myFactories[$id] = $one['cyborgs'];
        }

        if (count($myFactories)) {
            arsort($myFactories);

            return array_search(current($myFactories), $myFactories);
        }

        return false;
    }

    function bestProduction($factories)
    {
        $best = [];
        foreach ($factories as $id => $val) {
            $best[$id] = $val['production'];
        }

        arsort($best);

        return array_search(current($best), $best);
    }

    function closestBestProduction($closest, $factories)
    {
        foreach ($closest as $id => $val) {
            $closest[$id] = array(
                'production' => $factories[$id]['production'],
                'difficult'  => $factories[$id]['cyborgs'],
                'distance'   => $val,
            );
            //$closest[$id] = $factories[$id]['cyborgs'];
        }

        $closest = array_msort($closest, array('production' => SORT_DESC, 'distance' => SORT_ASC, 'difficult' => SORT_ASC,));

        //asort($closest);

        return array_search(current($closest), $closest);
    }

    function findTarget($my, $distances, $enemy)
    {
        $N_closest = find5Closest($my, $distances, $enemy);

        do {
            $betterTarget = closestBestProduction($N_closest, $enemy);

            if (count($N_closest) > 1) {
                if (isset($distances[$my . '_' . $betterTarget])) {
                    $dist = $distances[$my . '_' . $betterTarget];
                } else {
                    $dist = $distances[$betterTarget . '_' . $my];
                }
            }

            error_log(var_export(count($N_closest), true));
            error_log(var_export('dist - ' . $dist, true));
            error_log(var_export('target - ' . $betterTarget, true));
            unset($N_closest[$betterTarget]);

        } while ($dist > 5 && count($N_closest) > 1);

        return $betterTarget;
    }

    function find5Closest($my, $distances, $factories)
    {
        $currentDistances = [];
        //error_log(var_export($my, true));

        foreach ($factories as $id => $val) {
            if (isset($distances[$my . '_' . $id])) {
                $currentDistances[$id] = $distances[$my . '_' . $id];
            } else {
                $currentDistances[$id] = $distances[$id . '_' . $my];
            }
        }
        asort($currentDistances);
        //error_log(var_export($currentDistances, true));

        $result = [];
        $left = 3;
        foreach ($currentDistances as $id => $dist) {
            $left--;
            $result[$id] = $dist;

            if (!$left) break;
        }

        //error_log(var_export($result, true));

        return $result;
    }

    function findClosest($my, $distances, $factories)
    {
        $currentDistances = [];

        foreach ($factories as $id => $val) {
            if (isset($distances[$my . '_' . $id])) {
                $currentDistances[$id] = $distances[$my . '_' . $id];
            } else {
                $currentDistances[$id] = $distances[$id . '_' . $my];
            }
        }
        asort($currentDistances);

        return array_search(current($currentDistances), $currentDistances);
    }

    function alarm($bombs)
    {
        if (count($bombs)) {
            $enemy = [];
            foreach ($bombs as $bomb) {
                if ($bomb['distance'] < 3) {
                    $enemy[$bomb['distance']] = $bomb['target'];
                }
            }
            ksort($enemy);

            return current($enemy);

        } else {
            return false;
        }
    }

    function array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) {
                $colarr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }

        return $ret;

    }

    # =================================================================================================================
    # =================================================================================================================
    # =================================================================================================================


    fscanf(STDIN, "%d", $factoryCount);// the number of factories
    fscanf(STDIN, "%d", $linkCount); // the number of links between factories

    $distances = [];
    for ($i = 0; $i < $linkCount; $i++) {
        fscanf(STDIN, "%d %d %d", $fac1, $fac2, $distance);
        $distances[$fac1 . '_' . $fac2] = $distance;
    }

    $gameTurn = 0;
    // game loop
    while (true) {
        $gameTurn++;
        $factories = [];
        $bombs = $troops = [];
        $action = [];

        fscanf(STDIN, "%d", $entityCount); // the number of entities (e.g. factories and troops)

        for ($i = 0; $i < $entityCount; $i++) {
            fscanf(STDIN, "%d %s %d %d %d %d %d",
                $entityId,
                $entityType,
                $owner, //arg1
                $arg2, //arg2
                $arg3, //arg3
                $regen, //arg4
                $arg5 //arg5
            );

            if ($entityType == 'FACTORY') {
                $factory = array(
                    'id'         => $entityId,
                    'cyborgs'    => $arg2,
                    'production' => $arg3,
                );

                if ($owner == 1) { //1 - my
                    $factories['my'][$entityId] = $factory;
                } else {//0 - neutral //-1 - oponent
                    $factories['enemy'][$entityId] = $factory;
                }
            } elseif ($entityType == 'TROOP') {
                $troop = array(
                    'from'     => $arg2,
                    'to'       => $arg3,
                    'cyborgs'  => $regen,
                    'distance' => $arg5
                );

                if ($owner == 1) {
                    $troops['my'][] = $troop;
                } else {
                    $troops['enemy'][] = $troop;
                }

            } elseif ($entityType == 'BOMB') {
                $bomb = array(
                    'target'   => $arg3,
                    'distance' => $regen
                );

                if ($owner == 1) {
                    $bombs['my'][] = $bomb;
                } else {
                    $bombs['enemy'][] = $bomb;
                }
            }
        }

        ##
        ##
        //error_log(var_export($gameTurn, true));

        if (in_array($gameTurn, array(1, 20))) { // SEND BOMB
            $E_ID = bestProduction($factories['enemy']);
            $MY_ID = findClosest($E_ID, $distances, $factories['my']);
            $action[] = 'BOMB ' . $MY_ID . ' ' . $E_ID;
        }

        /*if (isset($bombs['enemy'])) {
            error_log(var_export($bombs['enemy'], true));
            $myID = alarm($bombs['enemy']); // RUN AWAY FROM BOMB
            if ($myID) {
                $closest = findClosest($myID, $distances, $factories['my']);
                $action[] = "MOVE {$myID} {$closest} {$factories['my'][$myID]['cyborgs']}";
            }
        }*/

        /*if (isset($troops['my']) && $gameTurn % 3 == 0) {
            //error_log(var_export($troops['my'], true));

            foreach ($troops['my'] as $troop) {
                unset($factories['neutral'][$troop['to']]);
                unset($factories['enemy'][$troop['to']]);
            }

            //error_log(var_export($factories['neutral'], true));
        }*/

        $move = attack($factories, $distances); // ATTACK

        if ($move !== false) {
            $selected = myBiggestFactory($factories['my']);
            //if($factories['my'][$selected]['production'])

            foreach ($factories['my'] as $from => $factory) {
                if ($factory['cyborgs'] > 13) {
                    $action[] = "INC {$from}";
                }

                $action[] = "MOVE {$from} {$move} {$factory['cyborgs']}";
            }


            /*$cyborgs = $factories['my'][$move['FROM']]['cyborgs'];

            if ($cyborgs > 61) {
                $half = floor($cyborgs / 2);
                $action[] = "MOVE {$move['FROM']} {$move['TO']} {$half}";
                unset($factories['enemy'][$move['TO']]);

                $move = attack($factories, $distances); // ATTACK
                if ($move) {
                    $half = $factories['my'][$move['FROM']]['cyborgs'] - $half;
                    $action[] = "MOVE {$move['FROM']} {$move['TO']} {$half}";
                }
            } else {
                //error_log(var_export($move, true));

                $action[] = "MOVE {$move['FROM']} {$move['TO']} {$cyborgs}";
            }*/
        }


        if (!count($action)) {
            echo "WAIT\n";
        } else {
            echo implode(';', $action) . "\n";
        }

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        // Any valid action, such as "WAIT" or "MOVE source destination cyborgs"

    }
