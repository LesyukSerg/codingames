<?
    $FIELD = [];
    $line = '';

    fscanf(STDIN, "%d", $L); //Length of field
    fscanf(STDIN, "%d", $H); //Height of field

    for ($i = 0; $i < $H; $i++) {
        fscanf(STDIN, "%s", $line); // field

        $FIELD[] = str_split($line);
        echo count($FIELD);
    }

    fscanf(STDIN, "%d", $N); // Number of coordinates to check

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d %d", $X, $Y);
        //error_log(var_export("\n".implode("\n",$FIELD), true));
        //error_log(var_export($Y.'_'.$X, true));

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        //echo count($FIELD);
        echo preCalculateLakeSize($FIELD, $Y, $X);
        echo("\n");

        /*foreach ($FIELD as $ROW) {
            error_log(var_export(implode("", $ROW), true));
        }
        error_log(var_export("\n", true));
        */
    }

    # ===============================================================================
    # ===============================================================================
    # ===============================================================================

    function preCalculateLakeSize(&$FIELD, $Y, $X)
    {
        if ($FIELD[$Y][$X] == 'O') {
            $check = [];
            $total = calculateLakeSize($FIELD, $Y, $X, $check);

            if (count($check)) {
                foreach ($check as $Y => $XX) {
                    foreach ($XX as $X => $V) {
                        $FIELD[$Y][$X] = $total;
                    }
                }
            }
        } else {
            $total = (int)$FIELD[$Y][$X];
        }


        return $total;
    }

    function calculateLakeSize(&$FIELD, $Y, $X, &$check)
    {
        $total = 1;
        $FIELD[$Y][$X] = '1';
        $check[$Y][$X] = 1;


        if (isset($FIELD[$Y - 1][$X]) && $FIELD[$Y - 1][$X] == 'O') { // UP
            //error_log(var_export("GO UP ".($Y - 1) . "_" . $X, true));
            $total += calculateLakeSize($FIELD, $Y - 1, $X, $check);
        }

        if (isset($FIELD[$Y + 1][$X]) && $FIELD[$Y + 1][$X] == 'O') { // DOWN
            //error_log(var_export("GO DOWN ".($Y + 1) . "_" . $X, true));
            $total += calculateLakeSize($FIELD, $Y + 1, $X, $check);
        }

        if (isset($FIELD[$Y][$X - 1]) && $FIELD[$Y][$X - 1] == 'O') { // LEFT
            //error_log(var_export("GO LEFT ".$Y . "_" . ($X - 1), true));
            $total += calculateLakeSize($FIELD, $Y, $X - 1, $check);
        }

        if (isset($FIELD[$Y][$X + 1]) && $FIELD[$Y][$X + 1] == 'O') { //RIGHT
            //error_log(var_export("GO RIGHT ".$Y . "_" . ($X + 1), true));
            $total += calculateLakeSize($FIELD, $Y, $X + 1, $check);
        }

        return $total;
    }
