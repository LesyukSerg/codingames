<?php
    fscanf(STDIN, "%d", $N);
    $M = [];

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%s %s %s", $op, $arg1, $arg2);
        $M[] = [
            'op'   => $op,
            'arg1' => $arg1,
            'arg2' => $arg2
        ];
    }

    foreach ($M as $l => $one) {
        operate($M, $l);

        echo $M[$l]['rez'] . "\n";
    }

    #==========================================================================

    function operate(&$M, $l)
    {
        $op = $M[$l]['op'];
        $arg1 = $M[$l]['arg1'];
        $arg2 = $M[$l]['arg2'];

        if ($arg1[0] == '$') {
            $p = str_replace('$', '', $arg1);

            while (!isset($M[$p]['rez'])) operate($M, $p);

            $arg1 = $p != $arg1 ? $M[$p]['rez'] : $arg1;
        }

        if ($arg2[0] == '$') {
            $p = str_replace('$', '', $arg2);

            while (!isset($M[$p]['rez'])) operate($M, $p);

            $arg2 = $p != $arg2 ? $M[$p]['rez'] : $arg2;
        }

        if ($op == 'VALUE') {
            $M[$l]['rez'] = $arg1;

        } elseif ($op == 'ADD') {
            $M[$l]['rez'] = $arg1 + $arg2;

        } elseif ($op == 'SUB') {
            $M[$l]['rez'] = $arg1 - $arg2;

        } elseif ($op == 'MULT') {
            $M[$l]['rez'] = $arg1 * $arg2;
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
