<?
    function move_to($x, $y, $type, $from)
    {
        if (in_array($type, [1, 3, 7, 8, 9, 12, 13])) {

            $y++;
        } elseif ($type == 2) {
            if ($from == 'LEFT') $x++;
            elseif ($from == 'RIGHT') $x--;
        } elseif ($type == 4) {
            if ($from == 'TOP') $x--;
            elseif ($from == 'RIGHT') $y++;
        } elseif ($type == 5) {
            if ($from == 'TOP') $x++;
            elseif ($from == 'LEFT') $y++;
        } elseif ($type == 6) {
            if ($from == 'LEFT') $x++;
            elseif ($from == 'RIGHT') $x--;
        } elseif ($type == 10) {
            $x--;
        } elseif ($type == 11) {
            $x++;
        }

        return $x . ' ' . $y;
    }


    fscanf(STDIN, "%d %d",
        $W, // number of columns.
        $H // number of rows.
    );

    $PLACE = [];
    for ($i = 0; $i < $H; $i++) {

        $LINE = stream_get_line(STDIN, 200, "\n"); // represents a line in the grid and contains W integers. Each integer represents one room of a given type.

        $PLACE[] = explode(' ', $LINE); //$PLACE[y][x]
    }
    fscanf(STDIN, "%d",
        $EX // the coordinate along the X axis of the exit (not useful for this first mission, but must be read).
    );

// game loop
    while (true) {
        fscanf(STDIN, "%d %d %s",
            $XI,
            $YI,
            $POS
        );
        error_log(var_export($XI . ' ' . $YI, true));
        error_log(var_export($POS, true));

        $type = $PLACE[$YI][$XI];
        error_log(var_export('type = ' . $type, true));
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        echo move_to($XI, $YI, $type, $POS) . "\n";

        //echo("0 0\n"); // One line containing the X Y coordinates of the room in which you believe Indy will be on the next turn.
    }
