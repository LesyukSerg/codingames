<?php
    $indexes = $groups = [];

    fscanf(STDIN, "%d %d %d",
        $L, # limited number of places.
        $C, # number of times per day.
        $N  # number of groups.
    );
    //error_log(var_export('L='.$L.'C='.$C.'N='.$N, true));
    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d", $groups[]);
        $indexes[$i] = $i + 1;
    }
    $indexes[$i - 1] = 0;
    $indexes[$i] = 0;

    echo calculate($groups, $C, $L, $indexes) . "\n";


    function calculate(&$groups, $C, $L, &$indexes)
    {
        $stop = $indexG = 0;
        //$LAST = count($GROUPS)-1;
        $cache = [];
        $rez['sum'] = 0;

        for ($c = $C; $c > 0; $c--) {
            $rez[0] = $groups[$indexG];
            $indexG = $indexes[$indexG];
            $next = $rez[0] + $groups[$indexG];
            $start = $indexG;

            if (isset($cache[$start])) {
                $indexG = $cache[$start]['ind'];
                $rez[0] = $cache[$start]['sum'];

            } else {
                while ($indexG != $stop && $next <= $L) {
                    $rez[0] = $next;
                    $indexG = $indexes[$indexG];
                    $next = $rez[0] + $groups[$indexG];
                }
                $cache[$start]['sum'] = $rez[0];
                $cache[$start]['ind'] = $indexG;
            }

            $stop = $indexG;
            $rez['sum'] += $rez[0];
        }

        return $rez['sum'];
    }