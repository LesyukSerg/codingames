<?
    fscanf(STDIN, "%d %d", $n, $k);
    $primes = explode(" ", trim(fgets(STDIN)));
    $odd = $even = [];

    for ($level = 0; $level < $k; $level++) {
        $one = [];
        collect($n, $primes, [], $level, $one);

        if ($level % 2 == 0) $even[] = array_sum($one);
        else $odd[] = array_sum($one);
    }

//    error_log(var_export($even, true));
//    error_log(var_export($odd, true));

    echo array_sum($even) - array_sum($odd);

    #==========================================================================
    #==========================================================================
    #==========================================================================

    function collect($n, $primes, $p, $level, &$diff)
    {
        foreach ($primes as $one) {
            $tp = $p;
            if (!$tp || $one > max($tp)) {
                $tp[] = $one;

                if ($level) {
                    collect($n, $primes, $tp, $level - 1, $diff);
                } else {
                    $diff[implode('*', $tp)] = floor($n / array_product($tp));
                }
            }
        }
    }