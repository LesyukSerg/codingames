<?php
    $MAP = [];

    fscanf(STDIN, "%d %d %d %d",
        $sX, // x coordinate of your start position
        $sY, // y coordinate of your start position
        $eX, // x coordinate of the exit
        $eY // y coordinate of the exit
    );
    fscanf(STDIN, "%d", $nbObstacles);  // number of walls
    $MAP[$sX][$sY] = 'S';
    $MAP[$eX][$eY] = 'X';

    for ($i = 0; $i < $nbObstacles; $i++) {
        fscanf(STDIN, "%d %d",
            $pX, // x coordinate of a wall
            $pY // y coordinate of a wall
        );
        $MAP[$pX][$pY] = '#';
    }

    findPath($MAP, $eX, $eY, 0);
    showMAP($MAP, 16, 10);

    $MAX = array(
        isset($MAP[$sX][$sY - 1]) ? $MAP[$sX][$sY - 1] : 0,
        isset($MAP[$sX][$sY + 1]) ? $MAP[$sX][$sY + 1] : 0,
        isset($MAP[$sX - 1][$sY]) ? $MAP[$sX - 1][$sY] : 0,
        isset($MAP[$sX + 1][$sY]) ? $MAP[$sX + 1][$sY] : 0,
    );
    $MAP[$sX][$sY] = max($MAX) + 10;
    $MAP[$eX][$eY] = '0';

    showMAP($MAP, 16, 10);
    //die;
    goToNext($MAP, $sX, $sY);
    echo "\n";
    //error_log(var_export($MAP, true));
    // Write an action using echo(). DON'T FORGET THE TRAILING \n
    // To debug (equivalent to var_dump): error_log(var_export($var, true));

    //echo("RIGHT RIGHT RIGHT DOWN\n"); // Use this function to dump all your movements (example: "RIGHT RIGHT UP UP UP")


    function findPath(&$MAP, $X, $Y, $i)
    {
        $WAVE = oneWave($MAP, $X, $Y, ++$i);

        while (count($WAVE)) {
            $i++;
            $newWave = [];
            foreach ($WAVE as $X => $W) {
                foreach ($W as $Y => $x) {
                    oneWave($MAP, $X, $Y, $i, $newWave);
                }
            }
            $WAVE = $newWave;
        }
    }

    function oneWave(&$MAP, $X, $Y, $i, &$WAVE = [])
    {
        //error_log(var_export($Y." ".$maxY, true));
        if ($Y > 0) {
            if (empty($MAP[$X][$Y - 1])) { //UP
                $MAP[$X][$Y - 1] = $i;
                $WAVE[$X][$Y - 1] = 1;
                //error_log(var_export('UP', true));

            } elseif ($MAP[$X][$Y - 1] == 'S') {
                return array();
            }
        }

        if ($Y < 8) {
            if (empty($MAP[$X][$Y + 1])) { //DOWN
                $MAP[$X][$Y + 1] = $i;
                $WAVE[$X][$Y + 1] = 1;
                //error_log(var_export('DOWN', true));

            } elseif ($MAP[$X][$Y + 1] == 'S') {
                return array();
            }
        }

        //error_log(var_export($X." ".$maxX, true));
        if ($X > 0) {
            if (empty($MAP[$X - 1][$Y])) { //LEFT
                $MAP[$X - 1][$Y] = $i;
                $WAVE[$X - 1][$Y] = 1;
                //error_log(var_export('LEFT', true));

            } elseif ($MAP[$X - 1][$Y] == 'S') {
                return array();
            }
        }

        if ($X < 15) {
            if (empty($MAP[$X + 1][$Y])) { //RIGHT
                $MAP[$X + 1][$Y] = $i;
                $WAVE[$X + 1][$Y] = 1;
                //error_log(var_export('RIGHT', true));

            } elseif ($MAP[$X + 1][$Y] == 'S') {
                return array();
            }
        }


        return $WAVE;
    }

    function showMAP($MAP, $xMax, $yMax)
    {
        $sMap = "\n";
        $sMap .= " ||";

        for ($x = 0; $x < $xMax; $x++) {
            if ($x < 10)
                $sMap .= " ";
            $sMap .= $x . "|";
        }
        $sMap .= "\n";

        for ($y = 0; $y < $yMax; $y++) {
            if ($x < 10)
                $sMap .= " ";
            $sMap .= $y . "||";

            for ($x = 0; $x < $xMax; $x++) {
                if (empty($MAP[$x][$y])) {
                    $sMap .= "00|";
                } else {
                    if ($MAP[$x][$y] < 10)
                        $sMap .= " ";

                    $sMap .= $MAP[$x][$y] . "|";
                }
            }

            $sMap .= "\n\n";
        }

        error_log(var_export($sMap, true));
    }

    function goToExit(&$MAP, $X, $Y)
    {
        while ($MAP[$X][$Y]) {
            $RIGHT = $DOWN = $LEFT = $UP = 9999;

            if (isset($MAP[$X + 1][$Y]) && $MAP[$X + 1][$Y] != '#')
                $RIGHT = $MAP[$X + 1][$Y];

            if (isset($MAP[$X][$Y + 1]) && $MAP[$X][$Y + 1] != '#')
                $DOWN = $MAP[$X][$Y + 1];

            if (isset($MAP[$X - 1][$Y]) && $MAP[$X - 1][$Y] != '#')
                $LEFT = $MAP[$X - 1][$Y];

            if (isset($MAP[$X][$Y - 1]) && $MAP[$X][$Y - 1] != '#')
                $UP = $MAP[$X][$Y - 1];

            error_log(var_export($RIGHT . " " . $DOWN . " " . $LEFT . " " . $UP . " ", true));


            if ($RIGHT <= $LEFT && $RIGHT <= $DOWN && $RIGHT <= $UP) {
                echo "RIGHT";
                $X++;
            } elseif ($DOWN < $UP && $DOWN <= $LEFT) {
                echo "DOWN";
                $Y++;
            } elseif ($LEFT < $RIGHT && $LEFT <= $UP) {
                echo "LEFT";
                $X--;
            } else {
                echo "UP";
                $Y--;
            }
            echo " ";
        }
    }
