<?php
    $arr = [];
    fscanf(STDIN, "%d", $N);

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d", $X);

        if (!in_array($X, $arr)) {
            $arr[] = $X;
            echo $X . "\n";
        }
    }
