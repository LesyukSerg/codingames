<?php
    $FIELD = [];

    fscanf(STDIN, "%d", $L); //Length of field
    fscanf(STDIN, "%d", $H); //Height of field

    for ($i = 0; $i < $H; $i++) {
        fscanf(STDIN, "%s", $line); // field
        $FIELD[] = str_split($line);
    }

    fscanf(STDIN, "%d", $N); // Number of coordinates to check

    //error_log(var_export("\n".implode("\n",$FIELD), true));

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d %d", $X, $Y);
        //error_log(var_export("\n".implode("\n",$FIELD), true));
        //error_log(var_export($Y.'_'.$X, true));

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        error_log(var_export($FIELD[$Y][$X], true));

        echo preCalculateLakeSize($FIELD, $Y, $X);
        echo("\n");
    }

    function preCalculateLakeSize(&$FIELD, $Y, $X)
    {
        if ($FIELD[$Y][$X] == 'O') {
            $lake = [];
            calculateLakeSize($FIELD, $Y, $X, $lake, $size);
//die('1');
            foreach ($lake as $Y => $point) {
                foreach ($point as $X => $V) {
                    $FIELD[$Y][$X] = $size;
                }
            }

            return $size;

        } elseif ($FIELD[$Y][$X] == '#') {
            return 0;

        } else {
            return $FIELD[$Y][$X];
        }


    }

    function calculateLakeSize(&$FIELD, $Y, $X, &$lake, &$total)
    {
        if ($FIELD[$Y][$X] == 'O') {
            $total++;
            $lake[$Y][$X] = '0';
            $FIELD[$Y][$X] = '1';
        } else {
            return 0;
        }


        if (isset($FIELD[$Y - 1][$X]) && $FIELD[$Y - 1][$X] == 'O') { // UP
            //error_log(var_export("GO UP ".($Y - 1) . "_" . $X, true));
            calculateLakeSize($FIELD, $Y - 1, $X, $lake, $total);
        }

        if (isset($FIELD[$Y + 1][$X]) && $FIELD[$Y + 1][$X] == 'O') { // DOWN
            //error_log(var_export("GO DOWN ".($Y + 1) . "_" . $X, true));
            calculateLakeSize($FIELD, $Y + 1, $X, $lake, $total);
        }

        if (isset($FIELD[$Y][$X - 1]) && $FIELD[$Y][$X - 1] == 'O') { // LEFT
            //error_log(var_export("GO LEFT ".$Y . "_" . ($X - 1), true));
            calculateLakeSize($FIELD, $Y, $X - 1, $lake, $total);
        }

        if (isset($FIELD[$Y][$X + 1]) && $FIELD[$Y][$X + 1] == 'O') { //RIGHT
            //error_log(var_export("GO RIGHT ".$Y . "_" . ($X + 1), true));
            calculateLakeSize($FIELD, $Y, $X + 1, $lake, $total);
        }
    }