<?php
    $N = $number = 81;

    while (1) {
        if ($N != (int)$N) {
            break;
        }

        $N = sqrt($N);
    }

    while (1) {
        $N = pow($N, 2);

        if ($N > $number) {
            echo $N;
        }
    }