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
            fscanf(STDIN, "%d %d", $row, $col);
            $available[$row . "_" . $col] = 1;
        }

        if ($validActionCount == 9 || $validActionCount == 81) {
            echo "1 1\n";
            $my["1_1"] = ['row' => 1, 'col' => 1];

        } else {
            $eMove = check($enemy, $available);

            if ($eMove) {
                echo $eMove[0] . " " . $eMove[1] . "\n";
                $my[$eMove[0] . "_" . $eMove[1]] = ['row' => $eMove[0], 'col' => $eMove[1]];

            } else {
                $mMove = check($my, $available);

                if ($mMove) {
                    echo $mMove[0] . " " . $mMove[1] . "\n";
                    $my[$mMove[0] . "_" . $mMove[1]] = ['row' => $mMove[0], 'col' => $mMove[1]];
                } else {
                    echo "$row $col\n";
                    $my[$row . "_" . $col] = ['row' => $row, 'col' => $col];
                }
            }
        }
    }


    function check($my, $available)
    {
        $possibility = [
            [[0, 0], [0, 1], [0, 2]],
            [[1, 0], [1, 1], [1, 2]],
            [[2, 0], [2, 1], [2, 2]],

            [[0, 0], [1, 0], [2, 0]],
            [[0, 1], [1, 1], [2, 1]],
            [[0, 2], [1, 2], [2, 2]],

            [[0, 0], [1, 1], [2, 2]],
            [[0, 2], [1, 1], [2, 0]]
        ];

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