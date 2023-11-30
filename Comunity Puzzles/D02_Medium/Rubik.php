<?php
    fscanf(STDIN, "%d", $N);

    if ($N > 2) {
        $M = pow($N, 3) - pow($N - 2, 3);
    } else {
        $M = pow($N, 3);
    }

    echo "$M\n";
