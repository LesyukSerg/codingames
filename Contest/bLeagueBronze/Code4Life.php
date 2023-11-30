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

    // game loop
    while (true) {
        $samples = [];
        $mySample = $need_diagnosis = $one = $enemy = $my = [];
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
                    $need_diagnosis[$sampleId] = $one;
                } else {
                    $mySample[] = $one;
                }
            }
        }

        if (!$my['eta']) {
            $pos = $my['target'];

            if ($pos == 'START_POS') {
                gotoModule('SAMPLES');

            } elseif ($pos == 'SAMPLES') { # === SAMPLES =========================================================
                if (count($need_diagnosis) + count($mySample) < 3) {
                    if ($my['score'] < 5) {
                        connect('SAMPLES', 1);
                    } elseif ($my['score'] > 100) {
                        connect('SAMPLES', 3);
                    } else {
                        connect('SAMPLES', 2);
                    }
                } else {
                    gotoModule('DIAGNOSIS');
                }
            } elseif ($pos == 'DIAGNOSIS') { # === DIAGNOSIS =========================================================
                //error_log(var_export($mySample, true));

                if (count($need_diagnosis)) {
                    $one = array_shift($need_diagnosis);
                    connect('DIAGNOSIS', $one['sampleId']);

                } else {

                    if (count($mySample) > 0) {
                        $sample = chooseSample($mySample, $my['storage'], $my['expertise']);

                        if ($sample) {
                            gotoModule('LABORATORY');
                        } else {
                            $sum = array_sum($my['storage']);

                            if ($sum == 10) {
                                foreach ($mySample as $one) {
                                    connect('DIAGNOSIS', $one['sampleId']);
                                    break;
                                }

                            } else {
                                gotoModule('MOLECULES');
                            }
                        }
                    } else {
                        if (count($healths)) {
                            krsort($healths);
                            // error_log(var_export($healths, true));

                            $sample = chooseSample($healths, $my['storage'], $my['expertise']);
                            error_log(var_export('+++', true));

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
                            if (count($mySample) < 3) {
                                gotoModule('SAMPLES');
                            } else {
                                gotoModule('DIAGNOSIS');
                            }
                        }
                    }

                } else {
                    if ($sample) {
                        gotoModule('LABORATORY');

                    } elseif (count($mySample) < 3) {
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
                        if (count($mySample)) {
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
