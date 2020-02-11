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
                'cardDraw'             => $cardDraw,
            ];

//            if ($step == 'fight') {
//                error_log(var_export($card, true));
//            }

            if ($card['location'] == 1) {
                $card['active'] = 1;
                $onBoard['my'][$i] = $card;
            } elseif ($card['location'] == -1) {
                $onBoard['opponent'][$i] = $card;
                // error_log(var_export($onBoard['opponent'], true));
                // error_log(var_export(__LINE__, true));
            } else {
                $myHand[$i] = $card;
            }
        }
        #======================================================================

        if ($step == 'collect') {
            $card = chooseBestCardFrom3($myHand, $myDeck);
            $myDeck[] = $card;
            echo "PICK {$card['i']}\n";
        } else {
            $actions = [];

            // --- SUMMON CARDS ---
            while (1) {
                $guards = isGuardOnBoard($onBoard['opponent']);
                //error_log(var_export($onBoard['my'], true));
                //error_log(var_export(__LINE__, true));

                if (!$guards && checkLastAttackCharge($onBoard['my'], $myHand, $players[1]['playerHealth'], $players[0]['playerMana'])) {
                    if ($card = summoncardWithAbility($players[0]['playerMana'], $myHand, 'C')) {
                        $card['active'] = hasAbility($card, 'C');
                        $actions[] = "SUMMON {$card['instanceId']}" . ' l ' . __LINE__;
                        $players[0]['playerMana'] -= $card['cost'];
                        unset($myHand[$card['i']]);
                        $onBoard['my'][$card['i']] = $card;
                    } else {
                        break;
                    }

                } elseif ($card = summonCard($players[0]['playerMana'], $myHand)) {
                    if ($card['cardType'] == 1) { //1: Green item
                        if (count($onBoard['my'])) {
                            if ($myGuards = isGuardOnBoard($onBoard['my'])) {
                                $strongest = current($myGuards);

                                if (!hasAbility($card, 'G')) {
                                    $actions[] = "USE {$card['instanceId']} {$strongest['instanceId']}" . ' l ' . __LINE__;
                                    $players[0]['playerMana'] -= $card['cost'];

                                    addAbilities($onBoard['my'][$strongest['i']], $card);
                                }

                                unset($myHand[$card['i']]);
                            } else {
                                usortK($onBoard['my'], 'maxAttack');
                                $strongest = current($onBoard['my']);

                                $actions[] = "USE {$card['instanceId']} {$strongest['instanceId']}" . ' l ' . __LINE__;
                                $players[0]['playerMana'] -= $card['cost'];

                                addAbilities($onBoard['my'][$strongest['i']], $card);
                                unset($myHand[$card['i']]);
                            }

                        } else {
                            unset($myHand[$card['i']]);
                            continue;
                        }
                    } elseif ($card['cardType'] == 2) { //2: Red item
                        if (count($onBoard['opponent'])) {
                            $guards = isGuardOnBoard($onBoard['opponent']);
                            usortK($guards, 'maxDefence');

                            if ($guards) {
                                $strongest = current($guards);
                                $actions[] = "USE {$card['instanceId']} {$strongest['instanceId']}" . ' l ' . __LINE__;
                                $players[0]['playerMana'] -= $card['cost'];

//                                error_log(var_export($strongest, true));
//                                error_log(var_export('---', true));
//                                error_log(var_export($card, true));

                                deleteAbilities($onBoard['opponent'][$strongest['i']], $card);

                                unset($myHand[$card['i']]);
                            } else {
                                if (hasAbility($card, 'B') && $enemyB = cardWithAbilityExist($onBoard['opponent'], 'B')) {
                                    usortK($enemyB, 'maxAttack');
                                    $strongest = current($enemyB);
                                    $actions[] = "USE {$card['instanceId']} {$strongest['instanceId']}" . ' l ' . __LINE__;

                                } elseif (!hasAbility($card, 'G')) {

                                    usortK($onBoard['my'], 'maxAttack');
                                    $strongest = current($onBoard['opponent']);
                                    $actions[] = "USE {$card['instanceId']} {$strongest['instanceId']}" . ' l ' . __LINE__;
                                }

                                $players[0]['playerMana'] -= $card['cost'];
                                unset($myHand[$card['i']]);
                            }
                        } else {
                            unset($myHand[$card['i']]);
                            continue;
                        }
                    } elseif ($card['cardType'] == 3) { //3: Blue item
                        $actions[] = "USE {$card['instanceId']} -1" . ' l ' . __LINE__;
                        $players[0]['playerMana'] -= $card['cost'];
                        unset($myHand[$card['i']]);
                    } else {
                        $card['active'] = hasAbility($card, 'C');
                        $actions[] = "SUMMON {$card['instanceId']}" . ' l ' . __LINE__;
                        $players[0]['playerMana'] -= $card['cost'];
                        unset($myHand[$card['i']]);
                        $onBoard['my'][$card['i']] = $card;
                    }
                } else {
                    break;
                }
            }

            // error_log(var_export($onBoard['my'], true));
            // error_log(var_export(__LINE__, true));

            // -- PLAY CARDS ---
            if ($guards) {
                $guards = isGuardOnBoard($onBoard['opponent']);
                $myGuards = isGuardOnBoard($onBoard['my']);
                usortK($guards, 'maxDefence');
                usortK($onBoard['my'], 'maxAttack');

                if ($myGuards) {
                    if (canIBeatCard($guards, $onBoard['my'])) {
                        attackEnemyCards($guards, $onBoard, $actions);
                    }
                } else {
                    attackEnemyCards($guards, $onBoard, $actions);
                }
            }

            if ($enemyB = cardWithAbilityExist($onBoard['opponent'], 'B')) {
//                error_log(var_export($enemyB, true));
//                error_log(var_export(__LINE__, true));

                if (canIBeatCard($enemyB, $onBoard['my'])) {
                    attackEnemyCards($enemyB, $onBoard, $actions, 1);
                }
            }

            // error_log(var_export($onBoard['my'], true));
            // error_log(var_export(__LINE__, true));
//            error_log(var_export($onBoard['my'], true));

            if (count($onBoard['my'])) {
                if (!checkLastAttack($onBoard['my'], $players[1]['playerHealth'])) {
                    $myGuards = isGuardOnBoard($onBoard['my']);
                    usortK($onBoard['opponent'], 'maxAttack');
                    usortK($onBoard['my'], 'maxAttack');
                    $strongest = current($onBoard['opponent']);

                    if (count($onBoard['opponent']) > 3 || ($strongest['attack'] > 5 && (!$myGuards || $players[0]['playerHealth'] < 10))) {
                        attackEnemyCards($onBoard['opponent'], $onBoard, $actions);
                    }
                }
            }

            if (count($onBoard['my'])) {
                foreach ($onBoard['my'] as $one) {
                    if ($one['active']) {
                        $actions[] = "ATTACK {$one['instanceId']} -1" . ' l ' . __LINE__;
                    }
                }
            }

            echo implode(';', $actions) . "\n";
        }
    }

    #==========================================================================
    #==========================================================================

    function summonCard($manna, $cards)
    {
        $ok = [];

        foreach ($cards as $one) {
            if ($one['cost'] <= $manna) {
                $ok[] = $one;
            }
        }

//        error_log(var_export($ok, true));
//        error_log(var_export(__LINE__, true));

        if (count($ok)) {
            usortK($ok, 'handChoose');

            return current($ok);
        }

        return 0;
    }

    function summonCardWithAbility($manna, $cards, $ability)
    {
        $ok = [];

        foreach ($cards as $one) {
            if ($one['cost'] <= $manna && hasAbility($one, $ability)) {
                $ok[] = $one;
            }
        }

        if (count($ok)) {
            usortK($ok, 'handChoose');

            return current($ok);
        }

        return 0;
    }

    function chooseBestCardFrom3($cards, $myDeck)
    {
        usort($cards, 'compareCards');
        $cardsCountType = [0 => 0, 1 => 0, 2 => 0, 3 => 0];
        $cardsCountCost = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];

        if (count($myDeck)) {
            foreach ($myDeck as $one) {
                $cardsCountType[$one['cardType']]++;
                $cardsCountCost[$one['cost']]++;
            }
        }

        $typeLimit = [
            0 => 99,
            1 => 3, // Green
            2 => 9, // Red
            3 => 9 // Blue
        ];

        $costLimit = [
            0  => 5,
            1  => 3,
            2  => 3,
            3  => 3,
            4  => 6,
            5  => 6,
            6  => 6,
            7  => 9,
            8  => 9,
            9  => 9,
            10 => 9,
            11 => 9,
            12 => 9,
        ];

        foreach ($cards as $card) {
            if (!isset($cardsCountCost[$card['cost']])) return $card;
            elseif ($cardsCountType[$card['cardType']] < $typeLimit[$card['cardType']]) {
                if ($cardsCountCost[$card['cost']] <= $costLimit[$card['cost']])
                    return $card;
            }
        }

        foreach ($cards as $card) {
            if ($cardsCountType[$card['cardType']] < $typeLimit[$card['cardType']]) {
                return $card;
            }
        }

        return current($cards);
    }

    function cardWithAbilityExist($cards, $ability)
    {
        $exist = [];
        foreach ($cards as $card) {
            if ($card['defense'] > 0 && hasAbility($card, $ability)) {
                $exist[$card['i']] = $card;
            }
        }

        return count($exist) ? $exist : 0;
    }

    function hasAbility($card, $ability)
    {
        return (strpos($card['abilities'], $ability) !== false) ? 1 : 0;
    }

    function isGuardOnBoard($cards)
    {
        $guards = [];

        if (count($cards)) {
            foreach ($cards as $one) {
                if ($one['defense'] > 0 && hasAbility($one, 'G')) $guards[] = $one;
            }
        }

        return count($guards) ? $guards : 0;
    }

    function deleteAbilities(&$opponent, $card)
    {
        for ($i = 0; $i < 6; $i++) {
            if ($card['abilities'][$i] != '-') {
                $opponent['abilities'] = str_replace($card['abilities'][$i], '-', $opponent['abilities']);
            }
        }

        $opponent['attack'] += $card['attack'];
        $opponent['defense'] += $card['defense'];
    }

    function addAbilities(&$creature, $card)
    {
        // error_log(var_export($creature, true));
        // error_log(var_export($card, true));
        // error_log(var_export(__LINE__, true));

        for ($i = 0; $i < 6; $i++) {
            if ($card['abilities'][$i] != '-') {
                $creature['abilities'][$i] = $card['abilities'][$i];
            }
        }

        $creature['attack'] += $card['attack'];
        $creature['defense'] += $card['defense'];
    }

    function canIBeatCard($guards, $cards)
    {
        $firstG = current($guards);
        $attackSum = 0;
        foreach ($cards as $one) {
            if ($one['active']) $attackSum += $one['attack'];
        }

        return $attackSum > $firstG['defense'] ? 1 : 0;
    }

    function checkLastAttack($myCards, $opHealth)
    {
        $totalDamage = 0;
        foreach ($myCards as $one) {
            if ($one['active']) $totalDamage += $one['attack'];
        }

        return $totalDamage >= $opHealth ? 1 : 0;
    }

    function checkLastAttackCharge($myCards, $myHand, $opHealth, $manna)
    {
        $totalDamage = 0;
        foreach ($myCards as $one) {
            if ($one['active']) $totalDamage += $one['attack'];
        }

        foreach ($myHand as $one) {
            if (hasAbility($one, 'C') && $one['cost'] <= $manna) $totalDamage += $one['attack'];
        }

        return $totalDamage >= $opHealth ? 1 : 0;
    }

    function attackEnemyCards($enemyCards, &$onBoard, &$actions, $force = false)
    {
        if (is_array($enemyCards)) {
//            if ($force) {
//                error_log(var_export($enemyCards, true));
//                error_log(var_export(__LINE__, true));
//            }

            foreach ($enemyCards as $enemy) {
                foreach ($onBoard['my'] as $k => $one) {
                    if ($one['active'] && $one['attack'] > 0 && $enemy['defense'] > 0) {
                        if (!hasAbility($one, 'G') || $one['defense'] > $enemy['attack'] || $force) {
                            $actions[] = "ATTACK {$one['instanceId']} {$enemy['instanceId']}" . ' l ' . __LINE__;

//                            error_log(var_export($enemy, true));
//                            error_log(var_export(__LINE__, true));

                            if (hasAbility($enemy, 'W')) {
                                $enemy['ability'] = str_replace('W', '-', $enemy['ability']);
                                $onBoard['opponent'][$enemy['i']]['ability'] = str_replace('W', '-', $onBoard['opponent'][$enemy['i']]['ability']);
                            } else {
                                $enemy['defense'] -= $one['attack'];
                                $onBoard['opponent'][$enemy['i']]['defense'] -= $one['attack'];
                            }

                            unset($onBoard['my'][$k]);

                            if ($enemy['defense'] <= 0) {
                                unset($onBoard['opponent'][$enemy['i']]);
                            }
                        }
                    }
                }
            }
        }
    }

    # --- Usort ----

    function usortK(&$array, $funcName)
    {
//        error_log(var_export($array, true));
        if (is_array($array)) {
            usort($array, $funcName);

            $k = [];

            foreach ($array as $item) {
                $k[$item['i']] = $item;
            }

            $array = $k;
        }
    }

    function getCoefficient($one)
    {
        if ($one['cardType'] == 0) {
            return (($one['attack'] + $one['defense'] + $one['myHealthChange'] + (0 - $one['opponentHealthChange'])) / 2 - $one['cost']) + $one['cardDraw'] * 2;
        } else {
            return (($one['attack'] + $one['defense'] + $one['myHealthChange'] + (0 - $one['opponentHealthChange'])) / 2 - $one['cost']) + $one['cardDraw'] * 2;
        }
    }

    function calculateCardCoefficient($card)
    {
        global $onBoard;

        $attack = $card['attack'];
        if ($card['cardType'] == 0) {
            $attack += 10;
            if (hasAbility($card, 'G') && !cardWithAbilityExist($onBoard['my'], 'G')) $attack += 50;
        } elseif ($card['cardType'] == 1) {
        } elseif ($card['cardType'] == 2) {
            if (cardWithAbilityExist($onBoard['opponent'], 'G')) {
                if (hasAbility($card, 'G')) $attack += 50;
            }
        }

        return $attack;
    }

    function handChoose($a, $b)
    {
        $attackA = calculateCardCoefficient($a);
        $attackB = calculateCardCoefficient($b);

        if ($attackA == $attackB) return 0;

        return ($attackA > $attackB) ? -1 : 1;
    }

    function compareCards($a, $b)
    {
        $abilities = [
            'B' => 3,
            'C' => 2,
            'D' => 2,
            'G' => 2,
            'L' => 3,
            'W' => 2,
        ];

        $oneK = getCoefficient($a);
        $twoK = getCoefficient($b);

        foreach ($abilities as $ability => $value) {
            if (hasAbility($a, $ability)) $oneK += $value;
            if (hasAbility($b, $ability)) $twoK += $value;
        }

        if ($oneK == $twoK) {
            if ($a['cost'] != $b['cost']) {
                return ($a['cost'] < $b['cost']) ? -1 : 1;
            } else {
                return 0;
            }
        }

        return ($oneK > $twoK) ? -1 : 1;
    }

    function maxAttack($a, $b)
    {
        if ($a['attack'] == $b['attack']) {
            return ($a['defense'] < $b['defense']) ? -1 : 1;
        }

        return ($a['attack'] > $b['attack']) ? -1 : 1;
    }

    function maxDefence($a, $b)
    {
        if ($a['defense'] == $b['defense']) {
            return ($a['attack'] < $b['attack']) ? -1 : 1;
        }

        return ($a['defense'] > $b['defense']) ? -1 : 1;
    }