<?php
    fscanf(STDIN, "%d", $N);

    $SEQ = array();

    for ($i = 0; $i < $N; $i++) {
        fscanf(STDIN, "%s", $S);

        if (strlen($S)) {
            $SEQ[] = $S;
        }
    }

    // delete Almost superimposed --------------------
    /*
    for ($i1=0; $i1<$N-1; $i1++) {
        for ($i2=$i1+1; $i2<$N; $i2++) {

            if(strlen($SEQ[$i1]) == strlen($SEQ[$i2])) {
                if ($SEQ[$i1] == $SEQ[$i2]) {
                    unset($SEQ[$i1]);
                    break;
                }
            } else {
                if (strstr($SEQ[$i2], $SEQ[$i1])) {
                    unset($SEQ[$i1]);
                    break;
                }
            }
        }
    }
    */
    //$SEQ = array('BA','CB','ABCD');
    // main  --------------------
    $rez = [];
    $start = current($SEQ);
    //error_log(var_export($SEQ, true));

    do {
        $rez[] = analyze($SEQ);

        $curr = current($SEQ);
        array_shift($SEQ);
        array_push($SEQ, $curr);
        error_log(var_export($rez, true));

    } while ($start != current($SEQ));

    echo min($rez);


    # =========================================================================
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    function analyze($SEQ)
    {
        while (count($SEQ) > 1) {
            usort($SEQ, 'lsort');
            error_log(var_export($SEQ, true));

            $ELEMENT = $SEQ[0];
            $word = $ELEMENT;
            $find_r = '';
            $find_l = '';
            $findr_el = 0;
            $findl_el = 0;

            if (finddublicates($SEQ, $ELEMENT)) continue;

            if (count($SEQ) == 1) break;


            while (strlen($word) && !$find_r) {
                error_log(var_export('W = ' . $word, true));

                for ($i = 1; $i < count($SEQ); $i++) {
                    $find_temp = strrpos($SEQ[$i], $word);
                    error_log(var_export($find_temp, true));

                    if (strlen($SEQ[$i]) == strlen($word)) {
                        continue;
                    } else {
                        if ($find_temp && strlen($SEQ[$i]) == $find_temp + strlen($word)) {
                            error_log(var_export('R = ' . $word . ' ~ ' . $SEQ[$i] . ' ' . strpos($SEQ[$i], $word), true));
                            $findr_el = $i;
                            $find_r = $word;
                            break;
                        }
                    }
                }
                error_log(var_export('------------', true));

                if ($find_r) break;

                $word = substr($word, 0, strlen($word) - 1);
            }


            error_log(var_export('>>>>>>>>>>>>>>', true));
            $word = $ELEMENT;


            while (strlen($word) && !$find_l) {
                error_log(var_export('W = ' . $word, true));

                for ($i = 1; $i < count($SEQ); $i++) {
                    $find_temp = strpos($SEQ[$i], $word);

                    if (strlen($SEQ[$i]) == strlen($word)) {
                        continue;
                    } else {
                        if ($find_temp === 0) {
                            error_log(var_export('L = ' . $word . ' ~ ' . $SEQ[$i] . ' ' . strpos($SEQ[$i], $word), true));
                            $find_l = $word;
                            $findl_el = $i;
                            break;
                        }
                    }
                }
                error_log(var_export('------------', true));

                if ($find_l) break;

                $word = substr($word, 1, strlen($word));
            }
            error_log(var_export('==============', true));
            error_log(var_export($find_r . ' ' . $find_l, true));

            if (strlen($find_l) == 0 && strlen($find_r) == 0) {
                $SEQ[1] .= $ELEMENT;
            } else {
                if (strlen($find_l) > strlen($find_r)) {
                    error_log(var_export($ELEMENT . ' => ' . $SEQ[$findl_el], true));
                    $remaind = substr($ELEMENT, 0, strlen($ELEMENT) - strlen($find_l));

                    $SEQ[$findl_el] = $remaind . $SEQ[$findl_el];
                } else {
                    error_log(var_export($ELEMENT . ' => ' . $SEQ[$findr_el], true));
                    $remaind = substr($ELEMENT, strlen($find_r), strlen($ELEMENT));
                    $SEQ[$findr_el] .= $remaind;
                }
            }

            unset($SEQ[0]);
            usort($SEQ, 'lsort');
        }

        return strlen(current($SEQ));
    }

    function lsort($a, $b)
    {
        return strlen($a) - strlen($b);
    }

    function finddublicates(&$SEQ, $ELEMENT)
    {
        $find = 0;
        for ($i = 1; $i < count($SEQ); $i++) {

            if (strlen($SEQ[$i]) == strlen($ELEMENT)) {
                if ($SEQ[$i] == $ELEMENT) {
                    unset($SEQ[0]);
                    $find++;
                }
            } else {
                if (strstr($SEQ[$i], $ELEMENT)) {
                    unset($SEQ[0]);
                    $find++;
                    break;
                }
            }
        }

        return $find;

        error_log(var_export($SEQ, true));
    }
