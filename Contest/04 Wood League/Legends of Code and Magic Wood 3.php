<?
    $step = 'collect';
    $collect = 0;
    $myDeck = [];

    // game loop
    while (1) {
        if ($collect++ == 30 && $step == 'collect') $step = 'fight';
        $players = $myHand = [];
        $onBoard = ['my' => [], 'opponent' => []];

        for ($i = 0; $i < 2; $i++) {
            fscanf(STDIN, "%d %d %d %d",
                $playerHealth,
                $playerMana,
                $playerDeck,
                $playerRune
            );

            $players[] = [
                'playerHealth' => $playerHealth,
                'playerMana'   => $playerMana,
                'playerDeck'   => $playerDeck,
                'playerRune'   => $playerRune
            ];
        }

        fscanf(STDIN, "%d %d", $opponentHand, $opponentActions);
        for ($i = 0; $i < $opponentActions; $i++) {
            $cardNumberAndAction = stream_get_line(STDIN, 20 + 1, "\n");
        }

        fscanf(STDIN, "%d", $cardCount);

        for ($i = 0; $i < $cardCount; $i++) {
            fscanf(STDIN, "%d %d %d %d %d %d %d %s %d %d %d",
                $cardNumber,
                $instanceId,
                $location,
                $cardType,
                $cost,
                $attack,
                $defense,
                $abilities,
                $myHealthChange,
                $opponentHealthChange,
                $cardDraw
            );

            $card = [
                'i'                    => $i,
                'cardNumber'           => $cardNumber,
                'instanceId'           => $instanceId,
                'location'             => $location,
                'cardType'             => $cardType,
                'cost'                 => $cost,
                'attack'               => $attack,
                'defense'              => $defense,
                'abilities'            => $abilities,
                'myHealthChange'       => $myHealthChange,
                'opponentHealthChange' => $opponentHealthChange,
                'cardDraw'             => $cardDraw
            ];

//            if ($step == 'fight') {
//                error_log(var_export($card, true));
//            }

            if ($card['location'] == 1) {
                $onBoard['my'][] = $card;
            } elseif ($card['location'] == -1) {
                $onBoard['opponent'][] = $card;
            } else {
                $myHand[] = $card;
            }
        }
        #======================================================================

        if ($step == 'collect') {
            $card = chooseBestCardFrom3($myHand, $myDeck);
            $myDeck[$card['cost']][] = $card;
            echo "PICK {$card['i']}\n";
        } else {

            $actions = [];

            while ($players[0]['playerMana']) {
                if ($card = summonCard($players[0]['playerMana'], $myHand)) {
                    $actions[] = "SUMMON {$card['instanceId']}";
                    $players[0]['playerMana'] -= $card['cost'];
                    unset($myHand[$card['i']]);
                } else {
                    break;
                }
            }


            if ($guards = isGuardOnBoard($onBoard)) {
                error_log(var_export($guards, true));

                foreach ($guards as $g) {
                    foreach ($onBoard['my'] as $k => $one) {
                        if ($g['defense'] > 0) {
                            $actions[] = "ATTACK {$one['instanceId']} {$g['instanceId']}";
                            $g['defense'] -= $one['attack'];
                            unset($onBoard['my'][$k]);
                        }
                    }
                }
            }

            if (count($onBoard['my'])) {
                foreach ($onBoard['my'] as $one) {
                    $actions[] = "ATTACK {$one['instanceId']} -1";
                }
            }

            echo implode(';', $actions) . "\n";
        }
    }

    #==========================================================================
    #==========================================================================

    function summonCard($mana, $cards)
    {
        $ok = [];

        foreach ($cards as $one) {
            if ($one['cost'] <= $mana) {
                $ok[$one['attack']] = $one;
            }
        }

        if (count($ok)) {
            krsort($ok);
            return current($ok);
        }

        return 0;
    }


    function chooseBestCard($cards, $myDeck)
    {
        usort($cards, 'compareCards');

        foreach ($cards as $card) {
            if (!isset($myDeck[$card['cost']])) {
                if (!isset($myDeck[$card['cost']]) || ($card['cost'] < 6 && count($myDeck[$card['cost']]) < 6) || ($card['cost'] > 5 && count($myDeck[$card['cost']]) < 4))
                    return $card;
            }
        }

        return current($cards);
    }

    function compareCards($a, $b)
    {
        $oneK = ($a['attack'] + $a['defense']) / 2 - $a['cost'];
        $twoK = ($b['attack'] + $b['defense']) / 2 - $b['cost'];
        if ($oneK == $twoK) {
            if ($a['cost'] != $b['cost']) {
                return ($a['cost'] < $b['cost']) ? -1 : 1;
            } else {
                return 0;
            }
        }

        return ($oneK > $twoK) ? -1 : 1;
    }

    function isGuardOnBoard($onBoard)
    {
        $guards = [];

        if (count($onBoard['opponent'])) {
            foreach ($onBoard['opponent'] as $one) {
                if (strpos($one['abilities'], 'G') !== false) $guards[] = $one;
            }
        }

        return count($guards) ? $guards : 0;
    }
