<?php
    $enemy = [];
    $my = [];

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
    // game loop
    while (true) {
        fscanf(STDIN, "%d %d", $oRow, $oCol);
        $enemy[$oRow . "_" . $oCol] = ['row' => $oRow, 'col' => $oCol];

        fscanf(STDIN, "%d", $validActionCount);
        $available = [];

        for ($i = 0; $i < $validActionCount; $i++) {
            fscanf(STDIN, "%d %d", $aRow, $aCol);
            $available[$aRow . "_" . $aCol] = 1;
        }

        $R_block = intval(3 * (ceil(($aRow + 1) / 3) - 1));
        $C_block = intval(3 * (ceil(($aCol + 1) / 3) - 1));
        //error_log(var_export($R_block."_".$C_block, true));
        //error_log(var_export($available, true));
        //error_log(var_export($available[$R_block + 1][$C_block + 1], true));

        if ($validActionCount == 81) {
            echo "4 4\n";
            $my["4_4"] = ['row' => 4, 'col' => 4];

        } elseif ($validActionCount == 9) {
            $row = $R_block + 1;
            $col = $C_block + 1;

            echo "$row $col\n";
            $my[$row . "_" . $col] = ['row' => $row, 'col' => $col];


        } elseif ($validActionCount > 9) {
            $move = allCheck($enemy, $available);

            if (!$move) {
                $move = allCheck($my, $available);
            }

            if ($move) {
                echo $move[0] . " " . $move[1] . "\n";
                $my[$move[0] . "_" . $move[1]] = ['row' => $move[0], 'col' => $move[1]];

            } else {
                echo "$aRow $aCol\n";
                $my[$aRow . "_" . $aCol] = ['row' => $aRow, 'col' => $aCol];
            }


        } else {
            //error_log(var_export($available, true));
            $eMove = check($enemy, $available, $R_block, $C_block);

            if ($eMove) {
                echo $eMove[0] . " " . $eMove[1] . "\n";
                $my[$eMove[0] . "_" . $eMove[1]] = ['row' => $eMove[0], 'col' => $eMove[1]];

            } else {
                $mMove = check($my, $available, $R_block, $C_block);
                //error_log(var_export($mMove, true));

                if ($mMove) {
                    echo $mMove[0] . " " . $mMove[1] . "\n";
                    $my[$mMove[0] . "_" . $mMove[1]] = ['row' => $mMove[0], 'col' => $mMove[1]];
                } else {
                    echo "$aRow $aCol\n";
                    $my[$aRow . "_" . $aCol] = ['row' => $aRow, 'col' => $aCol];
                }
            }
        }
    }


    function check($my, $available, $ROW, $COL)
    {
        $possibility = [
            [[$ROW, $COL], [$ROW, $COL + 1], [$ROW, $COL + 2]],
            [[$ROW + 1, $COL], [$ROW + 1, $COL + 1], [$ROW + 1, $COL + 2]],
            [[$ROW + 2, $COL], [$ROW + 2, $COL + 1], [$ROW + 2, $COL + 2]],

            [[$ROW, $COL], [$ROW + 1, $COL], [$ROW + 2, $COL]],
            [[$ROW, $COL + 1], [$ROW + 1, $COL + 1], [$ROW + 2, $COL + 1]],
            [[$ROW, $COL + 2], [$ROW + 1, $COL + 2], [$ROW + 2, $COL + 2]],

            [[$ROW, $COL + 0], [$ROW + 1, $COL + 1], [$ROW + 2, $COL + 2]],
            [[$ROW, $COL + 2], [$ROW + 1, $COL + 1], [$ROW + 2, $COL]]
        ];
        //error_log(var_export($possibility, true));

        foreach ($possibility as $k => $one) {
            $cnt = 0;
            for ($n = 0; $n < 3; $n++) {
                if (isset($my[$one[$n][0] . "_" . $one[$n][1]])) {
                    $cnt++;
                }
            }

            if ($cnt == 2) {
                foreach ($possibility[$k] as $el) {
                    if (isset($available[$el[0] . "_" . $el[1]])) {
                        return $el;
                    }
                }
            }
        }
    }

    function allCheck($some, $available)
    {
        for ($R = 0; $R < 3; $R++) {
            for ($C = 0; $C < 3; $C++) {
                $move = check($some, $available, $R * 3, $C * 3);

                if ($move) {
                    return $move;
                }
            }
        }
    }
