<?php
    $deckP1 = $deck2 = [];

    /*fscanf(STDIN, "%d", $n); // the number of cards for player 1
    for ($i = 0; $i < $n; $i++) {
        fscanf(STDIN, "%s", $deckP1[]); // the n cards of player 1
    }

    fscanf(STDIN, "%d", $m); // the number of cards for player 2
    for ($i = 0; $i < $m; $i++) {
        fscanf(STDIN, "%s", $deckP2[]); // the m cards of player 2
    }*/

    $deckP1 = "4C 4S QS 7D QD AS 6H 5D 2S 10S 3S 2C JS 10C 2D 5H KC AD KD JD JH 9H 7S 6S 3D 8S";
    $deckP1 = explode(' ',$deckP1);

    $deckP2 = "3H 7C KS 9D 4D 6D 8D JC 9S 10H 5C 8H AC 2H 6C 7H 10D 3C KH AH 9C QC 4H 8C QH 5S";
    $deckP2 = explode(' ',$deckP2);

    $rez = battle($deckP1, $deckP2);
    if ($rez['win']) {
        echo $rez['win'] . ' ' . $rez['rounds'] . "\n";
    } else {
        echo "PAT\n";
    }


    # ================================================================================
    # =========== FUNCTIONS ==========================================================
    # --------------------------------------------------------------------------------

    function show_me($text, $deck) {
        $show = '';
        foreach($deck as $card){
            if(strlen($card) > 2) {
                $show .= $card.' ';
            } else {
                $show .= ' '.$card.' ';
            }
        }
        var_dump($text.$show);
    }

    function battle($deckP1, $deckP2)
    {
        $rounds = 0;

        while (count($deckP1) && count($deckP2)) {
            var_dump("<<<<< {$rounds} >>>>>>>>>>");
            show_me('1deck - ',$deckP1);
            show_me('2deck - ',$deckP2);
            $rounds++;
            $cardP1 = array_shift($deckP1);
            $cardP2 = array_shift($deckP2);
            var_dump("{$cardP1} ? {$cardP2}");

            $card1 = cardCost($cardP1);
            $card2 = cardCost($cardP2);

            if ($card1 > $card2) {
                var_dump("win 1");
                array_push($deckP1, $cardP1, $cardP2);

            } elseif ($card1 < $card2) {
                var_dump("win 2");
                array_push($deckP2, $cardP1, $cardP2);

            } else {
                $card3P1 = $card3P2 = [];
                var_dump('============ WAR ====================');
                $war = war($deckP1, $deckP2, $card3P1, $card3P2);
                //error_log(var_export($war, true));
                //error_log(var_export('========== END WAR ==================', true));

                if ($war['win'] == 1) {
                    var_dump("win 1");
                    array_push($deckP1, $cardP1, $cardP2);
                    $deckP1 = array_merge($deckP1, $war['deck']);

                } elseif ($war['win'] == 2) {
                    var_dump("win 2");
                    array_push($deckP2, $cardP1, $cardP2);
                    $deckP2 = array_merge($deckP2, $war['deck']);
                }
            }

            //error_log(var_export('================================', true));
        }
        //error_log(var_export('1deck - ' . implode(' ', $deckP1), true));
        //error_log(var_export('2deck - ' . implode(' ', $deckP2), true));
        if (count($deckP1) > count($deckP2)) {
            $player = 1;
        } else {
            $player = 2;
        }

        return array('win' => $player, 'rounds' => $rounds);
    }

    function war(&$deckP1, &$deckP2, $warCardP1, $warCardP2)
    {
        show_me('1deck - ',$deckP1);
        show_me('2deck - ',$deckP2);
        for ($i = 0; $i < 4; $i++) {
            if (current($deckP1)) {
                $warCardP1[] = array_shift($deckP1);
            }
        }

        for ($i = 0; $i < 4; $i++) {
            if (current($deckP2)) {
                $warCardP2[] = array_shift($deckP2);
            }
        }

        $lastCard1 = end($warCardP1);
        $lastCard2 = end($warCardP2);

        $card1 = cardCost($lastCard1);
        $card2 = cardCost($lastCard2);

        //error_log(var_export($lastCard1.''.$card1, true));
        //error_log(var_export($lastCard2.''.$card2, true));

        if ($card1 > $card2) {
            $warCardP1 = array_merge($warCardP1, $warCardP2);

            return array('win' => 1, 'deck' => $warCardP1);

        } elseif ($card1 < $card2) {
            $warCardP2 = array_merge($warCardP2, $warCardP1);

            return array('win' => 2, 'deck' => $warCardP2);
        }

        var_dump("{$lastCard1} ? {$lastCard2}");
        war($deckP1, $deckP2, $warCardP1, $warCardP2);
    }

    function cardCost($card)
    {
        if ($card) {
            $cardCost = array(2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 'J' => 11, 'Q' => 12, 'K' => 13, 'A' => 14);
            $replace = array('D', 'H', 'C', 'S');
            $clearCard = str_replace($replace, '', $card);

            return $cardCost[$clearCard];
        } else {
            return 0;
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    //echo("PAT\n");
