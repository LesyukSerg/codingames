<?php
    fscanf(STDIN, "%d", $N);
    $primes = get_primes($N);
    $primes[2] = 2;
    ksort($primes);
    $divisors = [];

    for ($i = 2; $i <= $N; $i++) {
        $divisor = 1;
        $all = [];

        if (isset($primes[$i])) {
            $divisors[$i] = 1 + $i;

        } else {
            $simple = [];
            $half = floor($i / 2);
            $one = current($primes);
            $n = $i;

            while ($one <= $half && $n > 1) {
                if (isset($primes[$n])) {
                    $simple[] = $n;
                    break;
                }

                if ($n % $one == 0) {
                    $n = $n / $one;
                    $simple[] = $one;
                } else {
                    $one = next($primes);
                }
            }
            reset($primes);

            all_divisors($simple, 1, $all);

            $divisors[$i] = array_sum($all) + 1;
        }
        //error_log(var_export($divisor, true));
    }

    //error_log(var_export($divisors, true));

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    echo array_sum($divisors) + 1 . "\n";

    function all_divisors($simple, $el, &$all)
    {
        foreach ($simple as $k => $sm) {
            $delim = $sm * $el;
            $all[$delim] = $delim;
            unset($simple[$k]);

            if (count($simple)) all_divisors($simple, $delim, $all);
        }
    }

    function get_primes($n)
    {
        $primes = [];

        for ($i = 3; $i < $n + 1; $i = $i + 2) {
            $added = 0;
            if ($i > 10 && $i % 10 == 5) continue;

            foreach ($primes as $one) {
                if ($one * $one - 1 > $i) {
                    $added = 1;
                    $primes[$i] = $i;
                    break;
                }

                if ($i % $one == 0) {
                    $added = 1;
                    break;
                }
            }

            if (!$added) {
                $primes[$i] = $i;
            }

        }

        return $primes;
    }
