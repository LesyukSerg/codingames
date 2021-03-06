<?
    function get_distance($one, $two)
    {
        $d = sqrt(pow($one['x'] - $two['x'], 2) + pow($one['y'] - $two['y'], 2));

        return ceil($d);
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

    $myB = '';
    // game loop
    while (true) {
        fscanf(STDIN, "%d %d", $gold, $touchedSite); // -1 if none
        $mySitesDist = $freeSitesDist = $enemyUnits = $myUnits = $freeSites = $mySites = [];

        for ($i = 0; $i < $numSites; $i++) {
            fscanf(STDIN, "%d %d %d %d %d %d %d",
                $siteId,
                $ignore1, // used in future leagues
                $ignore2, // used in future leagues
                $structureType, // -1 = No structure, 1 = Tower 2 = Barracks
                $owner, // -1 = No structure, 0 = Friendly, 1 = Enemy
                $param1,
                $param2
            );

            $sites[$siteId]['structureType'] = $structureType;
            $sites[$siteId]['owner'] = $owner;

            if ($owner === 0) {
                $mySites[$siteId] = $sites[$siteId];
            } elseif ($owner === -1) {
                $freeSites[$siteId] = $sites[$siteId];
            }
        }

        fscanf(STDIN, "%d", $numUnits);

        for ($i = 0; $i < $numUnits; $i++) {
            fscanf(STDIN, "%d %d %d %d %d",
                $x,
                $posY,
                $owner,
                $unitType, // -1 = QUEEN, 0 = KNIGHT, 1 = ARCHER
                $health
            );
            //error_log(var_export("$x $y", true));
            $align = ['-1' => 'QUEEN', '0' => 'KNIGHT', '1' => 'ARCHER'];
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
            foreach ($freeSites as $id => $one) {
                $dist = get_distance($one, $myUnits['QUEEN'][0]) - $one['radius'];
                $freeSitesDist[$dist] = $id;
            }

            ksort($freeSitesDist);
            $closestSiteID = current($freeSitesDist);
            $dist = array_search($closestSiteID, $freeSitesDist);
            error_log(var_export($closestSiteID, true));
            echo "BUILD {$closestSiteID} BARRACKS-KNIGHT";
            $myB = $closestSiteID;
        } else {
            error_log(var_export($closestSiteID, true));
            if (count($freeSites)) {
                foreach ($freeSites as $id => $one) {
                    $dist = get_distance($one, $mySites[$myB]) - $one['radius'];
                    $freeSitesDist[$dist] = $id;
                }
                ksort($freeSitesDist);
                $closestSiteID = current($freeSitesDist);
                echo "BUILD {$closestSiteID} TOWER";
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
