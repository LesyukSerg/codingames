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
                $x,
                $y,
                $param1,
                $param2
            );

            $temp = array(
                'entityType' => $entityType,
                'x'          => $x,
                'y'          => $y,
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

        foreach ($Bombs as $bombs) {
            removeBoxes($map, $bombs['y'], $bombs['x'], $I['range']);
        }
        //showMap($map, $I['y'], $I['x']);

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

            //error_log(var_export('places', true));
            //error_log(var_export($places, true));

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

                $tmp = availableMap($map, $W, $H, $I);

                foreach ($tmp as $y => $line) {
                    foreach ($line as $x => $val) {
                        $mapPlus[$y][$x] = $val;
                    }
                }

                //if(!$x && !$y) error_log(var_export($mapPlus, true));
            }
        }

        foreach ($mapPlus as $y => $line) {
            foreach ($line as $x => $val) {
                $MoveMap[$y][$x] = $val;
            }
        }

        return $MoveMap;
    }

    function availableMap($map, $W, $H, $I)
    {
        $newMap = [];
        $x = $I['x'];
        $y = $I['y'];
        //error_log(var_export("$y $x", true));

        while ($x >= 0 && ($map[$y][$x] == '.' || $map[$y][$x] == 'B')) {
            //error_log(var_export("Y++ CHECK $y $x ".$map[$y][$x], true));
            if ($map[$y][$x] == '.' || $map[$y][$x] == 'B') {
                $newMap[$y][$x] = $map[$y][$x];
            }
            $x--;
        }

        $x = $I['x'];
        while (($map[$y][$x] == '.' || $map[$y][$x] == 'B') && $x < $W - 1) {
            if ($map[$y][$x] == '.' || $map[$y][$x] == 'B') {
                //error_log(var_export("Y-- CHECK $y $x ".$map[$y][$x], true));
                $newMap[$y][$x] = $map[$y][$x];
            }
            $x++;
        }

        $x = $I['x'];
        while ($y >= 0 && ($map[$y][$x] == '.' || $map[$y][$x] == 'B')) {
            //error_log(var_export("Y-- CHECK $y $x ".$map[$y][$x], true));
            if ($map[$y][$x] == '.' || $map[$y][$x] == 'B') {
                $newMap[$y][$x] = $map[$y][$x];
            }
            $y--;
        }

        $y = $I['y'];
        while (($map[$y][$x] == '.' || $map[$y][$x] == 'B') && $y < $H - 1) {
            if ($map[$y][$x] == '.' || $map[$y][$x] == 'B') {
                $newMap[$y][$x] = $map[$y][$x];
            }
            $y++;
        }

        return $newMap;
    }


    function findBoxes($map, $Y, $X, $range)
    {
        $numBombs = 0;
        for ($y = $Y; $y > $Y - $range; $y--) {
            if (isset($map[$y][$X])) {
                if ($map[$y][$X] == 'X' || $map[$y][$X] == "B") {
                    break;

                } elseif ($map[$y][$X] != '.') {
                    $numBombs++;
                    break;
                }
            }
        }

        for ($y = $Y; $y < $Y + $range; $y++) {
            if (isset($map[$y][$X])) {
                if ($map[$y][$X] == 'X' || $map[$y][$X] == "B") {
                    break;

                } elseif ($map[$y][$X] != '.') {
                    $numBombs++;
                    break;
                }
            }
        }

        for ($x = $X; $x > $X - $range; $x--) {
            if (isset($map[$Y][$x])) {
                if ($map[$Y][$x] == 'X' || $map[$Y][$x] == "B") {
                    break;

                } elseif ($map[$Y][$x] != '.') {
                    $numBombs++;
                    break;
                }
            }
        }

        for ($x = $X; $x < $X + $range; $x++) {
            if (isset($map[$Y][$x])) {
                if ($map[$Y][$x] == 'X' || $map[$Y][$x] == "B") {
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
                if ($map[$y][$X] != '.' && $map[$y][$X] != 'B') {
                    //error_log(var_export("Y-- EXIT $y $X", true));
                    $map[$y][$X] = "X";
                    break;

                } else {
                    //error_log(var_export("SET $y $X", true));
                    $map[$y][$X] = "B";
                }
            } else {
                break;
            }
        }
        //showMap($map, $Y, $X);

        for ($y = $Y; $y < $Y + $range; $y++) {
            if (isset($map[$y][$X])) {
                if ($map[$y][$X] != '.' && $map[$y][$X] != 'B') {
                    //error_log(var_export("Y++ EXIT $y $X", true));
                    $map[$y][$X] = "X";
                    break;

                } else {
                    //error_log(var_export("SET $y $X", true));
                    $map[$y][$X] = "B";
                }
            } else {
                break;
            }
        }
        //

        for ($x = $X; $x > $X - $range; $x--) {
            if (isset($map[$Y][$x])) {
                if ($map[$Y][$x] != '.' && $map[$Y][$x] != 'B') {
                    //error_log(var_export("X-- EXIT $Y $x ".$map[$Y][$x], true));
                    $map[$Y][$x] = 'X';
                    break;

                } else {
                    //error_log(var_export("SET $Y $x", true));
                    $map[$Y][$x] = 'B';
                }
            } else {
                break;
            }
        }

        for ($x = $X; $x < $X + $range; $x++) {
            if (isset($map[$Y][$x])) {
                if ($map[$Y][$x] != '.' && $map[$Y][$x] != 'B') {
                    //error_log(var_export("X++ SET $y $X", true));
                    $map[$Y][$x] = 'X';
                    break;

                } else {
                    //error_log(var_export("SET $y $X", true));
                    $map[$Y][$x] = 'B';
                }
            } else {
                break;
            }
        }
        //showMap($map, $Y, $X);
    }


    function getDistance($X1, $Y1, $X2, $Y2)
    {
        return sqrt(pow($X1 - $X2, 2) + pow($Y1 - $Y2, 2));
    }

    function showMap($map, $y = 0, $x = 0)
    {
        error_log(var_export($y . "|" . $x . " = " . $map[$y][$x], true));
        $map[$y][$x] = '@';

        foreach ($map as $Y => $line) {
            $Y = $Y % 10;
            error_log(var_export($Y . "|" . $line, true));
        }

        error_log(var_export('------------------------------------------', true));
    }
