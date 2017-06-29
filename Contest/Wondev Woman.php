<?php
    fscanf(STDIN, "%d", $size);
    fscanf(STDIN, "%d", $unitsPerPlayer);

    // game loop
    while (true) {
        $map = [];
        for ($i = 0; $i < $size; $i++) {
            fscanf(STDIN, "%s", $line);
            $map[] = trim($line);
        }
        //error_log(var_export($map, true));

        for ($i = 0; $i < $unitsPerPlayer; $i++) {
            fscanf(STDIN, "%d %d", $unitX, $unitY);
        }
        error_log(var_export($unitX . ' ' . $unitY, true));

        for ($i = 0; $i < $unitsPerPlayer; $i++) {
            fscanf(STDIN, "%d %d",
                $otherX,
                $otherY
            );
            $map[$otherY][$otherX] = '.';
        }
        fscanf(STDIN, "%d", $legalActions);

        $actions = [];
        for ($i = 0; $i < $legalActions; $i++) {
            $action = [];
            fscanf(STDIN, "%s %d %s %s",
                $action['atype'],
                $action['index'],
                $action['dir1'],
                $action['dir2']
            );

            //$pos = nextPos($map, $unitX, $unitY, $action);
            //error_log(var_export($action['dir1'] . ' - ' . $pos, true));
            //$floor = getFloor($map, $unitX, $unitY, $action);
            $actions[] = $action;
        }

        $first = $myAction = analyzeMove($map, $unitY, $unitX);
        $n = 0;
        while (!nextMoveAvailable($map, $myAction)) {
            $X = $myAction['new_XY'][0];
            $Y = $myAction['new_XY'][1];
            $map[$Y][$X] = '.';

            $myAction = analyzeMove($map, $unitY, $unitX);

            if (++$n > 8) {
                $myAction = $first;
                break;
            }
        }

        //$map[$unitY][$unitX] = 'X';
        //drawMap($map);

        //$myAction = current($actions);
        //error_log(var_export($actions, true));
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        //echo("{$myAction['atype']} {$myAction['index']} {$myAction['dir1']} {$myAction['dir2']}\n");
        echo("MOVE&BUILD 0 {$myAction['move']} {$myAction['build']['dir']}\n");
    }

    function nextMoveAvailable($map, $myAction)
    {
        $X = $myAction['new_XY'][0];
        $Y = $myAction['new_XY'][1];

        $oX = $myAction['old_XY'][0];
        $oY = $myAction['old_XY'][1];
        $map[$oY][$oX] = '.';

        $bX = $myAction['build']['xy'][0];
        $bY = $myAction['build']['xy'][0];
        $map[$bY][$bX] = $map[$bY][$bX] + 1;

        $myAction = analyzeMove($map, $Y, $X);
        error_log(var_export($myAction['move'] && $myAction['build']['dir'], true));
        if ($myAction['move'] && $myAction['build']['dir']) {
            return 1;
        } else {
            return 0;
        }
    }

    function analyzeMove($map, $myY, $myX)
    {
        $possibleToMove = [];

        for ($x = $myX - 1; $x <= $myX + 1; $x++) {
            for ($y = $myY - 1; $y <= $myY + 1; $y++) {
                if ($x != $myX || $y != $myY) {
                    if (isset($map[$y][$x]) && strlen($map[$y][$x]) > 0 && checkMove($map[$y][$x]) && (abs($map[$y][$x] - $map[$myY][$myX]) < 2 || $map[$myY][$myX] > $map[$y][$x])) {
                        $pos = $map[$y][$x];
                        $move = [
                            'old_XY' => [$myX, $myY],
                            'new_XY' => [$x, $y],
                            'pos'    => $pos,
                            'move'   => xyToDir($myX, $myY, $x, $y),
                            'build'  => analyzeBuild($map, $y, $x)
                        ];

                        $possibleToMove[$pos] = $move;
                    }
                }
            }
        }
        krsort($possibleToMove);

        //error_log(var_export($possibleToMove, true));

        return current($possibleToMove);
    }

    function analyzeBuild($map, $myY, $myX)
    {
        $possibleToBuild = [];
        for ($x = $myX - 1; $x <= $myX + 1; $x++) {
            for ($y = $myY - 1; $y <= $myY + 1; $y++) {
                if ($x != $myX || $y != $myY) {
                    if (isset($map[$y][$x]) && strlen($map[$y][$x]) > 0 && checkMove($map[$y][$x]) && $map[$y][$x] < 3) {
                        //error_log(var_export('B - '.$x.' '.$y.' = '.isset($map[$y][$x]), true));
                        $possibleToBuild[] = [
                            'floor' => $map[$y][$x],
                            'xy'    => [$x, $y],
                            'dir'   => xyToDir($myX, $myY, $x, $y),
                        ];
                        //$possibleToBuild[] = xyToDir($myX, $myY, $x, $y).' '.$myX.'_'.$myY.'-'.$x.'_'.$y;
                    }
                }
            }
        }

        if (!count($possibleToBuild)) {
            for ($x = $myX - 1; $x <= $myX + 1; $x++) {
                for ($y = $myY - 1; $y <= $myY + 1; $y++) {
                    if ($x != $myX || $y != $myY) {
                        if (isset($map[$y][$x]) && strlen($map[$y][$x]) > 0 && checkMove($map[$y][$x])) {
                            //error_log(var_export('B - '.$x.' '.$y.' = '.isset($map[$y][$x]), true));
                            $possibleToBuild[] = [
                                'floor' => $map[$y][$x],
                                'xy'    => [$x, $y],
                                'dir'   => xyToDir($myX, $myY, $x, $y),
                            ];
                            //$possibleToBuild[] = xyToDir($myX, $myY, $x, $y).' '.$myX.'_'.$myY.'-'.$x.'_'.$y;
                        }
                    }
                }
            }
        }

        //krsort($possibleToBuild);

        return current($possibleToBuild);
    }

    function xyToDir($myX, $myY, $x, $y)
    {
        $dir = '';
        //error_log(var_export($myX.' '. $myY.' -> '. $x.' '. $y, true));

        if ($y < $myY) {
            $dir .= 'N';
        } elseif ($y > $myY) {
            $dir .= 'S';
        }

        if ($x < $myX) {
            $dir .= 'W';
        } elseif ($x > $myX) {
            $dir .= 'E';
        }

        //error_log(var_export($dir, true));

        return $dir;
    }

    function checkMove($pos)
    {
        if ($pos == '.' || $pos == '4') {
            return 0;
        } else {
            return 1;
        }
    }

    function getFloor($map, $X, $Y, $action)
    {
        switch ($action['dir1']) {
            case 'N':
                $Y--;
                break;
            case 'NE':
                $Y--;
                $X++;
                break;
            case 'E':
                $X++;
                break;
            case 'SE':
                $Y++;
                $X++;
                break;
            case 'S':
                $Y++;
                break;
            case 'SW':
                $Y++;
                $X--;
                break;
            case 'W':
                $X--;
                break;
            case 'NW':
                $Y--;
                $X--;
                break;
        }

        switch ($action['dir2']) {
            case 'N':
                $Y--;
                break;
            case 'NE':
                $Y--;
                $X++;
                break;
            case 'E':
                $X++;
                break;
            case 'SE':
                $Y++;
                $X++;
                break;
            case 'S':
                $Y++;
                break;
            case 'SW':
                $Y++;
                $X--;
                break;
            case 'W':
                $X--;
                break;
            case 'NW':
                $Y--;
                $X--;
                break;
        }

        return $map[$Y][$X];
    }

    function nextPos($map, $X, $Y, $action)
    {
        switch ($action['dir2']) {
            case 'N':
                $Y--;
                break;
            case 'NE':
                $Y--;
                $X++;
                break;
            case 'E':
                $X++;
                break;
            case 'SE':
                $Y++;
                $X++;
                break;
            case 'S':
                $Y++;
                break;
            case 'SW':
                $Y++;
                $X--;
                break;
            case 'W':
                $X--;
                break;
            case 'NW':
                $Y--;
                $X--;
                break;
        }

        return $map[$Y][$X];
    }

    function drawMap($map)
    {
        foreach ($map as $line) {
            error_log(var_export($line, true));
        }
    }