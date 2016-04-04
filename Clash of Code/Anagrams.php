<?php
    fscanf(STDIN, "%s %s", $S1, $S2);

    $arr_S1 = $arr_S = [];
    $k = 0;

    for ($i = 0; $i < strlen($S1); $i++) {
        $arr_S[$S1[$i]]++;
    }

    if (strlen($S1) == strlen($S2)) {
        for ($i = 0; $i < strlen($S2); $i++) {
            if (isset($arr_S[$S2[$i]])) {
                $arr_S[$S2[$i]]--;

                if (!$arr_S[$S2[$i]]) {
                    unset($arr_S[$S2[$i]]);
                }
            } else {
                echo "0";
                break;
            }
        }
    }


    if (!count($arr_S)) {
        echo "1";
    } else {
        echo "0";
    }

// Write an action using echo(). DON'T FORGET THE TRAILING \n
// To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo "\n";
