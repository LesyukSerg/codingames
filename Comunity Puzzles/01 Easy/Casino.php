<?php
    fscanf(STDIN, "%d", $ROUNDS);
    fscanf(STDIN, "%d", $CASH);

    for ($i = 0; $i < $ROUNDS; $i++) {
        $BET = ceil($CASH / 4);
        $PLAY = explode(' ', stream_get_line(STDIN, 1024 + 1, "\n"));
        $NUMBER = $PLAY[0];
        $TYPE = $PLAY[1];
        $P_NUMBER = isset($PLAY[2]) ? $PLAY[2] : '';

        if ($TYPE == 'EVEN') {
            $CASH += ($NUMBER % 2 == 0 && $NUMBER > 0) ? $BET : -$BET;
        } elseif ($TYPE == 'ODD') {
            $CASH += $NUMBER % 2 == 1 ? $BET : -$BET;
        } elseif ($TYPE == 'PLAIN') {
            $CASH += $NUMBER == $P_NUMBER ? 35 * $BET : -$BET;
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo "$CASH\n";
