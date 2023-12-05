<?php
    fscanf(STDIN, "%d", $factoryCount);// the number of factories
    fscanf(STDIN, "%d", $linkCount); // the number of links between factories

    $distances = [];
    for ($i = 0; $i < $linkCount; $i++) {
        fscanf(STDIN, "%d %d %d", $fac1, $fac2, $distance);
        $distances[$fac1 . '_' . $fac2] = $distance;
    }
    //error_log(var_export($distances, true));

    // game loop
    while (true) {
        fscanf(STDIN, "%d", $entityCount); // the number of entities (e.g. factories and troops)
        $MyFactories = [];
        $OtherFactories = [];
        $Troops = [];


        for ($i = 0; $i < $entityCount; $i++) {
            fscanf(STDIN, "%d %s %d %d %d %d %d",
                $entityId,
                $entityType,
                $my,
                $cyborgs,
                $arg3,
                $arg4,
                $arg5
            );

            if ($entityType == 'FACTORY') {
                $Fcatory = array(
                    'ID'      => $entityId,
                    'CYBORGS' => $cyborgs
                );

                if ($my == 1) {
                    $MyFactories[$cyborgs] = $Fcatory;
                } else {
                    $OtherFactories[$entityId] = $Fcatory;
                }
            }
        }

        krsort($MyFactories);
        $selected = current($MyFactories);


        do {
            $closest = [];
            foreach ($OtherFactories as $k => $FCR) {
                if (isset($distances[$selected['ID'] . '_' . $FCR['ID']])) {
                    $dist = $distances[$selected['ID'] . '_' . $FCR['ID']];
                } else {
                    $dist = $distances[$FCR['ID'] . '_' . $selected['ID']];
                }

                $closest[$FCR['ID']] = $dist;
            }

            asort($closest);
            //error_log(var_export($closest, true));
            $closest = array_search(current($closest), $closest);
            //error_log(var_export($OtherFactories, true));

            if ($closest !== false && $OtherFactories[$closest]['CYBORGS'] > $selected['CYBORGS'] && count($OtherFactories) > 1) {
                unset($OtherFactories[$closest]);
            } else {
                break;
            }
        } while (count($FCR));


        //error_log(var_export($closest, true));
        if ($closest !== false && $selected['CYBORGS'] > 3) {
            $selected['CYBORGS']--;

            echo "MOVE {$selected['ID']} {$closest} {$selected['CYBORGS']}\n";
        } else {
            echo "WAIT\n";
        }
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
        // Any valid action, such as "WAIT" or "MOVE source destination cyborgs"

    }
