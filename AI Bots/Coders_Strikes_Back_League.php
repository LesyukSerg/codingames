<?php
    $game = new Game();

    while (true) {
        fscanf(STDIN, "%d %d %d %d %d %d",
            $x,
            $y,
            $nextPointX, // x position of the next check point
            $nextPointY, // y position of the next check point
            $Distance, // distance to the next checkpoint
            $pointAngle // angle between your pod orientation and the direction of the next checkpoint
        );

        fscanf(STDIN, "%d %d", $opponentX, $opponentY);

        if (!empty($game->oldPosition)) {
            $vMove = $game->findVector($game->oldPosition['x'], $game->oldPosition['y'], $x, $y);
            //error_log(var_export('move vector - ', true));
            //error_log(var_export($vMove, true));
        } else {
            $vMove = 0;
        }

        $game->update_data($nextPointX, $nextPointY, $x, $y);

        $vPoint = $game->findVector($x, $y, $nextPointX, $nextPointY);


        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));


        // You have to output the target position
        // followed by the power (0 <= thrust <= 100)
        // i.e.: "x y thrust"
        //$speed = get_speed($Distance, abs($pointAngle));
        //error_log(var_export($speed, true));
        if ($vMove) {
            //error_log(var_export('point vector - ', true));
            //error_log(var_export($vPoint, true));
            $angle = $game->angleBetweenVectors($vPoint, $vMove);
        } else {
            $angle = 0;
        }

        if ($angle > 10) {
            //error_log(var_export('angle - ' . $angle, true));
            $go = $game->mirrorVector($vMove, $vPoint);
            $go['x'] += $x;
            $go['y'] += $y;

            //error_log(var_export('mirror - ', true));
            //error_log(var_export($go, true));
        } else {
            $go = array('x' => $nextPointX, 'y' => $nextPointY);
        }

        $speed = $game->get_speed($Distance, $angle, $pointAngle);

        echo $go['x'] . " " . $go['y'] . " " . $speed . "\n";


        $game->oldPoint = $nextPointX . '_' . $nextPointY;
    }


    class Game
    {
        public $startFlag;
        public $Boost;
        public $prevAngle;
        public $point;
        public $points;
        public $oldPoint;
        public $oldPosition;

        public function __construct()
        {
            $this->startFlag = 1;
            $this->Boost = 1;
            $this->prevAngle = 0;
            $this->point = 0;
            $this->points = [];
        }

        public function update_data($nextPointX, $nextPointY, $xPos, $yPos)
        {
            $this->oldPosition = array('x' => $xPos, 'y' => $yPos);

            if ($this->oldPoint != $nextPointX . '_' . $nextPointY) {
                $this->point++;
            }

            $this->points[$nextPointX . '_' . $nextPointY] = $nextPointX . '_' . $nextPointY;
        }

        public function findVector($x1, $y1, $x2, $y2)
        {
            $B['x'] = $x2 - $x1;
            $B['y'] = $y2 - $y1;

            return $B;
        }

        public function angleBetweenVectors($v1, $v2)
        {
            $ab = $v2['x'] * $v1['x'] + $v2['y'] * $v1['y'];
            $modA = sqrt(pow($v1['x'], 2) + pow($v1['y'], 2));
            $modB = sqrt(pow($v2['x'], 2) + pow($v2['y'], 2));

            $modA = (!$modA) ? 1 : $modA;
            $modB = (!$modB) ? 1 : $modB;
            $angle = rad2deg(acos($ab / ($modA * $modB)));

            //error_log(var_export('угол - ' . $angle, true));

            if (!$angle) $angle = 1;

            return $angle;
        }

        public function mirrorVector($v1, $v2)
        {
            $V['x'] = $v2['x'] - 2 * $v1['x'];
            $V['y'] = $v2['y'] - 2 * $v1['y'];
            //error_log(var_export('mirror - ', true));
            //error_log(var_export($V, true));

            /*$angle = $this->angleBetweenVectors($v1, $v2);
            $modA = sqrt(pow($v1['x'], 2) + pow($v1['y'], 2));

            $ab /($modA*$modB) = cos(dec2rad($angle*2));



            $ab = $v2['x'] * $v1['vx'] + $v2['y'] * $v1['vy'];

            $modB = sqrt(pow($v2['x'], 2) + pow($v2['y'], 2));

            $modA = (!$modA) ? 1 : $modA;
            $modB = (!$modB) ? 1 : $modB;
            $angle = rad2deg(acos($ab / ($modA * $modB)));

            if (!$angle) $angle = 1;*/

            return $V;
        }

        public function get_speed($dist, $angle, $pointAngle)
        {
            error_log(var_export('dist - ' . $dist, true));
            error_log(var_export('angle - ' . $angle, true));
            $lastPoint = count($this->points)*3;


            if ($this->startFlag && $dist > 3000) {
                $speed = 100;

            } else {
                $this->startFlag = 0;

                if ($dist > 3000 && ($angle < 120 || $pointAngle < 90)) {
                    $speed = 100;
                } else {
                    if($pointAngle > 90) {
                        $speed = 30;
                    } else {
                        $speed = 100;
                    }
                }
            }

            if ($this->point == $lastPoint) $speed = 100;

            if ($pointAngle < 20 && $dist > 6000 && $this->Boost && !$this->startFlag) {
                $speed = "BOOST";
                $this->Boost--;
            }

            return $speed;
        }
    }
