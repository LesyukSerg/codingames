<?php
    $game = new Game();

// game loop
    while (true) {
        $game->myPods = $game->update_pods_data();
        $game->enemyPods = $game->update_pods_data();

        $game->calculate_action();
        // Write an action using echo(). DON'T FORGET THE TRAILING \n
        // To debug (equivalent to var_dump): error_log(var_export($var, true));
    }

    class Game
    {
        public $checkPoints;
        public $nextPoint;
        public $myPods;
        public $enemyPods;
        public $startFlag;
        public $Boost;
        public $podsLap;
        public $laps;
        public $ePodsLap;

        public function __construct()
        {
            $this->podsLap = array('lap' => array(0, 0), 'point' => array(0, 0));
            $this->ePodsLap = array('lap' => array(0, 0), 'point' => array(0, 0));
            $this->Boost = 1;
            $this->startFlag = 1;
            $this->waitForIt = 5;
            $checkpointX = $checkpointY = 0;
            fscanf(STDIN, "%d", $this->laps);
            fscanf(STDIN, "%d", $checkpointCount);

            for ($i = 0; $i < $checkpointCount; $i++) {
                fscanf(STDIN, "%d %d", $checkpointX, $checkpointY);
                $this->checkPoints[] = array('x' => $checkpointX, 'y' => $checkpointY);
            }
        }

        public function update_pods_data()
        {
            $pods = [];
            $x = $y = $vx = $vy = $angle = $nextCheckPointId = 0;

            for ($i = 0; $i < 2; $i++) {
                fscanf(STDIN, "%d %d %d %d %d %d", $x, $y, $vx, $vy, $angle, $nextCheckPointId);

                $pods[] = array(
                    'x'      => $x,
                    'y'      => $y,
                    'vx'     => $vx,
                    'vy'     => $vy,
                    'angle'  => $angle,
                    'nextID' => $nextCheckPointId
                );
            }

            return $pods;
        }

        public function calculate_action()
        {
            for ($i = 0; $i < 2; $i++) {
                if ($this->podsLap['point'][$i] != $this->myPods[$i]['nextID']) {
                    if ($this->podsLap['point'][$i] > $this->myPods[$i]['nextID']) {
                        $this->podsLap['lap'][$i]++;
                    }

                    $this->podsLap['point'][$i] = $this->myPods[$i]['nextID'];
                }
            }

            for ($i = 0; $i < 2; $i++) {
                if ($this->ePodsLap['point'][$i] != $this->enemyPods[$i]['nextID']) {
                    if ($this->ePodsLap['point'][$i] > $this->enemyPods[$i]['nextID']) {
                        $this->ePodsLap['lap'][$i]++;
                    }

                    $this->ePodsLap['point'][$i] = $this->enemyPods[$i]['nextID'];
                }
            }
            //error_log(var_export($this->ePodsLap, true));
            //error_log(var_export($this->podsLap, true));

            if ($this->podsLap['lap'][0] == $this->podsLap['lap'][1]) {
                $pod = $this->myPods[0];
                $this->nextPoint = $this->checkPoints[$pod['nextID']];
                echo $this->racer2($this->myPods[0], $this->myPods[1]);

                $pod = $this->myPods[1];
                $this->nextPoint = $this->checkPoints[$pod['nextID']];
                echo $this->racer2($this->myPods[1], $this->myPods[0]);

            } else {
                if ($this->podsLap['lap'][0] > $this->podsLap['lap'][1]) {
                    $pod = $this->myPods[0];
                    $this->nextPoint = $this->checkPoints[$pod['nextID']];
                    echo $this->racer2($this->myPods[0], $this->myPods[1]);
                    echo $this->taran($this->myPods[1]);
                } else {
                    $pod = $this->myPods[1];
                    $this->nextPoint = $this->checkPoints[$pod['nextID']];
                    echo $this->taran($this->myPods[0]);
                    echo $this->racer2($this->myPods[1], $this->myPods[0]);
                }
            }
        }

        public function racer($pod, $second)
        {
            // calc enemy distance
            $distance = [];
            foreach ($this->enemyPods as $ePod) {
                $distance[] = $this->get_distance($pod, $ePod);
            }
            $distance[] = $this->get_distance($pod, $second);

            $distance = min($distance);

            if ($distance > 100) {
                $speed = $this->get_speed($pod);
                $angle = ceil($this->angleBetweenVectors($pod, $this->nextPoint));

                if ($pod['vx'] && $pod['vy'] && $angle > 1) {
                    $new = $this->mirrorVektor($pod, $this->nextPoint);

                    //error_log(var_export($new, true));
                    return $new['x'] . ' ' . $new['y'] . " $speed\n";
                } else {
                    return $this->nextPoint['x'] . ' ' . $this->nextPoint['y'] . " 100\n";
                }
            } else {

                return $this->nextPoint['x'] . ' ' . $this->nextPoint['y'] . " 100\n";
            }
        }

        public function racer2($pod, $second)
        {
            // calc enemy distance
            /*$distance = [];
            foreach ($this->enemyPods as $ePod) {
                $distance[] = $this->get_distance($pod, $ePod);
            }
            $distance[] = $this->get_distance($pod, $second);
            $distance = min($distance);*/

            if ($pod['vx'] && $pod['vy']) {
                $angle = $this->angleBetweenVectors($pod, $this->nextPoint);
            } else {
                $angle = 0;
            }

            if ($angle > 10) {
                $go = $this->mirrorVektor($pod, $this->nextPoint);
                $go['x'] += $pod['x'];
                $go['y'] += $pod['y'];
            } else {
                $go = array('x' => $this->nextPoint['x'], 'y' => $this->nextPoint['y']);
            }

            $speed = $this->get_speed2($pod, $angle);

            return $go['x'] . ' ' . $go['y'] . " $speed R\n";
        }

        public function taran($taran)
        {
            $enemy1 = $this->enemyPods[0];
            $enemy2 = $this->enemyPods[1];

            if ($this->ePodsLap['lap'][0] == $this->ePodsLap['lap'][1]) {
                if ($enemy1['nextID'] > $enemy2['nextID']) {
                    $closestEnemy = $enemy1;
                } else {
                    $closestEnemy = $enemy2;
                }

            } elseif ($this->ePodsLap['lap'][0] > $this->ePodsLap['lap'][1]) {
                $closestEnemy = $enemy1;
            } else {
                $closestEnemy = $enemy2;
            }


            /*$distance = [];
            foreach ($this->enemyPods as $ePod) {
                $distance[] = $this->get_distance($taran, $ePod);
            }

            $minDist = min($distance);
            $k = array_search($minDist, $distance);
            $closestEnemy = $this->enemyPods[$k];*/

            $angle = $this->angleBetweenVectors($taran, $closestEnemy);

            if ($angle > 10) {
                //error_log(var_export('angle - ' . $angle, true));
                $go = $this->mirrorVektor($taran, $closestEnemy);
                $go['x'] += $taran['x'];
                $go['y'] += $taran['y'];

                //error_log(var_export('mirror - ', true));
                //error_log(var_export($go, true));
            } else {
                $go = array('x' => $closestEnemy['x'], 'y' => $closestEnemy['y']);
            }

            $eSpeed = $this->get_speed_enemy($taran, $closestEnemy);

            return $go['x'] . ' ' . $go['y'] . " $eSpeed T\n";
        }

        public function get_speed($pod)
        {
            $distance = $this->get_distance($pod, $this->nextPoint);
            $angle = ceil($this->angleBetweenVectors($pod, $this->nextPoint));
            //error_log(var_export('angle - ' . $angle, true));
            //error_log(var_export('distance - ' . $distance, true));

            if ($this->startFlag && $distance > 4000) {
                $speed = 100;
            } else {
                $this->startFlag = 0;

                if ($angle < 30 || ($pod['vx'] < 100 && $pod['vy'] < 100)) {
                    $speed = ceil($distance / 20);

                    if ($speed > 100) {
                        $speed = 100;
                    } elseif ($speed < 50) {
                        $speed = 50;
                    }

                } else {
                    if ($distance < 3000) {
                        $speed = ceil($distance / 30);
                        $speed = ($speed < 20) ? 100 : $speed;
                    } else {
                        $speed = 100;
                    }
                }
            }

            return $speed;
        }

        public function get_speed2($pod, $angle)
        {
            $dist = $this->get_distance($pod, $this->nextPoint);
            error_log(var_export('dist - ' . $dist, true));
            error_log(var_export('angle - ' . $angle, true));
            $lastPoint = count($this->checkPoints) * $this->laps;

            if ($this->startFlag && $dist > 3000) {
                $speed = 100;

            } else {
                $this->startFlag = 0;

                if ($dist > 3000 && ($angle < 120 || $pod['angle'] < 90)) {
                    $speed = 100;
                } else {
                    if ($pod['angle'] > 90) {
                        $speed = 30;
                    } else {
                        $speed = 100;
                    }
                }
            }

            //if ($this->podsLap['lap'][$index] == $lastPoint) $speed = 100;
            error_log(var_export('boost - ' . $this->Boost, true));
            if ($pod['angle'] < 20 && $dist > 4000 && $this->Boost) {
                $speed = "BOOST";
                $this->Boost--;
            }

            return $speed;
        }

        public function get_speed_enemy($pod, $enemy)
        {
            $distance = $this->get_distance($pod, $enemy);

            if ($distance > 1000) {
                $angle = ceil($this->angleBetweenVectors($pod, $enemy));

                if ($angle < 60) {
                    $speed = 100;
                } else {
                    $speed = 50;
                }
            } else {
                $speed = "SHIELD";
            }

            return $speed;
        }

        public function get_distance($pod, $point)
        {
            $d = sqrt(pow($pod['x'] - $point['x'], 2) + pow($pod['y'] - $point['y'], 2));

            return ceil($d);
        }

        public function angleBetweenVectors($pod, $point)
        {
            $B = []; // vektor from pod to point
            $B['x'] = $point['x'] - $pod['x'];
            $B['y'] = $point['y'] - $pod['y'];

            $ab = $B['x'] * $pod['vx'] + $B['y'] * $pod['vy'];
            $modA = sqrt(pow($pod['vx'], 2) + pow($pod['vy'], 2));
            $modB = sqrt(pow($B['x'], 2) + pow($B['y'], 2));

            $modA = (!$modA) ? 1 : $modA;
            $modB = (!$modB) ? 1 : $modB;
            $angle = rad2deg(acos($ab / ($modA * $modB)));

            if (!$angle) $angle = 1;

            return $angle;
        }

        public function mirrorVektor($pod, $point)
        {
            $B = []; // vektor from pod to point
            $B['x'] = $point['x'] - $pod['x'];
            $B['y'] = $point['y'] - $pod['y'];

            $V['x'] = $B['x'] - 2 * $pod['vx'];
            $V['y'] = $B['y'] - 2 * $pod['vy'];

            return $V;
        }
    }
