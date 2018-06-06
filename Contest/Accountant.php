<?php
    while (1) {
        $enemies = [];
        $data = [];

        fscanf(STDIN, "%d %d", $x, $posY);
        fscanf(STDIN, "%d", $dataCount);

        for ($i = 0; $i < $dataCount; $i++) {
            fscanf(STDIN, "%d %d %d", $dataId, $dataX, $dataY);
            $data[$dataId] = array(
                'x' => $dataX,
                'y' => $dataY
            );
        }

        fscanf(STDIN, "%d", $enemyCount);

        for ($i = 0; $i < $enemyCount; $i++) {
            fscanf(STDIN, "%d %d %d %d", $enemyId, $enemyX, $enemyY, $enemyLife);

            $enemies[$enemyId] = array(
                'life' => $enemyLife,
                'x'    => $enemyX,
                'y'    => $enemyY
            );
        }

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        $distances = [];

        $res = closest($enemies, $x, $posY);
        $distances[$res['dist']] = $res['key'];

        ksort($distances);
        $id = current($distances);
        $min = array_search($id, $distances);

        //error_log(var_export($min, true));

        if ($min < 4000) {
            if ($min > 2500) {
                echo("SHOOT {$id}\n"); // shoot closest enemy
            } else {
                $closest = $enemies[$id];
                $runX = ($x - $closest['x']) + $x;
                $runY = ($posY - $closest['y']) + $posY;
                //error_log(var_export('rX='.$runX."|".'rY='.$runY, true));

                if ($runX > 16000) $runX = 16000;
                elseif ($runX < 0) $runX = 0;

                if ($runY > 9000) $runY = 9000;
                elseif ($runY < 0) $runY = 0;

                echo("MOVE {$runX} {$runY}\n"); // run away
            }

        } else {
            $cnt = count($data);

            if ($cnt == 1) {
                $last = current($data);
                $res = closest($enemies, $last['x'], $last['y']);

                if ($res['dist'] < 3000) {
                    echo("SHOOT {$res['key']}\n"); // my last chance

                } else {
                    //echo("MOVE {$last['x']} {$last['y']}\n"); // move to last data
                    $avg = avg_position($enemies);
                    echo("MOVE {$avg['x']} {$avg['y']}\n"); // move to the center
                }

            } elseif ($cnt == 2) {
                $distances = [];
                foreach ($data as $item) {
                    $res = closest($enemies, $item['x'], $item['y']);
                    $distances[$res['dist']] = $res['key'];
                }

                krsort($distances);
                $id = current($distances);
                $min = array_search($id, $distances);

                if ($min < 2000) {
                    echo("SHOOT {$id}\n"); // sniper
                } else {
                    $closest = $enemies[$id];
                    echo("MOVE {$closest['x']} {$closest['y']}\n"); // neen to save one data
                }

            } else {
                $avg = avg_position($enemies);
                echo("MOVE {$avg['x']} {$avg['y']}\n"); // move to the center
            }
        }
    }

    #==================================================================================================================
    #==================================================================================================================
    #==================================================================================================================

    function closest($items, $myX, $myY)
    {
        $distances = [];

        foreach ($items as $id => $item) {
            $distances[$id] = getDistance($myX, $myY, $item['x'], $item['y']);
        }
        $min = min($distances);

        return array('dist' => $min, 'key' => array_search($min, $distances));
    }

    function getDistance($X1, $Y1, $X2, $Y2)
    {
        return sqrt(pow($X1 - $X2, 2) + pow($Y1 - $Y2, 2));
    }

    function avg_position($enemies)
    {
        $sumX = $sumY = [];
        $count = count($enemies);

        foreach ($enemies as $enemy) {
            $sumX[] = $enemy['x'];
            $sumY[] = $enemy['y'];
        }

        $avgX = ceil(array_sum($sumX) / $count);
        $avgY = ceil(array_sum($sumY) / $count);

        return array('x' => $avgX, 'y' => $avgY);
    }