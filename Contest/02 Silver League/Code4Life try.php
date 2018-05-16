<?php
    function gotoModule($module)
    {
        echo "GOTO " . $module . "\n";
    }

    function connect($module, $data, $comment = '')
    {
        echo "CONNECT " . $data . " " . $comment . "\n";
    }

    function needMolecules($storage, $samples, $total, $expertise)
    {
        $M = 'ABCDE';
        for ($i = 0; $i < 5; $i++) {
            $storage[$i] += $expertise[$i];
        }

        foreach ($samples as $sample) {
            foreach ($sample['cost'] as $i => $cnt) {
                if ($storage[$i] < $cnt && $total[$i]) {
                    return $M[$i];
                }
            }
        }

        $needTotal = [0, 0, 0, 0, 0];
        foreach ($samples as $sample) {
            foreach ($sample['cost'] as $i => $cnt) {
                $needTotal[$i] += $cnt;
            }
        }

        foreach ($needTotal as $i => $one) {
            if ($storage[$i] < $one && $total[$i]) {
                return $M[$i];
            }
        }

        return 0;
    }

    function chooseSample($samples, $storage, $expertise)
    {
        for ($i = 0; $i < 5; $i++) {
            $storage[$i] += $expertise[$i];
        }

        foreach ($samples as $sample) {
            $ok = 1;

            foreach ($sample['cost'] as $i => $cnt) {
                if ($storage[$i] < $cnt) {
                    $ok = 0;
                    break;
                }
            }

            if ($ok) {
                return $sample;
            }
        }

        return 0;
    }

    function accurateProportion($myScore, $carryCnt)
    {
        $prop = [ //proportions
                  5   => [0 => 1, 1 => 1, 2 => 1],
                  10  => [0 => 2, 1 => 1, 2 => 1],
                  50  => [0 => 2, 1 => 2, 2 => 1],
                  100 => [0 => 3, 1 => 2, 2 => 2],
                  200 => [0 => 3, 1 => 3, 2 => 2],
                  500 => [0 => 3, 1 => 3, 2 => 3]
        ];

        foreach ($prop as $score => $need) {
            if ($myScore < $score) {
                return $need[$carryCnt];
            }
        }

        return 1;
    }

    function throwBad($samples)
    {
        foreach ($samples as $one) {
            if ($one['health'] == 1) {
                return $one;
            }
        }

        return 0;
    }

    /**
     * Bring data on patient samples from the diagnosis machine to the laboratory with enough molecules to produce medicine!
     **/

    fscanf(STDIN, "%d", $projectCount);

    for ($i = 0; $i < $projectCount; $i++) {
        fscanf(STDIN, "%d %d %d %d %d", $a, $b, $c, $d, $e);
    }

    $canCarry = 3;

    // game loop
    while (true) {
        $samples = [];
        $mySample = $needDiagnos = $one = $enemy = $my = [];
        for ($i = 0; $i < 2; $i++) {
            fscanf(STDIN, "%s %d %d %d %d %d %d %d %d %d %d %d %d",
                $one['target'],
                $one['eta'],
                $one['score'],
                $one['storage'][],//A
                $one['storage'][],//B
                $one['storage'][],//C
                $one['storage'][],//D
                $one['storage'][],//E
                $one['expertise'][],
                $one['expertise'][],
                $one['expertise'][],
                $one['expertise'][],
                $one['expertise'][]
            );

            if ($i) {
                $enemy = $one;
            } else {
                $my = $one;
            }
        }

        $available = [];
        fscanf(STDIN, "%d %d %d %d %d",
            $available[],
            $available[],
            $available[],
            $available[],
            $available[]
        );

        fscanf(STDIN, "%d", $sampleCount);

        $healths = [];
        for ($i = 0; $i < $sampleCount; $i++) {
            $one = [];
            fscanf(STDIN, "%d %d %d %s %d %d %d %d %d %d",
                $sampleId,
                $one['carriedBy'],
                $one['rank'],
                $one['expertiseGain'],
                $one['health'],
                $one['cost'][],
                $one['cost'][],
                $one['cost'][],
                $one['cost'][],
                $one['cost'][]
            );

            $one['sampleId'] = $sampleId;
            $samples[$sampleId] = $one;

            if ($one['carriedBy'] == -1) {
                $healths[$sampleId] = $one;

            } elseif ($one['carriedBy'] == 0) {
                if ($one['health'] == -1) {
                    $needDiagnos[$sampleId] = $one;
                } else {
                    $mySample[$sampleId . "-" . $one['health']] = $one;
                    krsort($mySample);
                }
            }
        }

        if (!$my['eta']) {
            $pos = $my['target'];

            if ($pos == 'START_POS') {
                gotoModule('SAMPLES');

            } elseif ($pos == 'SAMPLES') { # === SAMPLES =========================================================
                $carryCnt = count($needDiagnos) + count($mySample);
                if ($carryCnt < $canCarry) {
                    $sum = array_sum($my['storage']);
                    if ($sum == 10) {
                        connect('SAMPLES', 1);
                    } else {
                        $rank = accurateProportion($my['score'], $carryCnt);
                        connect('SAMPLES', $rank);

                        /*if ($my['score'] < 5) {
                            connect('SAMPLES', 1);
                        } elseif ($my['score'] > 100) {
                            connect('SAMPLES', 3);
                        } else {
                            connect('SAMPLES', 2);
                        }*/
                    }
                } else {
                    gotoModule('DIAGNOSIS');
                }
            } elseif ($pos == 'DIAGNOSIS') { # === DIAGNOSIS =========================================================
                //error_log(var_export($mySample, true));

                if (count($needDiagnos)) {
                    $one = array_shift($needDiagnos);
                    connect('DIAGNOSIS', $one['sampleId']);

                } else {

                    if (count($mySample) > 0) {
                        $sample = chooseSample($mySample, $my['storage'], $my['expertise']);

                        if ($sample) {
                            gotoModule('LABORATORY');
                        } else {
                            $sum = array_sum($my['storage']);

                            if ($sum == 10) {
                                $one = current($mySample);
                                connect('DIAGNOSIS', $one['sampleId']);

                            } else {
                                gotoModule('MOLECULES');
                            }
                        }
                    } else {
                        if (count($healths)) {
                            krsort($healths);

                            $sample = chooseSample($healths, $my['storage'], $my['expertise']);

                            if ($sample) {
                                connect('DIAGNOSIS', $sample['sampleId']);
                            } else {
                                gotoModule('SAMPLES');
                                //error_log(var_export($healths, true));
                                //$sample = current($healths);
                                //error_log(var_export($sample, true));
                                //connect('DIAGNOSIS', $sample['sampleId']);
                            }

                        } else {
                            gotoModule('SAMPLES');
                        }
                    }
                }

            } elseif ($pos == 'MOLECULES') { # === MOLECULES =========================================================
                //error_log(var_export($my, true));
                //error_log(var_export($mySample, true));

                $sum = array_sum($my['storage']);
                $molecule = needMolecules($my['storage'], $mySample, $available, $my['expertise']);
                $sample = chooseSample($mySample, $my['storage'], $my['expertise']);

                if ($sum < 10) {
                    if ($molecule) {
                        connect('MOLECULES', $molecule);
                    } else {
                        if ($sample) {
                            gotoModule('LABORATORY');
                        } else {
                            $waitingSample = chooseSample($healths, $my['storage'], $my['expertise']);

                            if (count($mySample) < $canCarry && !$waitingSample) {
                                gotoModule('SAMPLES');
                            } else {
                                gotoModule('DIAGNOSIS');
                            }
                        }
                    }

                } else {
                    if ($sample) {
                        gotoModule('LABORATORY');

                    } elseif (count($mySample) < $canCarry && !count($healths)) {
                        gotoModule('SAMPLES');

                    } else {
                        gotoModule('DIAGNOSIS');
                    }
                }

            } elseif ($pos == 'LABORATORY') { # === LABORATORY ========================================================
                //error_log(var_export($mySample, true));
                $sample = chooseSample($mySample, $my['storage'], $my['expertise']);

                if ($sample) {
                    connect('LABORATORY', $sample['sampleId']);

                } else {
                    $sum = array_sum($my['storage']);

                    if ($sum == 10) {
                        if (count($mySample) == 3) {
                            gotoModule('DIAGNOSIS');
                        } else {
                            gotoModule('SAMPLES');
                        }

                    } else {
                        $molecule = needMolecules($my['storage'], $mySample, $available, $my['expertise']);

                        if (count($mySample) > 1 && $molecule) {
                            gotoModule('MOLECULES');

                        } else {
                            gotoModule('SAMPLES');
                        }
                    }
                }
            }
        } else {
            echo "\n";
        }


        /*//do {
        error_log(var_export($id, true));

        }*/
    }
