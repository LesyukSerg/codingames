<?php
    $display = $n2 = $n1 = '';
    $operation = '';
    $was = 0;
    $log = [];

    fscanf(STDIN, "%d", $n);

    for ($i = 0; $i < $n; $i++) {
        $key = stream_get_line(STDIN, 2 + 1, "\n");
        $log[] = $key;

        if (isOperation($key)) {
            //error_log(var_export("Operation | $key ", true));

            if ($key == 'AC') {
                $n2 = $n1 = '';
                $display = '0';
                $was = 0;
            }

            if ($was && $n2) {
                if (!isOperation($log[$i - 1]) || ($log[$i - 1] == '=' && $key == '=')) {
                    $n1 = doOperation($n1, $n2, $operation);
                }

                $display = $n1;
                if ($key != '=') $operation = $key;

            } else {
                $operation = $key;
            }

            $was = $key == 'AC' ? 0 : 1;

        } else {
            //error_log(var_export("key = $key ", true));
            if ($log[$i - 1] == '=') {
                $was = 0;
                $n2 = $n1 = '';
            }

            if (!$was) {
                $n1 .= $key;
                $display = $n1;
            } else {
                if (isOperation($log[$i - 1])) {
                    $n2 = $key;
                } else {
                    $n2 .= $key;
                }

                $display = $n2;
            }
        }

        echo $display . "\n";
    }

# =============================================================================

    function isOperation($key): bool
    {
        $operations = ['+', '-', '/', 'x', '=', 'AC'];

        return in_array($key, $operations);
    }

    function doOperation($n1, $n2, $operation)
    {
        //error_log(var_export("doOperation operation | $n1 $operation $n2 ", true));
        $res = 0;

        if ($operation) {
            switch ($operation) {
                case 'AC' :
                    $res = 0;
                    break;
                case '+' :
                    $res = $n1 + $n2;
                    break;
                case '-' :
                    $res = $n1 - $n2;
                    break;
                case '/' :
                    $res = round($n1 / $n2, 3);
                    break;
                case 'x' :
                    $res = $n1 * $n2;
                    break;
            }
        }

        return $res;
    }