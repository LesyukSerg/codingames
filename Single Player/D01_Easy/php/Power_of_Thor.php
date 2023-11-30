<?
    fscanf(STDIN, "%d %d %d %d", $endX, $endY, $ThorX, $ThorY);

    while (true) {
        fscanf(STDIN, "%d", $E);
        // A single line providing the move to be made: N NE E SE S SW W or NW

        if ($ThorY < $endY) {
            echo 'S';
            $ThorY++;
        } elseif ($ThorY > $endY) {
            echo 'N';
            $ThorY--;
        }

        if ($ThorX < $endX) {
            echo 'E';
            $ThorX++;
        } elseif ($ThorX > $endX) {
            echo 'W';
            $ThorX--;
        }

        echo "\n";
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
