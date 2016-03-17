<?php
    $message = stream_get_line(STDIN, 100, "\n");
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    echo chuckNorrisCode(convertToBin($message)) . "\n";


    function convertToBin($text)
    {
        $out = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $code = decbin(ord($text[$i]));

            while (strlen($code) < 7) {
                $code = '0' . $code;
            }

            $out .= $code;
        }

        return $out;
    }

    function chuckNorrisCode($code)
    {
        $one_zero = array();
        $current = '';
        $s = 0;
        for ($i = 0; $i < strlen($code); $i++) {
            if ($current != $code[$i]) {
                $s++;
                $one_zero[$s] = '';
                $current = $code[$i];
            }
            //echo $code[$i];
            $one_zero[$s] .= $code[$i];
        }

        $out = '';
        foreach ($one_zero as $elem) {
            if ($elem[0] == 1)
                $out .= '0 ';
            else
                $out .= '00 ';

            for ($i = 0; $i < strlen($elem); $i++) {
                $out .= '0';
            }
            $out .= ' ';
        }

        return substr($out, 0, strlen($out) - 1);
    }

