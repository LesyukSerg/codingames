<?
    fscanf(STDIN, "%d", $N);
    fscanf(STDIN, "%d", $C);

    error_log(var_export($N . ' ' . $C, true));

    $MONEY = [];
    $CASH = [];
    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d", $B);
        $CASH[] = $B;
    }

    sort($CASH);

    foreach ($CASH as $i => $B) {
        if ($C > 0) {
            $part = floor($C / ($N - $i));

            if ($B > $part) {
                $MONEY[] = $part;
                $C -= $part;
            } else {
                $MONEY[] = $B;
                $C -= $B;
            }
        } else {
            $MONEY[] = 0;
        }
    }

    if ($C == 0) {
        echo implode("\n", $MONEY);
    } else {
        echo "IMPOSSIBLE\n";
    }
