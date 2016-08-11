<?php
    $string = stream_get_line(STDIN, 5000, "\n");
    fscanf(STDIN, "%d", $K);

    $length = strlen($string);
    $step = 1;
    $subGroup = [];

    while ($step < $length - 1) {
        $sub = $string[$step - 1];
        $used = 1;

        for ($pos = $step; $pos < $length; $pos++) {
            if (strstr($sub, $string[$pos])) {
                $sub .= $string[$pos];

            } elseif ($used < $K) {
                $used++;
                $sub .= $string[$pos];

            } else {
                break;
            }
        }

        $subGroup[strlen($sub)] = $sub;
        $step++;
    }

    krsort($subGroup);

    echo strlen(current($subGroup)) . "\n";
