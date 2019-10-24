<?php
    fscanf(STDIN, "%d", $N);
    $inputs = explode(" ", fgets(STDIN));
    $stones = [];

    foreach ($inputs as $one) {
        if (!isset($stones[$one])) {
            $stones[$one] = 1;
        } else {
            $stones[$one]++;
        }
    }

    ksort($stones);

    while (max($stones) > 1) {
        foreach ($stones as $k => $count) {
            if ($count > 1) {
                if (!isset($stones[$k + 1])) $stones[$k + 1] = 0;

                $stones[$k + 1] += intval(floor($count / 2));

                if ($count % 2 == 0) {
                    unset($stones[$k]);
                } else {
                    $stones[$k] = 1;
                }

                break;
            }
        }
    }

    echo count($stones);
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));