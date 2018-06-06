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

    #=========================================================================================================

    while (true) {
        fscanf(STDIN, "%d", $myScore);
        fscanf(STDIN, "%d", $enemyScore1);
        fscanf(STDIN, "%d", $enemyScore2);
        fscanf(STDIN, "%d", $myRage);
        fscanf(STDIN, "%d", $enemyRage1);
        fscanf(STDIN, "%d", $enemyRage2);
        fscanf(STDIN, "%d", $unitCount);

        $tanker = $me = $palyers = $lake = [];

        for ($i = 0; $i < $unitCount; $i++) {
            fscanf(STDIN, "%d %d %d %f %d %d %d %d %d %d %d",
                $unitId,
                $unitType,
                $player,
                $mass,
                $radius,
                $posX,
                $posY,
                $vx,
                $vy,
                $extra,
                $extra2
            );

            $unit = [
                'id'     => $unitId,
                'type'   => $unitType,
                'player' => $player,
                'mass'   => $mass,
                'radius' => $radius,
                'x'      => $posX,
                'y'      => $posY,
                'vx'     => $vx,
                'vy'     => $vy,
                'z1'     => $extra,
                'z2'     => $extra2
            ];

            if (!$unitType) {
                if ($player) {
                    $palyers[$player] = $unit;
                } else {
                    $me = $unit;
                }

            } elseif ($unitType == 4) {
                $lake[$unitId] = $unit;

            } elseif ($unitType == 3) {
                $tanker[$unitId] = $unit;
            }
        }
        //error_log(var_export($units, true));
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        //krsort($units);

        if (count($lake)) {
            $playerClose = min_distance_to($palyers, $me);

            $min = min_distance_to($lake, $me);
            //error_log(var_export($min, true));
            if ($min['dist'] < 700) {
                $power = 0;
            } else {
                $power = round($min['dist'] / 10);

                if ($power > 300) {
                    $power = 300;
                }
            }

            if ($playerClose['dist'] < 1000 && $min['dist'] < 1000) {
                echo "{$playerClose['item']['x']} {$playerClose['item']['y']} 300\n";
                echo "{$playerClose['item']['x']} {$playerClose['item']['y']} 300\n";
                echo "{$playerClose['item']['x']} {$playerClose['item']['y']} 300\n";
            } else {
                echo "{$min['item']['x']} {$min['item']['y']} {$power}\n";
                echo "{$min['item']['x']} {$min['item']['y']} {$power}\n";
                echo "{$min['item']['x']} {$min['item']['y']} {$power}\n";
            }
        } elseif (count($tanker)) {
            $min = min_distance_to($tanker, $me);

            echo "{$min['item']['x']} {$min['item']['y']} 300\n";
            echo "{$min['item']['x']} {$min['item']['y']} 300\n";
            echo "{$min['item']['x']} {$min['item']['y']} 300\n";

        } else {
            echo "WAIT\n";
            echo "WAIT\n";
            echo "WAIT\n";
        }
    }