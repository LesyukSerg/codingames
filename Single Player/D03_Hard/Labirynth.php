<?
    function findPath(&$map, $rows, $cols, $Y, $X, $i, $find)
    {
        $WAVE = oneWave($map, $rows, $cols, $Y, $X, ++$i, $find);


        while (current($WAVE)) {
            $i++;
            $newWave = [];

            foreach ($WAVE as $Y => $W) {
                foreach ($W as $X => $x) {
                    //error_log(var_export("$Y $X path", true));
                    oneWave($map, $rows, $cols, $Y, $X, $i, $find, $newWave);
                }
            }

            foreach ($newWave as $Y => $W) {
                foreach ($W as $X => $x) {
                    $found = findEl($map, $rows, $cols, $Y, $X, $find);

                    if ($found) {
                        return $found;
                    }
                }
            }

            $WAVE = $newWave;
        }

        // showMAP($MAP, $height, $width);
        // die;
    }

    function oneWave(&$map, $rows, $cols, $Y, $X, $i, $find, &$WAVE = [])
    {
        if ($Y > 0) {
            if ($map[$Y - 1][$X] == '.') { //UP
                $map[$Y - 1][$X] = $i;
                $WAVE[$Y - 1][$X] = 1;

            } elseif ($map[$Y - 1][$X] == $find) {
                return [];
            }
        }

        if ($Y < $rows - 1) {
            if ($map[$Y + 1][$X] == '.') { //DOWN
                $map[$Y + 1][$X] = $i;
                $WAVE[$Y + 1][$X] = 1;
                //error_log(var_export('DOWN', true));

            } elseif ($map[$Y + 1][$X] == $find) {
                return [];
            }
        }

        //error_log(var_export($X." ".$maxX, true));
        if ($X > 0) {
            if ($map[$Y][$X - 1] == '.') { //LEFT
                $map[$Y][$X - 1] = $i;
                $WAVE[$Y][$X - 1] = 1;
                //error_log(var_export('LEFT', true));

            } elseif ($map[$Y][$X - 1] == $find) {
                return [];
            }
        }

        if ($X < $cols - 1) {
            if ($map[$Y][$X + 1] == '.') { //RIGHT
                $map[$Y][$X + 1] = $i;
                $WAVE[$Y][$X + 1] = 1;
                //error_log(var_export('RIGHT', true));

            } elseif ($map[$Y][$X + 1] == $find) {
                return [];
            }
        }


        return $WAVE;
    }

    function findXY($map, $rows, $cols, $Y, $X, $i, $find)
    {
        $WAVE = oneWave($map, $rows, $cols, $Y, $X, ++$i, $find);
        while (current($WAVE)) {
            //error_log(var_export($WAVE, true));
            //showMAP($MAP, $height, $width);

            $i++;
            $newWave = [];

            foreach ($WAVE as $Y => $W) {
                foreach ($W as $X => $x) {
                    error_log(var_export("$Y $X next", true));
                    oneWave($map, $rows, $cols, $Y, $X, $i, $find, $newWave);
                }
            }

            //error_log(var_export($map, true));
            //showMAP($map, $rows, $cols);
            //error_log(var_export($newWave, true));
            //error_log(var_export(current($newWave), true));


            foreach ($newWave as $Y => $W) {
                foreach ($W as $X => $x) {
                    $found = findEl($map, $rows, $cols, $Y, $X, $find);

                    if ($found) {
                        return $found;
                    }
                }
            }

            //error_log(var_export($WAVE, true));
            //showMAP($MAP, $height, $width);
            //die;

            //error_log(var_export(current($newWave), true));
            //error_log(var_export($newWave, true));
            //showMAP($MAP, $height, $width);
            $WAVE = $newWave;
        }
    }

    function findEl(&$map, $rows, $cols, $Y, $X, $find)
    {
        if ($Y > 0) {
            //error_log(var_export("$Y $X", true));
            if ($map[$Y - 1][$X] == $find) {
                return [$Y - 1, $X];
            }
        }

        if ($Y < $rows) {
            if ($map[$Y + 1][$X] == $find) {
                return [$Y + 1, $X];
            }
        }

        //error_log(var_export($X." ".$maxX, true));
        if ($X > 0) {
            if ($map[$Y][$X - 1] == $find) {
                return [$Y, $X - 1];
            }
        }

        if ($X < $cols) {
            if ($map[$Y][$X + 1] == $find) {
                return [$Y, $X + 1];
            }
        }

        return 0;
    }

    function goToNext(&$map, $Y, $X)
    {
        $RIGHT = $DOWN = $LEFT = $UP = 9999;
        $not = ["#", "?", "T", "C", "."];

        if (isset($map[$Y][$X + 1]) && !in_array($map[$Y][$X + 1], $not))
            $RIGHT = $map[$Y][$X + 1];

        if (isset($map[$Y + 1][$X]) && !in_array($map[$Y + 1][$X], $not))
            $DOWN = $map[$Y + 1][$X];

        //error_log(var_export(isset($MAP[$Y][$X - 1]), true));
        //error_log(var_export(!in_array($MAP[$Y][$X - 1], $not), true));
        if (isset($map[$Y][$X - 1]) && !in_array($map[$Y][$X - 1], $not))
            $LEFT = $map[$Y][$X - 1];

        if (isset($map[$Y - 1][$X]) && !in_array($map[$Y - 1][$X], $not))
            $UP = $map[$Y - 1][$X];

        //error_log(var_export($RIGHT . " " . $DOWN . " " . $LEFT . " " . $UP . " ", true));

        if ($RIGHT <= $LEFT && $RIGHT <= $DOWN && $RIGHT <= $UP && $RIGHT != 9999) {
            return "RIGHT";
        } elseif ($DOWN < $UP && $DOWN <= $LEFT && $DOWN != 9999) {
            return "DOWN";
        } elseif ($LEFT < $RIGHT && $LEFT <= $UP && $LEFT != 9999) {
            return "LEFT";
        } elseif ($UP != 9999) {
            return "UP";
        } else {
            return false;
        }
    }

    function showMAP($map, $rows, $cols)
    {
        $sMap = "\n";
        $sMap .= " ||";
        //;error_log(var_export($cols, true));
        for ($x = 0; $x < $cols; $x++) {
            if ($x < 10)
                $sMap .= " ";
            $sMap .= $x . "|";
        }

        $sMap .= "\n";

        for ($y = 0; $y < $rows; $y++) {
            if ($x < 10)
                $sMap .= " ";
            $sMap .= $y . "||";

            for ($x = 0; $x < $cols; $x++) {
                if (empty($map[$y][$x])) {
                    $sMap .= "00|";
                } else {
                    if ($map[$y][$x] < 10)
                        $sMap .= " ";

                    $sMap .= $map[$y][$x] . "|";
                }
            }

            $sMap .= "\n\n";
        }

        error_log(var_export($sMap, true));
    }

    #===========================================================================================================

    fscanf(STDIN, "%d %d %d",
        $rows, // number of rows.
        $cols, // number of columns.
        $A // number of rounds between the time the alarm countdown is activated and the time the alarm goes off.
    );
    //error_log(var_export($A, true));

    $startRow = $startCol = 0;
    $step = 0;
    $continue = 1;
    $go = 'search';
    // game loop
    while (1) {
        $map = [];
        $nextStep = $ctrlY = $ctrlX = 0;

        fscanf(STDIN, "%d %d",
            $kRow, // row where Kirk is located.
            $kCol // column where Kirk is located.
        );

        if (!$step) {
            $startRow = $kRow;
            $startCol = $kCol;
        }

        for ($i = 0; $i < $rows; $i++) {
            fscanf(STDIN, "%s",
                $ROW // C of the characters in '#.TC?' (i.e. one line of the ASCII maze).
            );

            $pos = strpos($ROW, 'C');

            if ($pos) {
                $ctrlY = $i;
                $ctrlX = $pos;
            }

            $map[] = str_split($ROW, 1);
        }

        $map[$kRow][$kCol] = '+';
        //showMAP($map, $Rows, $Cols);

        if ($go == 'goHome' || ($ctrlY == $kRow && $ctrlX == $kCol)) {
            error_log(var_export("Go To Home", true));
            $go = 'goHome';
            findPath($map, $rows, $cols, $startRow, $startCol, 0, '+');
            $map[$startRow][$startCol] = 1;
            $nextStep = goToNext($map, $kRow, $kCol);

        } else {
            $originalMap = $map;

            if ($go != 'goToC') {
                error_log(var_export("Explore", true));
                $map[$startRow][$startCol] = '.';
                $pos = findXY($map, $rows, $cols, $kRow, $kCol, 0, '?');
                findPath($map, $rows, $cols, $pos[0], $pos[1], 0, '+');
                $nextStep = goToNext($map, $kRow, $kCol);

            }


            if (!$nextStep) {
                $map = $originalMap;
                $go = 'goToC';
                error_log(var_export("Go To Control", true));
                findPath($map, $rows, $cols, $ctrlY, $ctrlX, 0, '+');
                $map[$ctrlY][$ctrlX] = 1;
                $nextStep = goToNext($map, $kRow, $kCol);
            }
        }

        //showMAP($map, $rows, $cols);
        echo $nextStep;
        echo "\n";

        $step++;
        //if ($step > 300) die; //for debug
    }
