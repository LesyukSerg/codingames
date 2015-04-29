<?php
    fscanf(STDIN, "%d %d %d %d", $LX, $LY, $TX, $TY);
    $X = $TX;
    $Y = $TY;
    while (true) {
        fscanf(STDIN, "%d", $E);
        $move = '';
        if ($Y < $LY) {
            echo 'S';
            $Y++;
        } elseif ($TY > $LY) {
            echo 'N';
            $Y--;
        }

        if ($X < $LX) {
            echo 'E';
            $X++;
        } elseif ($X > $LX) {
            echo 'W';
            $X--;
        }
        echo "\n";
    }
