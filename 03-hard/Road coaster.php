<?php
    $INDEXES = $GROUPS = [];

    fscanf(STDIN, "%d %d %d",
        $L, # limited number of places.
        $C, # number of times per day.
        $N  # number of groups.
    );
    //error_log(var_export('L='.$L.'C='.$C.'N='.$N, true));
    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%d", $GROUPS[]);
        $INDEXES[$i] = $i + 1;
    }
    $INDEXES[$i - 1] = 0;
    $INDEXES[$i] = 0;

    echo calculate($GROUPS, $C, $L, $INDEXES) . "\n";




    function calculate(&$GROUPS, $C, $L, &$INDEXES)
    {
        $STOP = $indexG = 0;
        //$LAST = count($GROUPS)-1;
        $CACHE = [];
        $REZ['sum'] = 0;

        for ($c = $C; $c > 0; $c--) {
            $REZ[0] = $GROUPS[$indexG];
            $indexG = $INDEXES[$indexG];
            $NEXT = $REZ[0] + $GROUPS[$indexG];
            $start = $indexG;

            if (isset($CACHE[$start])) {
                $indexG = $CACHE[$start]['ind'];
                $REZ[0] = $CACHE[$start]['sum'];
            } else {

                while ($indexG != $STOP && $NEXT <= $L) {
                    $REZ[0] = $NEXT;
                    $indexG = $INDEXES[$indexG];
                    $NEXT = $REZ[0] + $GROUPS[$indexG];
                }
                $CACHE[$start]['sum'] = $REZ[0];
                $CACHE[$start]['ind'] = $indexG;
            }

            $STOP = $indexG;
            $REZ['sum'] += $REZ[0];
        }

        return $REZ['sum'];
    }
