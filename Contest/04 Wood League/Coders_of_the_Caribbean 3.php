<?php
    function get_distance($one, $two)
    {
        $d = sqrt(pow($one['x'] - $two['x'], 2) + pow($one['y'] - $two['y'], 2));

        return ceil($d);
    }

    function min_distance_to($el, $item)
    {
        $distances = [];

        foreach ($el as $k => $one) {
            $distances[$k] = get_distance($item, $one);
        }
        asort($distances);
        $closest = current($distances);
        $k = array_search($closest, $distances);

        return ['dist' => $closest, 'item' => $el[$k]];
    }

    while (true) {
        fscanf(STDIN, "%d", $myShipCount); // the number of remaining ships
        fscanf(STDIN, "%d", $entityCount);// the number of entities (e.g. ships, mines or cannonballs)

        $barrel = $enemy = $myShips = [];

        for ($i = 0; $i < $entityCount; $i++) {
            fscanf(STDIN, "%d %s %d %d %d %d %d %d",
                $entityId,
                $entityType,
                $x,
                $y,
                $arg1,
                $arg2,
                $arg3,
                $arg4
            );

            $entity = [
                'id'    => $entityId,
                'type'  => $entityType,
                'x'     => $x,
                'y'     => $y,
                'pos'   => $arg1,
                'speed' => $arg2,
                'rum'   => $arg3,
                'my'    => $arg4
            ];

            if ($entityType == 'SHIP') {
                if ($entity['my']) {
                    $myShips[] = $entity;
                } else {
                    $enemy[] = $entity;
                }
            } elseif ($entityType == 'BARREL') {
                $barrel[] = $entity;
            }

            //error_log(var_export($entity, true));
        }

        foreach ($myShips as $ship) {
            $closest = min_distance_to($barrel, $ship);
            // Write an action using echo(). DON'T FORGET THE TRAILING \n
            // To debug (equivalent to var_dump): error_log(var_export($var, true));

            echo "MOVE {$closest['item']['x']} {$closest['item']['y']}\n";
            // Any valid action, such as "WAIT" or "MOVE x y"
        }
    }