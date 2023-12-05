<?php
    fscanf(STDIN, "%d", $n);
    $inputs = fgets(STDIN, 32 * $n);
    $inputs = explode(" ", $inputs);
    $onePos = [];
    $steps = 0;

    for ($i = 0; $i < $n; $i++) {
        $x = intval($inputs[$i]);

        if ($x) $onePos[] = $i;
    }

    while ($n) {
        $lastOneKey = array_pop($onePos);
        $zeroKey = array_search('0', $inputs);

        if ($zeroKey !== false && $zeroKey < $lastOneKey) {
            $steps++;
            $inputs[$zeroKey] = 1;
            $inputs[$lastOneKey] = 0;
        } else {
            break;
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo("{$steps}\n");
