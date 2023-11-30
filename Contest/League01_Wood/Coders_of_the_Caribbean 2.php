<?php

    function get_distance($one, $two)
    {
        $d = sqrt(pow($one['x'] - $two['x'], 2) + pow($one['y'] - $two['y'], 2));

        return ceil($d);
    }

    function min_distance_to($el, $item)
    {
        $distances = [];

        foreach ($el as $k => $one) {
            $distances[$k] = get_distance($item, $one);
        }
        asort($distances);
        $closest = current($distances);
        $k = array_search($closest, $distances);

        return ['dist' => $closest, 'item' => $el[$k]];
    }

    function findPath(&$MAP, $X, $Y, $i)
    {
        $WAVE = oneWave($MAP, $X, $Y, ++$i);

        while (count($WAVE)) {
            $i++;
            $newWave = [];
            foreach ($WAVE as $X => $W) {
                foreach ($W as $Y => $x) {
                    oneWave($MAP, $X, $Y, $i, $newWave);
                }
            }
            $WAVE = $newWave;
        }
    }

    function oneWave(&$MAP, $X, $Y, $i, &$WAVE = [])
    {
        //error_log(var_export($Y." ".$maxY, true));
        if ($Y > 0) {
            if (empty($MAP[$X][$Y - 1])) { //UP
                $MAP[$X][$Y - 1] = $i;
                $WAVE[$X][$Y - 1] = 1;

            } elseif ($MAP[$X][$Y - 1] == 'S') {
                return array();
            }
        }

        if ($Y < 8) {
            if (empty($MAP[$X][$Y + 1])) { //DOWN
                $MAP[$X][$Y + 1] = $i;
                $WAVE[$X][$Y + 1] = 1;
                //error_log(var_export('DOWN', true));

            } elseif ($MAP[$X][$Y + 1] == 'S') {
                return array();
            }
        }

        //error_log(var_export($X." ".$maxX, true));
        if ($X > 0) {
            if (empty($MAP[$X - 1][$Y])) { //LEFT
                $MAP[$X - 1][$Y] = $i;
                $WAVE[$X - 1][$Y] = 1;
                //error_log(var_export('LEFT', true));

            } elseif ($MAP[$X - 1][$Y] == 'S') {
                return array();
            }
        }

        if ($X < 15) {
            if (empty($MAP[$X + 1][$Y])) { //RIGHT
                $MAP[$X + 1][$Y] = $i;
                $WAVE[$X + 1][$Y] = 1;
                //error_log(var_export('RIGHT', true));

            } elseif ($MAP[$X + 1][$Y] == 'S') {
                return array();
            }
        }


        return $WAVE;
    }

    #=================================================================================================================

    while (true) {
        fscanf(STDIN, "%d", $myShipCount); // the number of remaining ships
        fscanf(STDIN, "%d", $entityCount);// the number of entities (e.g. ships, mines or cannonballs)

        $mine = $barrel = $enemy = $myShips = [];

        for ($i = 0; $i < $entityCount; $i++) {
            fscanf(STDIN, "%d %s %d %d %d %d %d %d",
                $entityId,
                $entityType,
                $posX,
                $posY,
                $arg1,
                $arg2,
                $arg3,
                $arg4
            );

            $entity = [
                'id'    => $entityId,
                'type'  => $entityType,
                'x'     => $posX,
                'y'     => $posY,
                'pos'   => $arg1,
                'speed' => $arg2,
                'rum'   => $arg3,
                'my'    => $arg4
            ];

            if ($entityType == 'SHIP') {
                if ($entity['my']) {
                    $myShips[] = $entity;
                } else {
                    $enemy[] = $entity;
                }
            } elseif ($entityType == 'BARREL') {
                $barrel[] = $entity;

            } elseif ($entityType == 'MINE') {
                $mine[] = $entity;
            }

            //error_log(var_export($entity, true));
        }

        foreach ($myShips as $ship) {
            // Write an action using echo(). DON'T FORGET THE TRAILING \n
            // To debug (equivalent to var_dump): error_log(var_export($var, true));
            $closest = min_distance_to($barrel, $ship);

            if (isset($closest['item'])) {
                echo "MOVE {$closest['item']['x']} {$closest['item']['y']}\n";
            } else {
                $closest = min_distance_to($enemy, $ship);

                echo "FIRE {$closest['item']['x']} {$closest['item']['y']}\n";
            }

            // Any valid action, such as "WAIT" or "MOVE x y"
        }
    }