<?
    function folding($side, &$paper)
    {
        $align = [
            'L' => "RLDU",
            'R' => "LRDU",
            'D' => "UDRL",
            'U' => "DURL",
        ];

        $pos = $align[$side];

        $paper[$pos[0]] += $paper[$pos[1]];
        $paper[$pos[1]] = 1;
        $paper[$pos[2]] *= 2;
        $paper[$pos[3]] *= 2;
    }

    $order = stream_get_line(STDIN, 8 + 1, "\n");
    $side = stream_get_line(STDIN, 5 + 1, "\n");

    $len = strlen($order);
    $paper = ["L" => 1, "R" => 1, "D" => 1, "U" => 1];

    for ($i = 0; $i < $len; $i++) {
        folding($order[$i], $paper);
    }

    echo $paper[$side] . "\n";
