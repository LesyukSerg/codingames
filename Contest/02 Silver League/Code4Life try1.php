<?php
    function gotoModule($module)
    {
        echo "GOTO " . $module . "\n";
    }

    function connect($module, $data, $comment = '')
    {
        echo "CONNECT " . $data . " " . $comment . "\n";
    }

    function needMolecules($storage, $samples, $total)
    {
        $M = 'ABCDE';

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

    function chooseSample($samples, $storage)
    {




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

    function collectLesser($available)
    {
        $M = 'ABCDE';
        asort($available);

        foreach($available as $k => $count) {
            if ($count) {
                return $M[$k];
            }
        }

        return 0;
    }

    function accurateProportion($myScore, $carryCnt)
    {
        $prop = [ //proportions
            5   => [1, 1, 2],
            10  => [2, 2, 2],
            50  => [2, 2, 2],
            100 => [2, 2, 2],
            200 => [3, 2, 2],
            500 => [3, 3, 3]
        ];

        $prop = [ //proportions
            5   => [2, 1, 1],
            10  => [2, 2, 1],
            50  => [2, 2, 1],
            100 => [2, 2, 2],
            150 => [3, 2, 2],
            200 => [3, 3, 2],
            300 => [3, 3, 2]
        ];

        $prop = [ //proportions
            5   => [1, 1, 1],
            10  => [1, 1, 1],
            50  => [2, 2, 1],
            100 => [3, 2, 2],
            150 => [3, 3, 1],
            200 => [3, 3, 2],
            300 => [3, 3, 3]
        ];

        foreach ($prop as $score => $need) {
            if ($myScore < $score) {
                return $need[$carryCnt];
            }
        }

        return 1;
    }

    function badForProject($mySample, $projects)
    {
        foreach ($mySample as $sample) {
            $gain = $sample['gain'];

            if (!$projects[$gain]) {
                return $sample;
            }
        }

        return 0;
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

##================================================================================================
# Bring data on patient samples from the diagnosis machine to the laboratory with enough molecules to produce medicine!
##================================================================================================

    $gameStep = 0;
    $canCarry = 3;

    fscanf(STDIN, "%d", $projectCount);

    for ($i = 0; $i < $projectCount; $i++) {
        $n = 0;
        fscanf(STDIN, "%d %d %d %d %d", $a, $b, $c, $d, $e);
        $n += $a ? 1 : 0;
        $n += $b ? 1 : 0;
        $n += $c ? 1 : 0;
        $n += $d ? 1 : 0;
        $n += $e ? 1 : 0;

        $projects[$n]['A'] = $a;
        $projects[$n]['B'] = $b;
        $projects[$n]['C'] = $c;
        $projects[$n]['D'] = $d;
        $projects[$n]['E'] = $e;
    }

    $gameStep = 0;
    $canCarry = 3;

    // game loop
    while (true) {
        $gameStep += 2;
        $samples = [];
        $available = $mySample = $needDiagnos = $one = $enemy = $my = [];

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
                $my['storageCnt'] = array_sum($my['storage']);

                $my['storage'][0] += $my['expertise'][0];
                $my['storage'][1] += $my['expertise'][1];
                $my['storage'][2] += $my['expertise'][2];
                $my['storage'][3] += $my['expertise'][3];
                $my['storage'][4] += $my['expertise'][4];
            }
        }


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
                $one['gain'],
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
                $healths[$one['health'] . "_" . $sampleId] = $one;

            } elseif ($one['carriedBy'] == 0) {
                if ($one['health'] == -1) {
                    $needDiagnos[$sampleId] = $one;
                } else {
                    $mySample[$one['health'] . "_" . $sampleId] = $one;
                    //krsort($mySample);
                }
            }
        }

        //if ($gameStep > 300) {
        //  $canCarry = 2;
        //}

        if (!$my['eta']) {
            $pos = $my['target'];

            if ($pos == 'START_POS') {
                gotoModule('SAMPLES');

            } elseif ($pos == 'SAMPLES') { # === SAMPLES =========================================================
                $carryCnt = count($needDiagnos) + count($mySample);

                if ($carryCnt < $canCarry) {
                    $rank = accurateProportion($my['score'], $carryCnt);
                    connect('SAMPLES', $rank);

                    /*if ($my['score'] < 5) {
                        connect('SAMPLES', 1);
                    } elseif ($my['score'] > 100) {
                        connect('SAMPLES', 3);
                    } else {
                        connect('SAMPLES', 2);
                    }*/
                } else {
                    gotoModule('DIAGNOSIS');
                }
            } elseif ($pos == 'DIAGNOSIS') { # === DIAGNOSIS =========================================================
                //error_log(var_export($mySample, true));

                if (count($needDiagnos)) {
                    $one = array_shift($needDiagnos);
                    connect('DIAGNOSIS', $one['sampleId']);

                } else {
                    if (count($mySample)) {
                        if (count($mySample) < $canCarry) {
                            if (count($healths)) {
                                krsort($healths);

                                $sample = chooseSample($healths, $my['storage']);

                                if ($sample) {
                                    connect('DIAGNOSIS', $sample['sampleId']);
                                } else {


                                    gotoModule('SAMPLES');
                                }

                            } else {
                                gotoModule('SAMPLES');
                            }
                        } else {
                            $sample = chooseSample($mySample, $my['storage']);

                            if ($sample) {
                                gotoModule('LABORATORY');
                            } else {
                                if ($my['storageCnt'] == 10) {
                                    $one = current($mySample);
                                    connect('DIAGNOSIS', $one['sampleId']);

                                } else {
                                    gotoModule('MOLECULES');
                                }
                            }
                        }

                    } else {
                        if (count($healths)) {
                            krsort($healths);

                            $sample = chooseSample($healths, $my['storage']);

                            if ($sample) {
                                connect('DIAGNOSIS', $sample['sampleId']);
                            } else {
                                gotoModule('SAMPLES');
                            }

                        } else {
                            gotoModule('SAMPLES');
                        }
                    }
                }

            } elseif ($pos == 'MOLECULES') { # === MOLECULES =========================================================
                //error_log(var_export($my, true));
                //error_log(var_export($mySample, true));
                $molecule = needMolecules($my['storage'], $mySample, $available);
                $sample = chooseSample($mySample, $my['storage']);

                if ($my['storageCnt'] < 10) {
                    if ($molecule) {
                        connect('MOLECULES', $molecule);

                        //} elseif ($gameStep < 200) {
                        //  $molecule = collectLesser($available);
                        //connect('MOLECULES', $molecule);

                    } else {
                        if ($sample) {
                            gotoModule('LABORATORY');
                        } else {
                            if (count($mySample) < $canCarry) {
                                if (count($healths)) {
                                    krsort($healths);

                                    $sample = chooseSample($healths, $my['storage']);

                                    if ($sample) {
                                        gotoModule('DIAGNOSIS');
                                    } else {
                                        gotoModule('SAMPLES');
                                    }

                                } else {
                                    gotoModule('SAMPLES');
                                }
                            } else {
                                gotoModule('DIAGNOSIS');
                            }
                        }
                    }

                } else {
                    if ($sample) {
                        gotoModule('LABORATORY');

                    } elseif (count($mySample) < $canCarry) {
                        if (count($healths)) {
                            krsort($healths);

                            $sample = chooseSample($healths, $my['storage']);

                            if ($sample) {
                                gotoModule('DIAGNOSIS');
                            } else {
                                gotoModule('SAMPLES');
                            }

                        } else {
                            gotoModule('SAMPLES');
                        }

                    } else {
                        gotoModule('DIAGNOSIS');
                    }
                }

            } elseif ($pos == 'LABORATORY') { # === LABORATORY ========================================================
                //error_log(var_export('==========', true));
                //error_log(var_export($my['storage'], true));
                $sample = chooseSample($mySample, $my['storage']);

                if ($sample) {
                    connect('LABORATORY', $sample['sampleId']);

                } else {
                    if ($my['storageCnt'] == 10) {
                        if (count($mySample) == $canCarry) {
                            gotoModule('DIAGNOSIS');
                        } else {
                            if (count($healths)) {
                                krsort($healths);

                                $sample = chooseSample($healths, $my['storage']);

                                if ($sample) {
                                    gotoModule('DIAGNOSIS');
                                } else {
                                    gotoModule('SAMPLES');
                                }

                            } else {
                                gotoModule('SAMPLES');
                            }
                        }

                    } else {
                        if (count($mySample)) {
                            $molecule = needMolecules($my['storage'], $mySample, $available);

                            if ($molecule) {
                                gotoModule('MOLECULES');
                            } else {
                                if (count($healths)) {
                                    krsort($healths);

                                    $sample = chooseSample($healths, $my['storage']);

                                    if ($sample) {
                                        gotoModule('DIAGNOSIS');
                                    } else {
                                        gotoModule('SAMPLES');
                                    }

                                } else {
                                    gotoModule('SAMPLES');
                                }
                            }

                        } else {
                            if (count($healths)) {
                                krsort($healths);

                                $sample = chooseSample($healths, $my['storage']);

                                if ($sample) {
                                    gotoModule('DIAGNOSIS');
                                } else {
                                    gotoModule('SAMPLES');
                                }

                            } else {
                                gotoModule('SAMPLES');
                            }
                        }
                    }
                }
            }
        } else {
            echo "\n";
        }


        //error_log(var_export($id, true));
    }
