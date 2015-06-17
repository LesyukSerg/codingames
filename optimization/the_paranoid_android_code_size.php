<?fscanf(STDIN, "%d %d %d %d %d %d %d %d",$z,$y,$x,$F,$P,$w,$a,$n);
    $E=[];
    for($i=0;$i<$n;$i++) {
        fscanf(STDIN, "%d %d", $eF, $eP);
        $E[$eF] = $eP;
    }
    $E[$F] = $P;
    $F = '';
    while (1) {
        fscanf(STDIN, "%d %d %s", $C, $P, $d);
        if (($E[$C] < $P && $d == 'RIGHT') || ($E[$C] > $P && $d == 'LEFT'))
            echo ($F !== $C) ? "BLOCK" : "WAIT";
        else echo "WAIT";
        echo "\n";
    }