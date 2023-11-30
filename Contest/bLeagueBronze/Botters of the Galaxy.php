<?php
    function fillItems(&$items, $item)
    {
        foreach ($item as $type => $val) {
            if ($val) {
                if (isset($items[$type][$val])) {
                    if ($items[$type][$val][$type] < $val) {
                        $items[$type][$val] = $item;
                    }
                } else {
                    $items[$type][$val] = $item;
                }
            }
        }
        unset($items["name"]);
    }

    function getDistance($one, $two)
    {
        $d = sqrt(pow($one['x'] - $two['x'], 2) + pow($one['y'] - $two['y'], 2));

        return ceil($d);
    }

    function unitsDist($units, $eTower)
    {
        if (count($units)) {
            $distances = [];
            foreach ($units as $one) {
                $distances[$one["id"]] = getDistance($one, $eTower);
            }
            asort($distances);
            //error_log(var_export($distances, true));
            $closest = current($distances);
            $id = array_search($closest, $distances);

            return $closest;
        }
    }

    function buyItem(&$items, $gold)
    {
        if (count($items)) {
            krsort($items);
            //error_log(var_export($gold, true));
            //error_log(var_export($items, true));

            foreach ($items as $k => $one) {
                if ($one['cost'] < $gold) {
                    //error_log(var_export($one, true));
                    unset($items[$k]);
                    return $one['name'];
                }
            }
        }

    }


    /**
     * Made with love by AntiSquid, Illedan and Wildum.
     * You can help children learn to code while you participate by donating to CoderDojo.
     **/

    fscanf(STDIN, "%d", $myTeam);
    fscanf(STDIN, "%d", $bushAndSpawnPointCount); // usefrul from wood1, represents the number of bushes and the number of places where neutral units can spawn

    $base = [];

    for ($i = 0; $i < $bushAndSpawnPointCount; $i++) {
        fscanf(STDIN, "%s %d %d %d",
            $entityType, // BUSH, from wood1 it can also be SPAWN
            $posX,
            $posY,
            $radius
        );

        $point = [
            "type"   => $entityType, // BUSH, from wood1 it can also be SPAWN
            "x"      => $posX,
            "y"      => $posY,
            "radius" => $radius
        ];
    }

    fscanf(STDIN, "%d", $itemCount); // useful from wood2

    $items = [];

    for ($i = 0; $i < $itemCount; $i++) {
        fscanf(STDIN, "%s %d %d %d %d %d %d %d %d %d",
            $itemName,
            // contains keywords such as BRONZE, SILVER and BLADE, BOOTS connected by "_" to help you sort easier
            $itemCost, // BRONZE items have lowest cost, the most expensive items are LEGENDARY
            $damage, // keyword BLADE is present if the most important item stat is damage
            $health,
            $maxHealth,
            $mana,
            $maxMana,
            $moveSpeed, // keyword BOOTS is present if the most important item stat is moveSpeed
            $manaRegeneration,
            $isPotion // 0 if it's not instantly consumed
        );

        $item = [
            "name"      => $itemName,
            // contains keywords such as BRONZE, SILVER and BLADE, BOOTS connected by "_" to help you sort easier
            "cost"      => $itemCost, // BRONZE items have lowest cost, the most expensive items are LEGENDARY
            "damage"    => $damage, // keyword BLADE is present if the most important item stat is damage
            "health"    => $health,
            "maxHealth" => $maxHealth,
            "mana"      => $mana,
            "maxMana"   => $maxMana,
            "moveSpeed" => $moveSpeed, // keyword BOOTS is present if the most important item stat is moveSpeed
            "regen"     => $manaRegeneration,
            "isPotion"  => $isPotion // 0 if it's not
        ];

        fillItems($items, $item);
    }

    echo "DEADPOOL\n";
    echo "HULK\n";
    $turn = 0;

    $special = [0 => ["COUNTER", "WIRE", "STEALTH"], 1 => ["CHARGE", "EXPLOSIVESHIELD", "BASH"]];

    // game loop
    while (true) {
        $turn++;
        $itemName = '';
        $my = $enemies = [];
        fscanf(STDIN, "%d", $gold);
        fscanf(STDIN, "%d", $enemyGold);
        fscanf(STDIN, "%d", $roundType); // a positive value will show the number of heroes that await a command
        fscanf(STDIN, "%d", $entityCount);

        for ($i = 0; $i < $entityCount; $i++) {
            fscanf(STDIN, "%d %d %s %d %d %d %d %d %d %d %d %d %d %d %d %d %d %d %d %s %d %d",
                $unitId,
                $team,
                $unitType, // UNIT, HERO, TOWER, can also be GROOT from wood1
                $posX,
                $posY,
                $attackRange,
                $health,
                $maxHealth,
                $shield, // useful in bronze
                $attackDamage,
                $movementSpeed,
                $stunDuration, // useful in bronze
                $goldValue,
                $countDown1, // all countDown and mana variables are useful starting in bronze
                $countDown2,
                $countDown3,
                $mana,
                $maxMana,
                $manaRegeneration,
                $heroType, // DEADPOOL, VALKYRIE, DOCTOR_STRANGE, HULK, IRONMAN
                $isVisible, // 0 if it isn't
                $itemsOwned // useful from wood1
            );

            $unit = [
                'id'               => $unitId,
                'team'             => $team,
                'type'             => $unitType,         // UNIT, HERO, TOWER, can also be GROOT from wood1
                'x'                => $posX,
                'y'                => $posY,
                'attackRange'      => $attackRange,
                'health'           => $health,
                'maxHealth'        => $maxHealth,
                'shield'           => $shield,           // useful in bronze
                'attackDamage'     => $attackDamage,
                'movementSpeed'    => $movementSpeed,
                'stunDuration'     => $stunDuration,     // useful in bronze
                'goldValue'        => $goldValue,
                'countDown1'       => $countDown1,       // all countDown and mana variables are useful starting in bronze
                'countDown2'       => $countDown2,
                'countDown3'       => $countDown3,
                'mana'             => $mana,
                'maxMana'          => $maxMana,
                'manaRegeneration' => $manaRegeneration,
                'heroType'         => $heroType,         // DEADPOOL, VALKYRIE, DOCTOR_STRANGE, HULK, IRONMAN
                'isVisible'        => $isVisible,        // 0 if it isn't
                'itemsOwned'       => $itemsOwned        // useful from wood1
            ];

            if ($myTeam == $team) {
                if ($unitType == "TOWER") {
                    $my["TOWER"] = $unit;
                } elseif ($unitType == "HERO") {
                    $my["HERO"][] = $unit;
                } else {
                    //error_log(var_export($health, true));
                    $my["UNITS"][] = $unit;
                }

            } else {
                if ($isVisible) {
                    if ($unitType == "TOWER") {
                        $eTower = $unit;
                    } else {
                        $enemies[] = $unit;
                    }
                }

                //if ($unitType == 'HERO') {

                //}
            }
        }

        if ($roundType > 0) {
            $myUnitsDist = unitsDist($my["UNITS"], $eTower);

            foreach ($my["HERO"] as $k => $hero) {
                $myDist = getDistance($hero, $eTower);

                if ($k) {
                    $cDown = 2;
                    $spec = $special[$k][1];
                } else {
                    $cDown = 1;
                    $spec = $special[$k][0];
                }

                $myDistToEnemy = unitsDist($enemies, $hero);

                if ($myDistToEnemy < 400 && $hero['mana'] > 40 && $hero['countDown' . $cDown] == 0) {
                    echo "$spec\n";

                } elseif ($myDist > $myUnitsDist && $myDist > 600) {
                    if ($hero["health"] < $hero["maxHealth"] / 2) {
                        $itemName = buyItem($items["health"], $gold);
                    }

                    //error_log(var_export($items["moveSpeed"], true));

                    // if (!$itemName) {
                    //    $itemName = buyItem($items["attackRange"], $gold);
                    // }

                    //if (!$itemName) {
                    //    $itemName = buyItem($items["damage"], $gold);
                    //}

                    if (!$itemName) {
                        $itemName = buyItem($items["moveSpeed"], $gold);
                    }

                    if ($itemName) {
                        echo "BUY $itemName\n";
                    } else {
                        echo "ATTACK_NEAREST UNIT\n";
                    }

                } else {
                    echo "MOVE {$my["TOWER"]["x"]} {$my["TOWER"]["y"]}\n";
                }
            }
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
