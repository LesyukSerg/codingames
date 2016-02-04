<?
    fscanf(STDIN, "%d", $FIELD_X);
    fscanf(STDIN, "%d", $FIELD_Y);
    fscanf(STDIN, "%d", $count_pos);
    //error_log(var_export('$FIELD_X - ' . $FIELD_X, true));
    //error_log(var_export('$FIELD_Y - ' . $FIELD_Y, true));

    $map = [];
    $ARROW = "UP";

    // fill in map area
    for ($x = 0; $x < $FIELD_X; $x++) {
        for ($y = 0; $y < $FIELD_Y; $y++) {
            $map[$y][$x] = '.';
        }
    }

    // game loop
    while (true) {
        $I_SEE = [];

        fscanf(STDIN, "%s", $I_SEE['UP']);
        fscanf(STDIN, "%s", $I_SEE['NEXT']);
        fscanf(STDIN, "%s", $I_SEE['BOTTOM']);
        fscanf(STDIN, "%s", $I_SEE['PREV']);


        for ($i = 0; $i < $count_pos; $i++) {
            fscanf(STDIN, "%d %d", $X, $Y);
            $map[$Y][$X] = $i;
            //error_log(var_export('-----------------------', true));
            //error_log(var_export('Y=' . $Y . ' | X=' . $X, true));
        }

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        //echo("A, B, C, D or E\n");

        $map[$Y][$X] = '+';
        if ($map[$Y][$X - 1] == '.') $map[$Y][$X - 1] = $I_SEE['PREV'];
        if ($map[$Y][$X + 1] == '.') $map[$Y][$X + 1] = $I_SEE['NEXT'];
        if ($map[$Y - 1][$X] == '.') $map[$Y - 1][$X] = $I_SEE['UP'];
        if ($map[$Y + 1][$X] == '.') $map[$Y + 1][$X] = $I_SEE['BOTTOM'];

        show_map($map, $FIELD_Y);

        echo movement($ARROW, $I_SEE, $FIELD_X, $FIELD_Y, $X, $Y);
        echo "\n";
    }


    function movement(&$ARROW, $I_SEE, $MAX_X, $MAX_Y, $X, $Y)
    {
        $ARROWS = array('C' => 'UP', 'D' => 'DOWN', 'A' => 'RIGHT', 'E' => 'LEFT');
        $move = 'B';
        $can_move = [];
        // A - NEXT
        // B - ???
        // C - UP
        // D - DOWN
        // E - BACK

        // MOVE UP ---------------------------------------
        if ($ARROW == 'UP') {
            if ($I_SEE['UP'] != '#' && $Y > 0) {
                $can_move[] = "C";
            }

            if ($I_SEE['NEXT'] != '#' && $X < $MAX_X - 1) {
                $can_move[] = "A";
            }

            if ($I_SEE['PREV'] != '#' && $Y > 0) {
                $can_move[] = "E";
            }

            if (count($can_move)) {
                $k = array_rand($can_move);
                $move = $can_move[$k];
                $ARROW = $ARROWS[$move];
            }

        } // MOVE RIGHT ---------------------------------
        elseif ($ARROW == 'RIGHT') {
            if ($I_SEE['NEXT'] != '#' && $X < $MAX_X - 1) {
                $can_move[] = "A";
            }

            if ($I_SEE['BOTTOM'] != '#' && $Y < $MAX_Y - 1) {
                $can_move[] = "D";

            }

            if ($I_SEE['UP'] != '#' && $Y > 0) {
                $can_move[] = "C";
            }

            if (count($can_move)) {
                $k = array_rand($can_move);
                $move = $can_move[$k];
                $ARROW = $ARROWS[$move];
            }


        } // MOVE DOWN ---------------------------------
        elseif ($ARROW == 'DOWN') {
            if ($I_SEE['BOTTOM'] != '#' && $Y < $MAX_Y - 1) {
                $can_move[] = "D";
            }

            if ($I_SEE['PREV'] != '#' && $Y > 0) {
                $can_move[] = "E";
            }

            if ($I_SEE['NEXT'] != '#' && $X < $MAX_X - 1) {
                $can_move[] = "A";
            }

            if (count($can_move)) {
                $k = array_rand($can_move);
                $move = $can_move[$k];
                $ARROW = $ARROWS[$move];
            }


        } // MOVE LEFT --------------------------------
        elseif ($ARROW == 'LEFT') {
            if ($I_SEE['PREV'] != '#' && $Y > 0) {
                $can_move[] = "E";
            }

            if ($I_SEE['UP'] != '#' && $Y > 0) {
                $can_move[] = "C";
            }

            if ($I_SEE['BOTTOM'] != '#' && $Y < $MAX_Y - 1) {
                $can_move[] = "D";
            }

            if (count($can_move)) {
                $k = array_rand($can_move);
                $move = $can_move[$k];
                $ARROW = $ARROWS[$move];
            }
        }

        error_log(var_export($move . ' ' . $ARROW, true));

        if ($move == 'B') {
            if ($ARROW == 'LEFT') {
                $ARROW = 'UP';

            } elseif ($ARROW == 'UP') {
                $ARROW = 'RIGHT';

            } elseif ($ARROW == 'RIGHT') {
                $ARROW = 'DOWN';

            } elseif ($ARROW == 'DOWN') {
                $ARROW = 'LEFT';
            }

            return movement($ARROW, $I_SEE, $MAX_X, $MAX_Y, $X, $Y);
        }

        return $move;
    }

    function show_map($map, $Y)
    {
        for ($y = 0; $y < $Y; $y++) {
            //if ($y < 10) $y = "0$y";

            error_log(var_export($y . ' = ' . implode('|', $map[$y]), true));
        }
    }