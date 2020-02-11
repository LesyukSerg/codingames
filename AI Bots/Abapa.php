<?php
    while (1) {
        $inputs = explode(" ", fgets(STDIN));
        $possible = $seeds = [];

        for ($i = 0; $i < 12; $i++) $seeds[$i] = intval($inputs[$i]);
        $mySeeds = array_slice($seeds, 0, 6);

        foreach ($mySeeds as $k => $v) {
            $tSeeds = $seeds;
            $tSeeds[$k] = 0;

            $possible[] = [
                'myPos' => $k,
                'opPos' => $k + $v,
                'seeds' => $tSeeds
            ];
        }

        $great = [];
        foreach ($possible as $one) {
            if (in_array($one['seeds'][$one['opPos']], [1, 2, 3])) {
                $great[$one['myPos']] = $one['seeds'][$one['opPos']];
            }
        }

        if (count($great)) {
            arsort($great);
            current($great);
            $myPos = array_search(current($great), $great);
            echo $myPos . "\n";
        } else {
            do {
                $k = array_rand($possible);
                $one = $possible[$k];
            } while (!$mySeeds[$one['myPos']]);
//            error_log(var_export($one, true));
            echo $one['myPos'] . "\n";
        }

        //     error_log(var_export($possible, true));
        // error_log(var_export($seeds, true));
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));

        // echo("0\n");
    }