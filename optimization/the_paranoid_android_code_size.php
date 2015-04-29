<?php
    fscanf(STDIN, "%d %d %d %d %d %d %d %d", $nF, $w, $nR, $eF, $eP, $nC, $nAE, $nE);

    $E = [];
    for ($i = 0; $i < $nE; $i++) {
        fscanf(STDIN, "%d %d", $eF, $eP);
        $E[$eF] = $eP;
    }
    $E[$eF] = $eP;
    $fF = false;
    while (true) {
        fscanf(STDIN, "%d %d %s", $cF, $cP, $d);
        error_log(var_export($E, true));
        if (($E[$cF] < $cP && $d == 'RIGHT') || ($E[$cF] > $cP && $d == 'LEFT')) {
            if ($fF !== $cF) {
                echo "BLOCK\n";
                $fF = $cF;
            } else {
                echo "WAIT\n";
            }
        } else {
            echo "WAIT\n";
        }
    }
