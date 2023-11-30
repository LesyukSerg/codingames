<?php
    /**
     * Deliver more ore to hq (left side of the map) than your opponent. Use radars to find ore but beware of traps!
     **/

    fscanf(STDIN, "%d %d", $width, $height); // size of the map
    $map = [];

    // game loop
    while (true) {
        $items = [];
        $oresFound = 0;
        $enemy = $my = [];
        $radar = 0;

        fscanf(STDIN, "%d %d", $myScore, $opponentScore); // Amount of ore delivered

        for ($y = 0; $y < $height; $y++) {
            $inputs = explode(" ", fgets(STDIN));
            for ($x = 0; $x < $width; $x++) {
                $ore = ($inputs[2 * $x]); // amount of ore or "?" if unknown
                $hole = intval($inputs[2 * $x + 1]); // 1 if cell has a hole

                if ($ore != '?' && $ore > 0) {
                    $oresFound = 1;
                    $items['ore'][$x . '_' . $y] = ['x' => $x, 'y' => $y, 'count' => $ore];
                    $map[$y][$x] = $ore;
                } else {
                    $map[$y][$x] = '?';
                }

                if ($hole) {
                    $items['hole'][$x . '_' . $y] = ['x' => $x, 'y' => $y];
                    $map[$y][$x] = 'X';
                }
            }
        }

        //showMAP($map, $width, $height);

        fscanf(STDIN, "%d %d %d",
            $entityCount, // number of entities visible to you
            $radarCooldown, // turns left until a new radar can be requested
            $trapCooldown // turns left until a new trap can be requested
        );

        for ($i = 0; $i < $entityCount; $i++) {
            fscanf(STDIN, "%d %d %d %d %d",
                $id, // unique id of the entity
                $type, // 0 for your robot, 1 for other robot, 2 for radar, 3 for trap
                $x,
                $y, // position of the entity
                $carry // if this entity is a robot, the item it is carrying (-1 for NONE, 2 for RADAR, 3 for TRAP, 4 for ORE)
            );

            if ($type == 0) {
                if ($carry == 2) $radar = 1;

                $my[] = [
                    'id'        => $id, // unique id of the entity
                    'type'      => $type, // 0 for your robot, 1 for other robot, 2 for radar, 3 for trap
                    'x'         => $x,
                    'y'         => $y, // position of the entity
                    'carry'     => $carry, //
                    'needRadar' => !$radar ? 1 : 0
                ];
            } elseif ($type == 1) {
                $enemy[] = [
                    'id'    => $id, // unique id of the entity
                    'type'  => $type, // 0 for your robot, 1 for other robot, 2 for radar, 3 for trap
                    'x'     => $x,
                    'y'     => $y, // position of the entity
                    'carry' => $carry, //
                ];
            } else {
                $a = [2 => 'R', 3 => 'T'];
                $map[$y][$x] = $a[$type];

                if ($type == 2) {
                    $items['radar'][$x . '_' . $y] = ['x' => $x, 'y' => $y];
                    unset($items['hole'][$x . '_' . $y]);
                } elseif ($type == 3) {
                    $items['trap'][$x . '_' . $y] = ['x' => $x, 'y' => $y];
                    unset($items['hole'][$x . '_' . $y]);
                }
            }
        }

        //error_log(var_export($items, true));

        foreach ($my as $one) {
            $nextX = $one['x'] + 4 < $width ? $one['x'] + 4 : $width;

            if ($one['carry'] == -1) {
                if ($oresFound) {
                    if ($ore = array_pop($items['ore'])) {
                        echo "DIG {$ore['x']} {$ore['y']}\n";
                    } else {
                        echo "REQUEST RADAR\n";
                    }

                } else {
                    if ($one['needRadar']) {
                        echo "REQUEST RADAR\n";
                    } else {
                        echo "MOVE {$nextX} {$one['y']}\n";
                    }
                }
            } else {
                if ($one['carry'] == 2) {
                    if (isset($items['hole']) && count($items['hole'])) {
                        $hole = current($items['hole']);
                        echo "DIG {$hole['x']} {$hole['y']}\n";
                    } else {
                        echo "DIG {$one['x']} {$one['y']}\n";
                    }
                } else {
                    echo "MOVE 0 {$one['y']}\n";
                }
            }
            // Write an action using echo(). DON'T FORGET THE TRAILING \n
            // To debug (equivalent to var_dump): error_log(var_export($var, true));
//            echo "WAIT\n"; // WAIT|MOVE x y|DIG x y|REQUEST item
        }
    }

    ###===============================================================================================================


    function showMAP($MAP, $xMax, $yMax)
    {
        $sMap = "\n";
        $sMap .= " ||";

        for ($x = 0; $x < $xMax; $x++) {
            if ($x < 10)
                $sMap .= " ";
            $sMap .= $x . "|";
        }
        $sMap .= "\n";

        for ($y = 0; $y < $yMax; $y++) {
            if ($x < 10)
                $sMap .= " ";
            $sMap .= $y . "||";

            for ($x = 0; $x < $xMax; $x++) {
                if (empty($MAP[$y][$x])) {
                    $sMap .= "00|";
                } else {
                    if ($MAP[$y][$x] < 10)
                        $sMap .= " ";

                    $sMap .= $MAP[$y][$x] . "|";
                }
            }

            $sMap .= "\n\n";
        }

        error_log(var_export($sMap, true));
    }
