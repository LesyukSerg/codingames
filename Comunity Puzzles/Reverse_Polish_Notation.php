<?php
    function op1(&$stack, $one)
    {
        $res = 1;
        $y = array_pop($stack);
        $x = array_pop($stack);

        if ($x !== null && $y !== null) {
            switch ($one) {
                case 'ADD':
                    $stack[] = $x + $y;
                    break;

                case 'SUB':
                    $stack[] = $x - $y;
                    break;

                case 'MUL':
                    $stack[] = $x * $y;
                    break;

                case 'DIV':
                    if (!$y) {
                        $stack[] = "ERROR";
                        $res = 0;
                    } else {
                        $stack[] = $x / $y;
                    }

                    break;

                case 'MOD':
                    if (!$y) {
                        $stack[] = "ERROR";
                        $res = 0;
                    } else {
                        $stack[] = $x % $y;
                    }

                    break;

                case 'SWP':
                    $stack[] = $y;
                    $stack[] = $x;
                    break;
            }
        } else {
            $stack[] = "ERROR";
            $res = 0;
        }

        return $res;
    }

    function op2(&$stack, $one)
    {
        $res = 1;
        switch ($one) {
            case 'POP':
                array_pop($stack);
                break;

            case 'DUP':
                $x = array_pop($stack);

                if ($x !== null) {
                    $stack[] = $x;
                    $stack[] = $x;
                } else {
                    $stack[] = "ERROR";
                    $res = 0;
                }
                break;

            case 'ROL':
                array_pop($stack);
                $i = count($stack) - 3;

                if (isset($stack[$i])) {
                    $stack[] = $stack[$i];
                    unset($stack[$i]);
                    $stack = array_merge($stack); //reindex

                } else {
                    $stack[] = "ERROR";
                    $res = 0;
                }

                break;
        }

        return $res;
    }

    #-----------------------------------------------------

    $op1 = ['ADD', 'SUB', 'MUL', 'DIV', 'MOD', 'SWP'];
    $op2 = ['POP', 'DUP', 'ROL'];

    fscanf(STDIN, "%d", $N);
    $inputs = explode(" ", fgets(STDIN));
    $stack = [];
    $res = 1;

    foreach ($inputs as $one) {
        $one = trim($one);

        if (in_array($one, $op1)) {
            $res = op1($stack, $one);

        } elseif (in_array($one, $op2)) {
            $res = op2($stack, $one);

        } else {
            $stack[] = $one;
        }

        if (!$res) break;
    }

    echo implode(' ', $stack) . "\n";
