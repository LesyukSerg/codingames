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

    function max_distance_to($el, $item)
    {
        $distances = [];

        foreach ($el as $k => $one) {
            $distances[$k] = get_distance($item, $one);
        }
        arsort($distances);
        $closest = current($distances);
        $k = array_search($closest, $distances);

        return ['dist' => $closest, 'item' => $el[$k]];
    }

    function get_all_unit($units, $type)
    {
        $need = [];
        foreach ($units as $one) {
            if ($one['type'] == $type) {
                $need[] = $one;
            }
        }

        return $need;
    }

    function circle_move($unit)
    {
        $new['x'] = rand(-6000, 6000);
        $new['y'] = rand(-6000, 6000);

        if ($unit['x'] < 0 && $unit['x'] > -5000) {
            if ($unit['vx'] < 0) {
                $new['x'] = -5999;
            } else {
                $new['x'] = 0;
            }
        } elseif ($unit['x'] > 0 && $unit['x'] < 5000) {
            if ($unit['vx'] > 0) {
                $new['x'] = 5999;
            } else {
                $new['x'] = 0;
            }
        }

        if ($unit['y'] < 0 && $unit['y'] > -5000) {
            if ($unit['vy'] < 0) {
                $new['y'] = -5999;
            } else {
                $new['y'] = 0;
            }
        } elseif ($unit['y'] > 0 && $unit['x'] < 5000) {
            if ($unit['vy'] > 0) {
                $new['y'] = 5999;
            } else {
                $new['y'] = 0;
            }
        }

        return $new;
    }

    function max_water($lakes)
    {
        $max = [];

        foreach ($lakes as $k => $one) {
            $max[$k] = $one['water'];
        }
        arsort($max);
        $biggest = current($max);
        $k = array_search($biggest, $max);

        return $lakes[$k];
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

        $enemyReaper = $tanker = $me = $palyers = $lake = [];

        for ($i = 0; $i < $unitCount; $i++) {
            fscanf(STDIN, "%d %d %d %f %d %d %d %d %d %d %d",
                $unitId,
                $unitType,
                $player,
                $mass,
                $radius,
                $x,
                $y,
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
                'x'      => $x,
                'y'      => $y,
                'vx'     => $vx,
                'vy'     => $vy,
                'water'  => $extra,
                'z2'     => $extra2
            ];
            $allUnits = $unit;

            if ($player) {
                $palyers[] = $unit;

                if ($unitType == 1) {
                    $enemyReaper[] = $unit;
                }

            } else {
                $me[$unitType] = $unit;
            }

            if (!$unitType) {

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

        foreach ($me as $one) {
            if ($one['type'] === 0) {
                if (count($lake)) {
                    $min = min_distance_to($lake, $one);
                    $playerClose = min_distance_to($palyers, $min['item']);
                    if ($myRage > 100 && $min['dist'] < 2000) {
                        echo "SKILL {$min['item']['x']} {$min['item']['y']}\n";
                    } else {

                        /*if ($min['dist'] < 700) {
                            $power = 0;
                        } else {
                            $power = round($min['dist'] / 10);

                            if ($power > 300) {
                                $power = 300;
                            }
                        }*/
                        $x = $min['item']['x'] - $one['vx'];
                        $y = $min['item']['y'] - $one['vy'];
                        echo "{$x} {$y} 300\n";
                    }

                } else {
                    //$min = min_distance_to($tanker, $one);
                    $x = $me[1]['x'] - $one['vx'];
                    $y = $me[1]['y'] - $one['vy'];
                    echo "{$x} {$y} 300\n";
                }

            } elseif ($one['type'] == 1) {
                $min = min_distance_to($enemyReaper, $one);

                if ($myRage > 100 && $min['dist'] < 2000) {
                    echo "SKILL {$min['item']['x']} {$min['item']['y']}\n";

                } else {
                    if (count($tanker)) {
                        $min = min_distance_to($tanker, $one);
                        $x = $min['item']['x'] - $one['vx'];
                        $y = $min['item']['y'] - $one['vy'];
                        echo "{$x} {$y} 300\n";

                    } else {
                        echo "WAIT\n";
                    }
                }

            } elseif ($one['type'] == 2) {
                $move = circle_move($one);
                //$need = get_all_unit($palyers, 2);
                //$min = min_distance_to($need, $one);
                echo "{$move['x']} {$move['y']} 300\n";
            }
        }
    }