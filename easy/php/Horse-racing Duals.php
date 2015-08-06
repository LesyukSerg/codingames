<?php
    fscanf(STDIN, "%d", $N);
    $D = $H = [];


    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d", $H[]);
    }
    sort($H);
    $N--;

    for ($i = 0; $i < $N; $i++) {
        $D[] = abs($H[$i] - $H[$i + 1]);
    }

    echo(min($D) . "\n");
