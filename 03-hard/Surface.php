<?php
    $FIELD = [];

    fscanf(STDIN, "%d", $L); //Length of field
    fscanf(STDIN, "%d", $H); //Height of field

    for ($i = 0; $i < $H; $i++) {
        fscanf(STDIN, "%s", $FIELD[]); // field
    }

    fscanf(STDIN, "%d", $N); // Number of coordinates to check

    //error_log(var_export("\n".implode("\n",$FIELD), true));
    $lakes = [];
    for ($i = 0; $i < $N; $i++) {
        $X = $Y = 0;
        fscanf(STDIN, "%d %d", $X, $Y);
        //error_log(var_export($Y.' '.$X, true));
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        //echo preCalculateLakeSize($FIELD, $Y, $X);

        if (isset($lakes[$Y][$X])) {
            echo $lakes[$Y][$X];

        } else {
            if ($FIELD[$Y][$X] == 'O') {
                $positions[0] = array('y' => $Y, 'x' => $X);
                echo calculateLakeSize2($FIELD, $positions, $lake);
                $lakes = $lakes + $lake;
            } else {
                echo 0;
            }
        }

        echo("\n");
        //error_log(var_export("\n".implode("\n",$FIELD), true));
    }

    function calculateLakeSize2($FIELD, $positions, &$lake)
    {
        $total = 0;

        while (count($positions)) {
            $pos = current($positions);

            $Y = $pos['y'];
            $X = $pos['x'];

            if ($FIELD[$Y][$X] == 'O') {
                $lake[$Y][$X] = $FIELD[$Y][$X] = '0';
                $total++;
                array_shift($positions);

            } elseif ($FIELD[$Y][$X] === '0') {
                array_shift($positions);
                continue;

            } else {
                return $total;
            }
            //error_log(var_export($total, true));

            if (isset($FIELD[$Y - 1][$X]) && $FIELD[$Y - 1][$X] == 'O') { // UP
                $positions[] = array('y' => $Y - 1, 'x' => $X);
            }

            if (isset($FIELD[$Y + 1][$X]) && $FIELD[$Y + 1][$X] == 'O') { // DOWN
                $positions[] = array('y' => $Y + 1, 'x' => $X);
            }

            if (isset($FIELD[$Y][$X - 1]) && $FIELD[$Y][$X - 1] == 'O') { // LEFT
                $positions[] = array('y' => $Y, 'x' => $X - 1);
            }

            if (isset($FIELD[$Y][$X + 1]) && $FIELD[$Y][$X + 1] == 'O') { //RIGHT
                $positions[] = array('y' => $Y, 'x' => $X + 1);
            }
        }

        foreach ($lake as $Y => $line) {
            foreach ($line as $X => $pos) {
                $lake[$Y][$X] = $total;
            }
        }


        return $total;
    }

    function preCalculateLakeSize($FIELD, $Y, $X)
    {
        $size = 0;
        calculateLakeSize($FIELD, $Y, $X, $size);

        return $size;
    }

    function calculateLakeSize(&$FIELD, $Y, $X, &$total)
    {
        if ($FIELD[$Y][$X] == 'O') {
            $total++;
            $FIELD[$Y][$X] = '0';
        } else {
            return 0;
        }

        if (isset($FIELD[$Y - 1][$X]) && $FIELD[$Y - 1][$X] == 'O') { // UP
            //error_log(var_export("GO UP ".($Y - 1) . "_" . $X, true));
            calculateLakeSize($FIELD, $Y - 1, $X, $total);
        }

        if (isset($FIELD[$Y + 1][$X]) && $FIELD[$Y + 1][$X] == 'O') { // DOWN
            //error_log(var_export("GO DOWN ".($Y + 1) . "_" . $X, true));
            calculateLakeSize($FIELD, $Y + 1, $X, $total);
        }

        if (isset($FIELD[$Y][$X - 1]) && $FIELD[$Y][$X - 1] == 'O') { // LEFT
            //error_log(var_export("GO LEFT ".$Y . "_" . ($X - 1), true));
            calculateLakeSize($FIELD, $Y, $X - 1, $total);
        }

        if (isset($FIELD[$Y][$X + 1]) && $FIELD[$Y][$X + 1] == 'O') { //RIGHT
            //error_log(var_export("GO RIGHT ".$Y . "_" . ($X + 1), true));
            calculateLakeSize($FIELD, $Y, $X + 1, $total);
        }

        return 0;
    }