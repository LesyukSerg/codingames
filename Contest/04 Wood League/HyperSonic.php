<?php
    fscanf(STDIN, "%d %d %d", $width, $height, $myId);

    // game loop
    while (true) {
        $map = [];
        for ($i = 0; $i < $height; $i++) {
            fscanf(STDIN, "%s", $map[]);
        }

        fscanf(STDIN, "%d", $entities);
        $items = $Bombs = $I = $enemy = [];

        for ($i = 0; $i < $entities; $i++) {
            fscanf(STDIN, "%d %d %d %d %d %d",
                $entityType,
                $owner,
                $posX,
                $posY,
                $param1,
                $param2
            );

            $temp = array(
                'entityType' => $entityType,
                'x'          => $posX,
                'y'          => $posY,
                'bombs'      => $param1,
                'range'      => $param2
            );

            if (!$entityType) {
                if ($myId == $owner) {
                    $I = $temp;
                } else {
                    $enemy = $temp;
                }
            } elseif ($entityType == 1) {
                $Bombs[] = $temp;
            } else {
                $items[] = $temp;
            }
        }

        showMap($map, $I['y'], $I['x']);
        foreach ($Bombs as $bombs) {
            removeBoxes($map, $bombs['x'], $bombs['y'], $I['range']);
        }
        showMap($map);

        $newCoord = searchBetterPlace($map, $width, $height, $I);
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        if ($I['bombs'] && $newCoord == "{$I['x']} {$I['y']}") {
            echo("BOMB {$newCoord}\n");
        } else {
            echo("MOVE {$newCoord}\n");
        }
    }

    function searchBetterPlace($map, $W, $H, $I)
    {
        $places = [];
        $MoveMap = availableMap_collect($map, $W, $H, $I);

        error_log(var_export('available move', true));
        error_log(var_export($MoveMap, true));

        foreach ($MoveMap as $y => $line) {
            foreach ($line as $x => $pos) {
                if ($pos == '.') {
                    $places[$x . " " . $y] = findBoxes($map, $y, $x, $I['range']);
                }
            }
        }

        showMap($map, $I['y'], $I['x']);
        if (count($places)) {

            error_log(var_export('places', true));
            error_log(var_export($places, true));

            arsort($places);
            $max = max($places);
            $distances = [];

            foreach ($places as $k => $place) {
                if ($place < $max) break;

                $pos = explode(' ', $k);
                $distances[$k] = getDistance($pos[0], $pos[1], $I['x'], $I['y']);    //x1 y1 x2 y2
            }

            asort($distances);

            //error_log(var_export($distances, true));


            return array_search(current($distances), $distances);
        } else {
            return "{$I['x']} {$I['y']}";
        }
    }

    function availableMap_collect($map, $W, $H, $I)
    {
        $MoveMap = availableMap($map, $W, $H, $I);
        $mapPlus = [];

        foreach ($MoveMap as $y => $line) {
            foreach ($line as $x => $pos) {
                $I['x'] = $x;
                $I['y'] = $y;

                $mapPlus += availableMap($map, $W, $H, $I);
            }
        }
        $MoveMap += $mapPlus;

        return $MoveMap;
    }

    function availableMap($map, $W, $H, $I)
    {
        $newMap = [];
        $x = $I['x'];
        $y = $I['y'];
        error_log(var_export("$y $x", true));

        while ($map[$y][$x] == '.' && $x > 0) {
            $newMap[$y][$x] = $map[$y][$x];
            $x--;
        }

        $x = $I['x'];
        while ($map[$y][$x] == '.' && $x < $W - 1) {
            $newMap[$y][$x] = $map[$y][$x];
            $x++;
        }

        $x = $I['x'];
        while ($map[$y][$x] == '.' && $y > 0) {
            $newMap[$y][$x] = $map[$y][$x];
            $y--;
        }

        $y = $I['y'];
        while ($map[$y][$x] == '.' && $y < $H - 1) {
            $newMap[$y][$x] = $map[$y][$x];
            $y++;
        }

        return $newMap;
    }


    function findBoxes($map, $Y, $X, $range)
    {
        $numBombs = 0;
        for ($y = $Y; $y > $Y - $range; $y--) {
            if (isset($map[$y][$X])) {
                if ($map[$y][$X] == 'X') {
                    break;

                } elseif ($map[$y][$X] != '.') {
                    $numBombs++;
                    break;
                }
            }
        }

        for ($y = $Y; $y < $Y + $range; $y++) {
            if (isset($map[$y][$X])) {
                if ($map[$y][$X] == 'X') {
                    break;

                } elseif ($map[$y][$X] != '.') {
                    $numBombs++;
                    break;
                }
            }
        }

        for ($x = $X; $x > $X - $range; $x--) {
            if (isset($map[$Y][$x])) {
                if ($map[$Y][$x] == 'X') {
                    break;

                } elseif ($map[$Y][$x] != '.') {
                    $numBombs++;
                    break;
                }
            }
        }

        for ($x = $X; $x < $X + $range; $x++) {
            if (isset($map[$Y][$x])) {
                if ($map[$Y][$x] == 'X') {
                    break;

                } elseif ($map[$Y][$x] != '.') {
                    $numBombs++;
                    break;
                }
            }
        }

        return $numBombs;
    }

    function removeBoxes(&$map, $Y, $X, $range)
    {
        for ($y = $Y; $y > $Y - $range; $y--) {
            if (isset($map[$y][$X])) {
                if ($map[$y][$X] == 'X') {
                    break;

                } elseif ($map[$y][$X] != '.') {
                    $map[$y][$X] = '.';
                    break;
                }
            }
        }

        for ($y = $Y; $y < $Y + $range; $y++) {
            if (isset($map[$y][$X])) {
                if ($map[$y][$X] == 'X') {
                    break;

                } elseif ($map[$y][$X] != '.') {
                    $map[$y][$X] = '.';
                    break;
                }
            }
        }

        for ($x = $X; $x > $X - $range; $x--) {
            if (isset($map[$Y][$x])) {
                if ($map[$Y][$x] == 'X') {
                    break;

                } elseif ($map[$Y][$x] != '.') {
                    $map[$Y][$x] = '.';
                    break;
                }
            }
        }

        for ($x = $X; $x < $X + $range; $x++) {
            if (isset($map[$Y][$x])) {
                if ($map[$Y][$x] == 'X') {
                    break;

                } elseif ($map[$Y][$x] != '.') {
                    $map[$Y][$x] = '.';
                    break;
                }
            }
        }
    }


    function getDistance($X1, $Y1, $X2, $Y2)
    {
        return sqrt(pow($X1 - $X2, 2) + pow($Y1 - $Y2, 2));
    }

    function showMap($map, $y = 0, $x = 0)
    {
        $map[$y][$x] = '@';

        foreach ($map as $line) {
            error_log(var_export($line, true));
        }

        error_log(var_export('------------------------------------------', true));
    }
