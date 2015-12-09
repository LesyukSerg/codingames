<?
    $REZ = $ZONES = [];
    fscanf(STDIN, "%d %d %d %d",
        $P, // number of players in the game (2 to 4 players)
        $ID, // ID of your player (0, 1, 2, or 3)
        $D, // number of drones in each team (3 to 11)
        $Z // number of zones on the map (4 to 8)
    );
    for ($i = 0; $i < $Z; $i++) {
        fscanf(STDIN, "%d %d",
            $X, // corresponds to the position of the center of a zone. A zone is a circle with a radius of 100 units.
            $Y
        );
        $ZONES[] = array('x' => $X, 'y' => $Y);
    }

// game loop
    $first = 1;

    while (true) {
        for ($i = 0; $i < $Z; $i++) {
            fscanf(STDIN, "%d",
                $TID // ID of the team controlling the zone (0, 1, 2, or 3) or -1 if it is not controlled. The zones are given in the same order as in the initialization.
            );
        }

        if ($first) {
            $ZONES_T = array();
            for ($i = 0; $i < $P; $i++) {
                if ($i != $ID) continue;

                for ($j = 0; $j < $D; $j++) {
                    fscanf(STDIN, "%d %d",
                        $DX, // The first D lines contain the coordinates of drones of a player with the ID 0, the following D lines those of the drones of player 1, and thus it continues until the last player.
                        $DY
                    );

                    if (!count($ZONES_T)) {
                        $ZONES_T = $ZONES;
                    }

                    $DISTANCES = array();
                    foreach ($ZONES_T as $N => $Z) {
                        $k = sqrt(pow($Z['x'] - $DX, 2) + pow($Z['y'] - $DY, 2));
                        $DISTANCES[$k] = $N;
                    }
                    ksort($DISTANCES);
                    $Z = current($DISTANCES);

                    $REZ[] = array('x' => $ZONES[$Z]['x'], 'y' => $ZONES[$Z]['y']);

                    unset($ZONES_T[$Z]);
                    $first = 0;
                }
            }
        }

        foreach ($REZ as $E) {
            echo($E['x'] . ' ' . $E['y'] . "\n"); // output a destination point to be reached by one of your drones. The first line corresponds to the first of your drones that you were provided as input, the next to the second, etc.
        }
    }

    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));
