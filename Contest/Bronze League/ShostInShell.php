<?php
    function attack($factories, $distances)
    {
        $selected = myBiggestFactory($factories['my']);
        $target = findTarget($selected, $distances, $factories['neutral'], $factories['neutral']);

        if ($selected) {
            return $selected . ' ' . $target . ' ' . $factories['my'][$selected]['cyborgs'];
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
        //error_log(var_export($factories, true));
        //error_log(var_export($closest, true));
        foreach ($closest as $id => $val) {
            $closest[$id] = $factories[$id]['production'];
        }
        arsort($closest);

        return array_search(current($closest), $closest);
    }

    function findTarget($my, $distances, $neutral, $enemy)
    {
        $N_closest = find5Closest($my, $distances, $neutral);

        if (!count($N_closest)) {
            $E_closest = find5Closest($my, $distances, $enemy);
            $betterTarget = closestBestProduction($E_closest, $enemy);

        } else {
            //error_log(var_export($N_closest, true));
            $betterTarget = closestBestProduction($N_closest, $neutral);
        }

        return $betterTarget;
    }

    function find5Closest($my, $distances, $factories)
    {
        $currentDistances = [];
        //error_log(var_export($factories, true));

        foreach ($factories as $id => $val) {
            if (isset($distances[$my . '_' . $id])) {
                $currentDistances[$id] = $distances[$my . '_' . $id];
            } else {
                $currentDistances[$id] = $distances[$id . '_' . $my];
            }
        }
        asort($currentDistances);

        $result = [];
        $left = 5;
        foreach ($currentDistances as $id => $dist) {
            $left--;
            $result[$id] = $dist;

            if (!$left) break;
        }

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
    //error_log(var_export($distances, true));

    $gameTurn = 0;
    // game loop
    while (true) {
        $gameTurn++;
        $factories = [];
        $bombs = $troops = [];

        fscanf(STDIN, "%d", $entityCount); // the number of entities (e.g. factories and troops)

        for ($i = 0; $i < $entityCount; $i++) {
            fscanf(STDIN, "%d %s %d %d %d %d %d",
                $entityId,
                $entityType,
                $owner, //arg1
                $cyborgs, //arg2
                $arg3, //arg3
                $regen, //arg4
                $arg5 //arg5
            );

            if ($entityType == 'FACTORY') {
                $factory = array(
                    'id'         => $entityId,
                    'cyborgs'    => $cyborgs,
                    'production' => $arg3,
                );

                if ($owner == 1) { //1 - my
                    $factories['my'][$entityId] = $factory;
                } elseif ($owner == -1) { //-1 - oponent
                    $factories['enemy'][$entityId] = $factory;
                } else {//0 - neutral
                    $factories['neutral'][$entityId] = $factory;
                }
            } elseif ($entityType == 'TROOP') {
                $troops[] = array(
                    'owner'    => $owner,
                    'cyborgs'  => $regen,
                    'distance' => $arg5
                );
            } elseif ($entityType == 'BOMB') {
                if ($owner != 1) {
                    $bombs[] = array(
                        'owner'    => $owner,
                        'target'   => $arg3,
                        'distance' => $regen
                    );
                }
            }
        }

        $move = attack($factories, $distances);

        if ($move) {
            $action = 'MOVE ' . $move;

            if (in_array($gameTurn, array(10, 50))) {
                $E_ID = bestProduction($factories['enemy']);
                $MY_ID = findClosest($E_ID, $distances, $factories['my']);
                $action .= ';BOMB ' . $MY_ID . ' ' . $E_ID;
            }

            $MY_ID = alarm($bombs);

            echo $action . "\n";
        } else {
            echo "WAIT\n";
        }

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        // Any valid action, such as "WAIT" or "MOVE source destination cyborgs"

    }
