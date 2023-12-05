<?php
//define('STDIN', fopen('input.txt', 'r'));

    fscanf(STDIN, "%d %d",
        $w, // width of the building.
        $h // height of the building.
    );

    fscanf(STDIN, "%d", $turns); // maximum number of turns before game over.
    fscanf(STDIN, "%d %d", $posX, $posY);

    $start = ['x' => 0, 'y' => 0];
    $end = ['x' => $w - 1, 'y' => $h - 1];
    $pos = ['x' => $posX, 'y' => $posY];
    $historyDir = [];

    while (true) {
        //define('STDIN2', fopen('input2.txt', 'r'));
        // Current distance to the bomb compared to previous distance (COLDER, WARMER, SAME or UNKNOWN)
        fscanf(STDIN, "%s", $bombDir);
        $historyDir[] = $bombDir;

        $old[] = $pos;
        $pos = analyzeDir($bombDir, $start, $end, $pos, $old, $historyDir);

        echo "{$pos['x']} {$pos['y']}\n";
    }


    function analyzeDir($bombDir, &$start, &$end, $pos, $old, $historyDir)
    {
        error_log(var_export($bombDir, true));
        error_log(var_export($old, true));
        $lastPos = $old[count($old) - 2];

        switch ($bombDir) {
            case 'COLDER':
                changeAreaC($lastPos, $pos, $start, $end);
                list($x, $y) = setNewCoordinate($start, $end, $pos);

                break;

            case 'WARMER':
                changeAreaW($lastPos, $pos, $start, $end);

                $myStart = $start;
                $myEnd = $end;

                if ($pos['x'] - $start['x'] > $end['x'] - $pos['x']) {
                    $myStart['x'] = $start['x'];
                    $myEnd['x'] = $pos['x'];
                } else {
                    $myStart['x'] = $pos['x'];
                    $myEnd['x'] = $end['x'];
                }

                if ($pos['y'] - $start['y'] > $end['y'] - $pos['y']) {
                    $myStart['y'] = $start['y'];
                    $myEnd['y'] = $pos['y'];
                } else {
                    $myStart['y'] = $pos['y'];
                    $myEnd['y'] = $end['y'];
                }

                list($x, $y) = setNewCoordinate($myStart, $myEnd, $pos);

                break;

            case 'SAME':
                changeAreaS($lastPos, $pos, $start, $end);
                list($x, $y) = setNewCoordinate($start, $end, $pos);
                break;

            case 'UNKNOWN':
                list($x, $y) = setNewCoordinate($start, $end, $pos);
        }

        error_log(var_export('area', true));
        error_log(var_export($start, true));
        error_log(var_export($end, true));

        return ['x' => $x, 'y' => $y];
    }

    function changeAreaW($old, $new, &$start, &$end)
    {
        if ($old['x'] < $new['x']) {
            $newStart = $old['x'] + ceil(($new['x'] - $old['x']) / 2);

            if ($newStart >= $start['x']) {
                $start['x'] = $newStart;
            } else {
                $start['x']++;
            }

        } elseif ($old['x'] > $new['x']) {
            $newEnd = $new['x'] + ceil(($old['x'] - $new['x']) / 2);

            if ($newEnd >= $end['x']) {
                $end['x']--;
            } else {
                $end['x'] = $newEnd;
            }
        }

        if ($old['y'] < $new['y']) {
            $newStart = $old['y'] + ceil(($new['y'] - $old['y']) / 2);

            if ($newStart >= $start['y']) {
                $start['y'] = $newStart;
            } else {
                $start['y']++;
            }

        } elseif ($old['y'] > $new['y']) {
            $newEnd = $new['y'] + ceil(($old['y'] - $new['y']) / 2);

            if ($newEnd >= $end['y']) {
                $end['y']--;
            } else {
                $end['y'] = $newEnd;
            }
        }
    }

    function changeAreaC($old, $new, &$start, &$end)
    {
        if ($old['x'] < $new['x']) {
            $end['x'] = $old['x'] + ceil(($new['x'] - $old['x']) / 2);
        } elseif ($old['x'] > $new['x']) {
            $start['x'] = $new['x'] + ceil(($old['x'] - $new['x']) / 2);
        }

        if ($old['y'] < $new['x']) {
            $end['x'] = $old['x'] + ceil(($new['x'] - $old['x']) / 2);
        } elseif ($old['x'] > $new['x']) {
            $start['x'] = $new['x'] + ceil(($old['x'] - $new['x']) / 2);
        }

        if ($old['x'] < $new['x']) {
            $end['x'] = $old['x'];
        } elseif ($old['y'] > $new['x']) {
            $start['x'] = $new['x'];
        }

        if ($old['y'] < $new['y']) {
            $end['y'] = $old['y'];
        } elseif ($old['y'] > $new['y']) {
            $start['y'] = $new['y'];
        }
    }

    function changeAreaS($old, $new, &$start, &$end)
    {
        if ($old['x'] != $new['x']) {
            if ($new['x'] > $old['x']) {
                $start['x'] = $end['x'] = $old['x'] + ceil(abs($new['x'] - $old['x']) / 2);
            } else {
                $start['x'] = $end['x'] = $old['x'] - ceil(abs($new['x'] - $old['x']) / 2);
            }
        }

        if ($old['y'] != $new['y']) {
            if ($new['y'] > $old['y']) {
                $start['y'] = $end['y'] = $old['y'] + ceil(abs($new['y'] - $old['y']) / 2);
            } else {
                $start['y'] = $end['y'] = $old['y'] - ceil(abs($new['y'] - $old['y']) / 2);
            }
        }
    }

    function setNewCoordinate($start, $end, $pos)
    {
        if ($start['x'] == $end['x'] && $start['y'] == $end['y']) {
            $x = $end['x'];
            $y = $end['y'];
        } else {
            if (($end['y'] - $start['y']) > ($end['x'] - $start['x'])) {
                $y = $start['y'] + ceil(($end['y'] - $start['y']) / 2);
                $x = $pos['x'];
            } else {
                $x = $start['x'] + ceil(($end['x'] - $start['x']) / 2);
                $y = $pos['y'];
            }
        }

        return ['x' => $x, 'y' => $y];
    }