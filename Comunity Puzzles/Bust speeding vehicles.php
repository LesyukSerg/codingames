<?
    fscanf(STDIN, "%d", $allowedSpeed);
    fscanf(STDIN, "%d", $N);

    $km = $time = [];
    $rez = "";

    for ($i = 0; $i < $N; $i++) {
        $R = stream_get_line(STDIN, 255 + 1, "\n");
        $R = explode(' ', $R);
        $km[$R[0]][] = $R[1];
        $time[$R[0]][] = $R[2];
    }

    foreach ($km as $vID => $vKm) {
        $vTime = $time[$vID];
        $cnt = count($vTime);

        for ($i = 0; $i < $cnt - 1; $i++) {
            $timeOne = $vTime[$i + 1] - $vTime[$i];
            $kmOne = $vKm[$i + 1] - $vKm[$i];

            $speed = $kmOne / ($timeOne / 3600);

            //    error_log(var_export($vID . " " . $vKm[$i + 1] . " " . $speed, true));

            if ($speed > $allowedSpeed) {
                $rez .= $vID . " " . $vKm[$i + 1] . "\n";
            }
        }
    }

    if ($rez == '') {
        echo "OK\n";
    } else {
        echo $rez;
    }
