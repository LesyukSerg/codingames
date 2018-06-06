<?
    function get_distance($one, $two)
    {
        $d = sqrt(pow($one['x'] - $two['x'], 2) + pow($one['y'] - $two['y'], 2));

        return round($d, 2);
    }

    function minDist($item, $units)
    {
        $distances = [];

        foreach ($units as $id => $one) {
            $d = get_distance($one, $item);
            $distances[$d] = $id;
        }
        ksort($distances);
        $closestOne = current($distances);
        $distance = array_search($closestOne, $distances);

        return ['id' => $closestOne, 'dist' => $distance];
    }

    #==========================

    $sites = [];
    fscanf(STDIN, "%d", $numSites);
    for ($i = 0; $i < $numSites; $i++) {
        fscanf(STDIN, "%d %d %d %d",
            $siteId,
            $x,
            $posY,
            $radius
        );
        $sites[$siteId] = [
            'siteID' => $siteId,
            'x' => $x,
            'y' => $posY,
            'radius' => $radius
        ];
    }

    $try = $myB = '';
    $cnt
    // game loop
    while (true) {
        fscanf(STDIN, "%d %d", $gold, $touchedSite); // -1 if none
        $mySitesDist = $freeSitesDist = $enemyUnits = $myUnits = $enemySites = $freeSites = $mySites = [];
        $alignS = ['-1' => 'FREE', '0' => 'MINE', '1' => 'TOWER', '2' => 'BARRACK'];
        for ($i = 0; $i < $numSites; $i++) {
            fscanf(STDIN, "%d %d %d %d %d %d %d",
                $siteId,
                $ignore1, // used in future leagues
                $ignore2, // used in future leagues
                $structureType, // -1 = No structure, 1 = Tower 2 = Barracks 0 - MINE
                $owner, // -1 = No structure, 0 = Friendly, 1 = Enemy
                $param1,
                $param2
            );

            $sites[$siteId]['structureType'] = $structureType;
            $sites[$siteId]['owner'] = $owner;
            $sites[$siteId]['level'] = $param1;
            $sites[$siteId]['range'] = $param2;
            $type = $alignS[$structureType];

            if ($owner === 0) {
                $mySites[$type][$siteId] = $sites[$siteId];
            } elseif ($owner === 1) {
                $enemySites[$type][$siteId] = $sites[$siteId];
            } else {
                $freeSites[$siteId] = $sites[$siteId];
            }
        }

        fscanf(STDIN, "%d", $numUnits);
        $align = ['-1' => 'QUEEN', '0' => 'KNIGHT', '1' => 'ARCHER'];

        for ($i = 0; $i < $numUnits; $i++) {
            fscanf(STDIN, "%d %d %d %d %d",
                $x,
                $posY,
                $owner,
                $unitType, // -1 = QUEEN, 0 = KNIGHT, 1 = ARCHER
                $health
            );
            //error_log(var_export("$x $y", true));
            $one = ['x' => $x, 'y' => $posY];

            if ($owner === 0) {
                $myUnits[$align[$unitType]][] = $one;
            } else {
                $enemyUnits[$align[$unitType]][] = $one;
            }
        }

        //error_log(var_export($myUnits, true));
        //die;


        if (!count($mySites)) {
            $closestFree = minDist($myUnits['QUEEN'][0], $freeSites);

            echo "BUILD {$closestFree['id']} BARRACKS-KNIGHT";
            $myB = $closestFree['id'];
        } else {
            $closestFree = minDist($mySites['BARRACK'][$myB], $freeSites);
            //$closestFree = minDist($myUnits['QUEEN'][0], $freeSites);

            if (count($mySites['MINE'])) {

            }



            if (count($freeSites) > 7) {
                if (count($enemyUnits) > 1) {
                    $closestEnemy = minDist($myUnits['QUEEN'][0], $enemyUnits['KNIGHT']);
                    $closestEnemySite = minDist($myUnits['QUEEN'][0], $enemySites);
                    $enemySite = $enemySites[$closestEnemySite['id']];

                    if ($closestEnemy['dist'] < 600 || $closestEnemySite['dist'] < $enemySite['range']) {
                        echo "BUILD {$closestFree['id']} TOWER";
                    } else {
                        echo "BUILD {$closestFree['id']} MINE";
                    }
                } else {
                    echo "BUILD {$closestFree['id']} MINE";
                }

            } else {
                $dist = get_distance($myUnits['QUEEN'][0], $mySites[$myB]);

                if ($dist > 100) {
                    $b = $mySites[$myB];
                    echo "MOVE {$b['x']} {$b['y']}";
                } else {
                    echo "WAIT";
                }
            }
        }
        echo "\n";


        if (count($mySites)) {
            echo "TRAIN {$myB}";
//            foreach ($mySites as $id => $one) {
//                $dist = get_distance($one, $enemyUnits['QUEEN'][0]) - $one['radius'];
//                $mySitesDist[$dist] = $id;
//            }
//
//            if (count($mySitesDist)) {
//                ksort($mySitesDist);
//                //error_log(var_export($freeSitesDist, true));
//                $closestSiteID = current($mySitesDist);
//                //$dist = array_search($closestSiteID, $freeSitesDist);
//
//
//            } else {
//                echo "TRAIN";
//            }
        } else {
            echo "TRAIN";
        }
        echo "\n";
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));


        // First line: A valid queen action
        // Second line: A set of training instructions
        //echo "TRAIN\n";
    }
