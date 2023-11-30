<?php
    date_default_timezone_set('UTC');
    fscanf(STDIN, "%d", $N);
    $times = [];

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%s", $t);
        $times[strtotime($t)] = $t;
    }

    ksort($times);
    echo current($times);

    // To debug (equivalent to var_dump): error_log(var_export($var, true));
