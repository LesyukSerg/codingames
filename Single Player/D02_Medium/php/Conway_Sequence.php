<?
    function funRecurs($R, $L)
    {
        if ($L > 1) {
            //error_log(var_export($R, true));
            $COUNT = $FLAG = $REZ = '';
            $LINE = explode(' ', $R);

            foreach ($LINE as $N) {
                if ($FLAG != $N) {
                    $REZ .= $COUNT . ' ' . $FLAG . ' ';

                    $FLAG = $N;
                    $COUNT = 0;
                }
                $COUNT++;
            }
            $REZ .= $COUNT . ' ' . $FLAG;
            $REZ = trim($REZ);

            funRecurs($REZ, --$L);
        } else {
            echo "$R\n";
        }
    }

    fscanf(STDIN, "%d", $R);
    fscanf(STDIN, "%d", $L);

    funRecurs($R, $L);
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
