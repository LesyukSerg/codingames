<?php
    fscanf(STDIN, "%s", $BEGIN);
    fscanf(STDIN, "%s", $END);

    $remain = strtotime($END) - strtotime($BEGIN);

    $Y = date("Y", $remain) - date("Y", 0);
    $M = date("n", $remain) - 1;
    $D = $remain / (60 * 60 * 24);

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    $res = [];
    if ($Y > 1) {
        $res[] = "$Y years,";
    } elseif ($Y == 1) {
        $res[] = "$Y year,";
    }

    if ($M > 1) {
        $res[] = "$M months,";
    } elseif ($M == 1) {
        $res[] = "$M month,";
    }

    $res[] = "total $D days";
    echo implode(" ", $res) . "\n";
