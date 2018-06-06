<?php
    /**
     * Made with love by AntiSquid, Illedan and Wildum.
     * You can help children learn to code while you participate by donating to CoderDojo.
     **/

    fscanf(STDIN, "%d", $myTeam);
    fscanf(STDIN, "%d", $bushAndSpawnPointCount); // usefrul from wood1, represents the number of bushes and the number of places where neutral units can spawn

    for ($i = 0; $i < $bushAndSpawnPointCount; $i++) {
        fscanf(STDIN, "%s %d %d %d",
            $entityType, // BUSH, from wood1 it can also be SPAWN
            $posX,
            $posY,
            $radius
        );
    }

    fscanf(STDIN, "%d", $itemCount); // useful from wood2

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
    }

    echo("DEADPOOL\n");

    // game loop
    while (true) {
        fscanf(STDIN, "%d", $gold);
        fscanf(STDIN, "%d", $enemyGold);
        fscanf(STDIN, "%d", $roundType); // a positive value will show the number of heroes that await a command
        fscanf(STDIN, "%d", $entityCount);
        error_log(var_export($entityCount, true));

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

            if ($myTeam == $team) {

            } else {
                if ($isVisible) {
                    $enemies[$health] = [
                        'id'               => $unitId,
                        'team'             => $team,
                        'type'             => $unitType,
                        // UNIT, HERO, TOWER, can also be GROOT from wood1
                        'x'                => $posX,
                        'y'                => $posY,
                        'attackRange'      => $attackRange,
                        'health'           => $health,
                        'maxHealth'        => $maxHealth,
                        'shield'           => $shield,
                        // useful in bronze
                        'attackDamage'     => $attackDamage,
                        'movementSpeed'    => $movementSpeed,
                        'stunDuration'     => $stunDuration,
                        // useful in bronze
                        'goldValue'        => $goldValue,
                        'countDown1'       => $countDown1,
                        // all countDown and mana variables are useful starting in bronze
                        'countDown2'       => $countDown2,
                        'countDown3'       => $countDown3,
                        'mana'             => $mana,
                        'maxMana'          => $maxMana,
                        'manaRegeneration' => $manaRegeneration,
                        'heroType'         => $heroType,
                        // DEADPOOL, VALKYRIE, DOCTOR_STRANGE, HULK, IRONMAN
                        'isVisible'        => $isVisible,
                        // 0 if it isn't
                        'itemsOwned'       => $itemsOwned
                        // useful from wood1

                    ];
                }


                if ($unitType == 'HERO') {

                }
            }
        }

        ksort($enemies);
        $enemy = current($enemies);

        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));


        // If roundType has a negative value then you need to output a Hero name, such as "DEADPOOL" or "VALKYRIE".
        // Else you need to output roundType number of any valid action, such as "WAIT" or "ATTACK unitId"
        if ($enemies) {
            echo "ATTACK {$enemy['id']}\n";
        } else {

        }
    }